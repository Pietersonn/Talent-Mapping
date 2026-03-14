<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount(['testSessions', 'acaraSebagaiMitra']);

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('nama', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('peran', 'like', "%{$term}%")
                    ->orWhere('nomor_telepon', 'like', "%{$term}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('peran', $request->role);
        }

        $users = $query->latest()->paginate(10);

        if ($request->ajax()) {
            $users->getCollection()->transform(function ($user) {
                return [
                    'id'            => $user->id,
                    'name'          => $user->nama,
                    'email'         => $user->email,
                    'phone_number'  => $user->nomor_telepon ?? '-',
                    'role'          => ucfirst($user->peran),
                    'role_raw'      => $user->peran,
                    'is_active'     => $user->aktif,
                    'avatar_letter' => substr($user->nama, 0, 1),
                    'edit_url'      => route('admin.users.edit', $user->id),
                    'delete_url'    => route('admin.users.destroy', $user->id),
                    'show_url'      => route('admin.users.show', $user->id),
                ];
            });

            return response()->json([
                'users'           => $users,
                'current_user_id' => Auth::id(),
                'is_admin'        => Auth::user()->peran === 'admin',
            ]);
        }

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'           => ['required', 'string', 'max:100'],
            'email'          => ['required', 'email', 'unique:pengguna,email'],
            'nomor_telepon'  => ['nullable', 'string', 'max:20'],
            'peran'          => ['required', 'in:admin,mitra,peserta'],
            'password'       => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'nama'          => $request->nama,
            'email'         => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'peran'         => $request->peran,
            'password'      => Hash::make($request->password),
            'aktif'         => true,
        ]);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Pengguna berhasil dibuat.');
    }

    public function show(User $user)
    {
        $user->load('testSessions.testResult');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama'          => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email', \Illuminate\Validation\Rule::unique('pengguna', 'email')->ignore($user->id)],
            'nomor_telepon' => ['nullable', 'string', 'max:20'],
            'peran'         => ['required', 'in:admin,mitra,peserta'],
        ]);

        $user->update([
            'nama'          => $request->nama,
            'email'         => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'peran'         => $request->peran,
        ]);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }
        if ($user->testSessions()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus pengguna yang sudah memiliki sesi tes.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }
        $user->update(['aktif' => !$user->aktif]);
        $status = $user->aktif ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun pengguna berhasil {$status}.");
    }

    public function resetPassword(User $user)
    {
        $newPassword = Str::random(10);
        $user->update(['password' => Hash::make($newPassword)]);

        return back()->with('success', "Password berhasil direset. Password baru: {$newPassword}");
    }

    public function exportPdf(Request $request)
    {
        $query = User::query();
        if ($request->filled('role')) {
            $query->where('peran', $request->role);
        }
        $users = $query->orderBy('nama')->get();

        $pdf = Pdf::loadView('admin.users.pdf.userReport', [
            'reportTitle' => 'Laporan Daftar Pengguna',
            'generatedBy' => Auth::user()->nama,
            'generatedAt' => now()->format('d/m/Y H:i'),
            'rows'        => $users,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Pengguna.pdf');
    }
}

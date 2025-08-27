@extends('public.layouts.app')

@section('content')
<div class="thank-you-container">
    <div class="thank-you-content">
        <div class="thank-you-icon">
            ✓
        </div>

        <h1 class="thank-you-title">Terima Kasih!</h1>

        <div class="thank-you-message">
            <p>Test TalentMapping Anda telah berhasil diselesaikan. Hasil analisis kompetensi dan tipologi kekuatan akan segera diproses dan dikirimkan ke email Anda dalam 1-2 hari kerja.</p>

            <div class="test-summary">
                <div class="summary-item">
                    <strong>Nama:</strong> {{ $session->participant_name ?? Auth::user()->name }}
                </div>
                <div class="summary-item">
                    <strong>Email:</strong> {{ Auth::user()->email }}
                </div>
                @if($session->event)
                <div class="summary-item">
                    <strong>Event:</strong> {{ $session->event->name }}
                </div>
                @endif
                <div class="summary-item">
                    <strong>Selesai pada:</strong> {{ now()->format('d F Y, H:i') }}
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <a href="{{ route('home') }}" class="btn btn-primary">
                Kembali ke Beranda
            </a>

            @auth
                @if(auth()->user()->role !== 'user')
                    <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="btn btn-secondary">
                        Ke Dashboard
                    </a>
                @endif
            @endauth
        </div>

        <div class="next-steps">
            <h3>Langkah Selanjutnya:</h3>
            <ul>
                <li>Hasil akan dikirim ke email Anda</li>
                <li>Cek folder spam jika tidak diterima dalam 2 hari</li>
                <li>Hubungi admin jika ada pertanyaan</li>
                <li>Konsultasi lebih lanjut dapat dilakukan melalui kontak BCTI</li>
            </ul>
        </div>
    </div>
</div>

<style>
.thank-you-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #f0fff4 0%, #c6f6d5 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
}

.thank-you-content {
    background: white;
    padding: 60px 50px;
    border-radius: 20px;
    text-align: center;
    max-width: 700px;
    width: 100%;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.thank-you-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #48bb78, #38a169);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 30px;
    color: white;
    font-size: 48px;
    font-weight: bold;
    box-shadow: 0 10px 30px rgba(72, 187, 120, 0.3);
}

.thank-you-title {
    font-size: 42px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 25px;
}

.thank-you-message {
    font-size: 16px;
    color: #4a5568;
    line-height: 1.6;
    margin-bottom: 40px;
}

.test-summary {
    background: #f7fafc;
    border-radius: 12px;
    padding: 25px;
    margin: 30px 0;
    text-align: left;
}

.summary-item {
    padding: 8px 0;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    color: #4a5568;
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-item strong {
    color: #2d3748;
    min-width: 120px;
}

.action-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-bottom: 40px;
    flex-wrap: wrap;
}

.next-steps {
    text-align: left;
    background: #ebf8ff;
    padding: 25px;
    border-radius: 12px;
    border-left: 4px solid #3182ce;
}

.next-steps h3 {
    color: #2b6cb0;
    margin-bottom: 15px;
    font-size: 18px;
}

.next-steps ul {
    margin: 0;
    padding-left: 20px;
    color: #4a5568;
}

.next-steps li {
    margin-bottom: 8px;
    line-height: 1.4;
}

.btn {
    padding: 15px 30px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 16px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: linear-gradient(135deg, #48bb78, #38a169);
    color: white;
    box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(72, 187, 120, 0.4);
}

.btn-secondary {
    background: #e2e8f0;
    color: #4a5568;
    border: 2px solid #cbd5e0;
}

.btn-secondary:hover {
    background: #cbd5e0;
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .thank-you-content {
        padding: 40px 30px;
    }

    .thank-you-title {
        font-size: 32px;
    }

    .thank-you-icon {
        width: 80px;
        height: 80px;
        font-size: 36px;
    }

    .action-buttons {
        flex-direction: column;
        align-items: center;
    }

    .btn {
        width: 200px;
        justify-content: center;
    }
}
</style>
@endsection

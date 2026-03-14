{{-- SweetAlert2 Handler for Flash Messages --}}
<script>
// Tunggu dokumen siap DAN SweetAlert2 dimuat
$(document).ready(function() {
    // Cek apakah SweetAlert2 sudah dimuat
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 belum dimuat! Harap periksa CDN.');
        return;
    }

    // Pesan Sukses
    @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: `{{ session('success') }}`,
            icon: 'success',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
    @endif

    // Pesan Error / Gagal
    @if(session('error'))
        Swal.fire({
            title: 'Gagal!',
            text: `{{ session('error') }}`,
            icon: 'error',
            confirmButtonText: 'OK',
            confirmButtonColor: '#d33',
            allowOutsideClick: false
        });
    @endif

    // Pesan Peringatan
    @if(session('warning'))
        Swal.fire({
            title: 'Peringatan!',
            text: `{{ session('warning') }}`,
            icon: 'warning',
            confirmButtonText: 'OK',
            confirmButtonColor: '#f39c12'
        });
    @endif

    // Pesan Informasi
    @if(session('info'))
        Swal.fire({
            title: 'Informasi',
            text: `{{ session('info') }}`,
            icon: 'info',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true
        });
    @endif

    // Error Validasi
    @if($errors->any())
        let errorList = '';
        @foreach($errors->all() as $error)
            errorList += '• {{ addslashes($error) }}\n';
        @endforeach

        Swal.fire({
            title: 'Kesalahan Validasi!',
            text: errorList,
            icon: 'error',
            confirmButtonText: 'Perbaiki',
            confirmButtonColor: '#d33',
            allowOutsideClick: false,
            customClass: {
                content: 'text-left'
            },
            width: '500px'
        });
    @endif
});

/**
 * Global SweetAlert2 Functions - Enhanced & Fixed (Bahasa Indonesia)
 */

// Konfirmasi Hapus
function confirmDelete(title, text, deleteUrl, formId = null) {
    if (typeof Swal === 'undefined') {
        if (confirm(title + '\n' + text)) {
            if (formId) {
                document.getElementById(formId).submit();
            } else {
                window.location.href = deleteUrl;
            }
        }
        return;
    }

    Swal.fire({
        title: title || 'Apakah Anda yakin?',
        text: text || "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            if (formId) {
                document.getElementById(formId).submit();
            } else {
                // Buat dan kirim form secara manual
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;

                // Tambah CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfInput);

                // Tambah method DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            }
        }
    });
}

// Konfirmasi Ubah Status (Toggle)
function confirmToggleStatus(title, text, toggleUrl, currentStatus) {
    if (typeof Swal === 'undefined') {
        if (confirm(title + '\n' + text)) {
            window.location.href = toggleUrl;
        }
        return;
    }

    const actionText = currentStatus ? 'Nonaktifkan' : 'Aktifkan';
    const actionColor = currentStatus ? '#d33' : '#28a745';

    Swal.fire({
        title: title || `Konfirmasi ${actionText}?`,
        text: text || `Apakah Anda yakin ingin me-${actionText.toLowerCase()} item ini?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: actionColor,
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Ya, ${actionText}!`,
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Buat dan kirim form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = toggleUrl;

            // Tambah CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfInput);

            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Indikator Loading
function showLoading(title = 'Memuat...', text = 'Mohon tunggu...') {
    if (typeof Swal === 'undefined') return;

    Swal.fire({
        title: title,
        text: text,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
}

// Toast Sukses
function showSuccessToast(message, timer = 3000) {
    if (typeof Swal === 'undefined') {
        alert('Berhasil: ' + message);
        return;
    }

    Swal.fire({
        title: message,
        icon: 'success',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: timer,
        timerProgressBar: true
    });
}

// Toast Error
function showErrorToast(message, timer = 5000) {
    if (typeof Swal === 'undefined') {
        alert('Gagal: ' + message);
        return;
    }

    Swal.fire({
        title: message,
        icon: 'error',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: timer,
        timerProgressBar: true
    });
}

// Toast Peringatan
function showWarningToast(message, timer = 4000) {
    if (typeof Swal === 'undefined') {
        alert('Peringatan: ' + message);
        return;
    }

    Swal.fire({
        title: message,
        icon: 'warning',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: timer,
        timerProgressBar: true
    });
}

// Toast Informasi
function showInfoToast(message, timer = 4000) {
    if (typeof Swal === 'undefined') {
        alert('Info: ' + message);
        return;
    }

    Swal.fire({
        title: message,
        icon: 'info',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: timer,
        timerProgressBar: true
    });
}

// Konfirmasi Kustom (Custom Confirmation)
function customConfirm(options = {}) {
    if (typeof Swal === 'undefined') {
        return Promise.resolve({ isConfirmed: confirm(options.title + '\n' + options.text) });
    }

    const defaultOptions = {
        title: 'Apakah Anda yakin?',
        text: '',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal',
        reverseButtons: true
    };

    const mergedOptions = { ...defaultOptions, ...options };
    return Swal.fire(mergedOptions);
}

// Tutup SweetAlert yang terbuka
function closeSwal() {
    if (typeof Swal !== 'undefined') {
        Swal.close();
    }
}
</script>

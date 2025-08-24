{{-- SweetAlert2 Handler for Flash Messages --}}
<script>
// Wait for document ready AND SweetAlert2 to be loaded
$(document).ready(function() {
    // Check if SweetAlert2 is loaded
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 not loaded! Please check CDN.');
        return;
    }

    // Success Messages
    @if(session('success'))
        Swal.fire({
            title: 'Success!',
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

    // Error Messages
    @if(session('error'))
        Swal.fire({
            title: 'Error!',
            text: `{{ session('error') }}`,
            icon: 'error',
            confirmButtonText: 'OK',
            confirmButtonColor: '#d33',
            allowOutsideClick: false
        });
    @endif

    // Warning Messages
    @if(session('warning'))
        Swal.fire({
            title: 'Warning!',
            text: `{{ session('warning') }}`,
            icon: 'warning',
            confirmButtonText: 'OK',
            confirmButtonColor: '#f39c12'
        });
    @endif

    // Info Messages
    @if(session('info'))
        Swal.fire({
            title: 'Information',
            text: `{{ session('info') }}`,
            icon: 'info',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true
        });
    @endif

    // Validation Errors
    @if($errors->any())
        let errorList = '';
        @foreach($errors->all() as $error)
            errorList += '• {{ addslashes($error) }}\n';
        @endforeach

        Swal.fire({
            title: 'Validation Error!',
            text: errorList,
            icon: 'error',
            confirmButtonText: 'Fix Errors',
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
 * Global SweetAlert2 Functions - Enhanced & Fixed
 */

// Delete Confirmation
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
        title: title || 'Are you sure?',
        text: text || "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            if (formId) {
                document.getElementById(formId).submit();
            } else {
                // Create and submit form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;

                // Add CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfInput);

                // Add DELETE method
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

// Status Toggle Confirmation
function confirmToggleStatus(title, text, toggleUrl, currentStatus) {
    if (typeof Swal === 'undefined') {
        if (confirm(title + '\n' + text)) {
            window.location.href = toggleUrl;
        }
        return;
    }

    const actionText = currentStatus ? 'Deactivate' : 'Activate';
    const actionColor = currentStatus ? '#d33' : '#28a745';

    Swal.fire({
        title: title || `${actionText} Item?`,
        text: text || `Are you sure you want to ${actionText.toLowerCase()} this item?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: actionColor,
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Yes, ${actionText.toLowerCase()}!`,
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = toggleUrl;

            // Add CSRF token
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

// Loading Indicator
function showLoading(title = 'Loading...', text = 'Please wait...') {
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

// Success Toast
function showSuccessToast(message, timer = 3000) {
    if (typeof Swal === 'undefined') {
        alert('Success: ' + message);
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

// Error Toast
function showErrorToast(message, timer = 5000) {
    if (typeof Swal === 'undefined') {
        alert('Error: ' + message);
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

// Warning Toast
function showWarningToast(message, timer = 4000) {
    if (typeof Swal === 'undefined') {
        alert('Warning: ' + message);
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

// Info Toast
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

// Custom Confirmation
function customConfirm(options = {}) {
    if (typeof Swal === 'undefined') {
        return Promise.resolve({ isConfirmed: confirm(options.title + '\n' + options.text) });
    }

    const defaultOptions = {
        title: 'Are you sure?',
        text: '',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    };

    const mergedOptions = { ...defaultOptions, ...options };
    return Swal.fire(mergedOptions);
}

// Close any open SweetAlert
function closeSwal() {
    if (typeof Swal !== 'undefined') {
        Swal.close();
    }
}
</script>

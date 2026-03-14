// public/assets/pic/js/dashboard.js

document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle functionality
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarToggleTop = document.getElementById('sidebarToggleTop');
    const sidebar = document.getElementById('sidebar');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }

    if (sidebarToggleTop) {
        sidebarToggleTop.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 991.98) {
            if (!sidebar.contains(event.target) && !event.target.closest('.sidebar-toggle')) {
                sidebar.classList.remove('show');
            }
        }
    });

    // Auto dismiss alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Smooth scroll for quick actions
    const quickActionCards = document.querySelectorAll('.quick-action-card');
    quickActionCards.forEach(function(card) {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            });

       card.addEventListener('mouseleave', function() {
           this.style.transform = 'translateY(-2px)';
       });
   });

   // Initialize tooltips
   const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
   const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
       return new bootstrap.Tooltip(tooltipTriggerEl);
   });

   // Progress bar animations
   const progressBars = document.querySelectorAll('.progress-bar');
   progressBars.forEach(function(bar) {
       const width = bar.style.width;
       bar.style.width = '0%';
       setTimeout(function() {
           bar.style.width = width;
           bar.style.transition = 'width 1s ease-in-out';
       }, 500);
   });

   // Refresh data every 5 minutes
   setInterval(function() {
       // You can add AJAX call here to refresh dashboard data
       console.log('Dashboard data refresh interval');
   }, 300000);
});

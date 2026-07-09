window.showSuccessToast = function (message) {
    if (window.Swal) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: message,
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            customClass: {
                popup: 'shadow border border-white',
            },
        });
        return;
    }

    const tempAlert = document.createElement('div');
    tempAlert.className = 'alert alert-success border-0 shadow-sm d-flex align-items-center gap-2 mb-4';
    tempAlert.style.position = 'fixed';
    tempAlert.style.top = '1rem';
    tempAlert.style.right = '1rem';
    tempAlert.style.zIndex = '1050';
    tempAlert.innerHTML = `<i class="bi bi-check-circle-fill fs-5"></i><div>${message}</div>`;

    document.body.appendChild(tempAlert);
    setTimeout(() => tempAlert.remove(), 3000);
};

document.addEventListener('DOMContentLoaded', function () {
    const sidebarToggle = document.getElementById('sb-sidebar-toggle');
    const sidebar = document.getElementById('sb-sidebar');
    const content = document.getElementById('sb-content');

    if (sidebarToggle && sidebar && content) {
        sidebarToggle.addEventListener('click', function (e) {
            e.preventDefault();
            
            // Check if mobile or desktop
            if (window.innerWidth <= 768) {
                // On mobile, toggle class to slide in/out
                sidebar.classList.toggle('show-mobile');
            } else {
                // On desktop, toggle collapse
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('expanded');
            }
        });
    }

    // Close mobile sidebar when window is resized to desktop width
    window.addEventListener('resize', function () {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('show-mobile');
        }
    });
});

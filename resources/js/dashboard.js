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

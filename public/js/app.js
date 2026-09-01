document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('clinicSidebar');
    const backdrop = document.querySelector('.sidebar-backdrop');

    if (!sidebar || !backdrop) {
        return;
    }

    document.querySelectorAll('[data-sidebar-open]').forEach(function (button) {
        button.addEventListener('click', function () {
            sidebar.classList.add('open');
            backdrop.classList.add('show');
        });
    });

    document.querySelectorAll('[data-sidebar-close]').forEach(function (button) {
        button.addEventListener('click', function () {
            sidebar.classList.remove('open');
            backdrop.classList.remove('show');
        });
    });
});

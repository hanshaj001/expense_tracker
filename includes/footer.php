    <!-- Bootstrap 5 JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar Mobile Toggle Script -->
    <script>
        (function () {
            var sidebar      = document.getElementById('sidebar');
            var overlay      = document.getElementById('sidebarOverlay');
            var hamburgerBtn = document.getElementById('hamburgerBtn');
            var closeBtn     = document.getElementById('sidebarCloseBtn');

            function openSidebar() {
                if (sidebar) sidebar.classList.add('open');
                if (overlay) overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                if (sidebar) sidebar.classList.remove('open');
                if (overlay) overlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            if (hamburgerBtn) hamburgerBtn.addEventListener('click', openSidebar);
            if (closeBtn)     closeBtn.addEventListener('click', closeSidebar);
            if (overlay)      overlay.addEventListener('click', closeSidebar);

            // Auto-close sidebar on mobile when a link is clicked
            document.querySelectorAll('.menu .nav-link').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (window.innerWidth <= 768) closeSidebar();
                });
            });
        })();
    </script>

    <!-- Global App JS -->
    <script src="../assets/app.js"></script>

</body>
</html>

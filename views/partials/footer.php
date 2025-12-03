        </div>
    </main>

    <!-- Site Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <p>&copy; <?php echo date('Y'); ?> Mini CRM. All rights reserved.</p>
                <p class="text-muted text-sm">
                    Built with PHP <?php echo PHP_VERSION; ?> & MariaDB
                </p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        /**
         * Delete Confirmation
         */
        document.addEventListener('DOMContentLoaded', function() {
            // Delete confirmation
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('Naozaj chcete vymazať tohto zákazníka? Túto akciu nie je možné vrátiť späť.')) {
                        e.preventDefault();
                        return false;
                    }
                });
            });

            // Auto-hide flash messages after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                }, 5000);
            });

            // Search form submission on Enter
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        document.querySelector('.search-bar form').submit();
                    }
                });
            }
        });
    </script>
</body>
</html>

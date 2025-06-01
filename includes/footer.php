</div><!-- /.container -->

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="<?php echo isset($_SESSION['user_id']) ? '../' : ''; ?>assets/js/validation.js"></script>
<script src="<?php echo isset($_SESSION['user_id']) ? '../' : ''; ?>assets/js/maps.js"></script>
<script src="<?php echo isset($_SESSION['user_id']) ? '../' : ''; ?>assets/js/search.js"></script>
</body>
</html>
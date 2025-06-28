<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="/static/css/users/HeaderFooter.css">
    <link rel="stylesheet" href="/static/css/users/TrangChu.css">
    <?php echo $__env->yieldContent('page-css'); ?>

</head>
<body class="bg-dark">
    <?php echo $__env->make('layouts.users.Header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('content'); ?>
    <?php echo $__env->make('layouts.users.Footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('page-js'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tự động ẩn alert sau 3 giây
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    if (alert && alert.parentNode) {
                        // Sử dụng Bootstrap fade out
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 3000); // 3000ms = 3 giây
            });
        });
    </script>
</body>
</html><?php /**PATH C:\mysites\ct27501-project-BanhNhatKhang-1\app\Views/layouts/users/master.blade.php ENDPATH**/ ?>
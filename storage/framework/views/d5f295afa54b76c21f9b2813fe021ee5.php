<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Flourish Supermarket'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#059669',
                        secondary: '#7c3aed',
                    }
                }
            }
        }
    </script>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-gray-50">
    <?php echo $__env->yieldContent('content'); ?>
</body>
</html>
<?php /**PATH C:\wamp64\www\flourish\flourish\resources\views/layouts/master.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Login - Flourish Supermarket'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-emerald-600 to-teal-800">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Flourish</h1>
            <p class="text-gray-500">Supermarket POS</p>
        </div>
        
        <?php echo $__env->yieldContent('auth-content'); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\flourish\flourish\resources\views/layouts/auth.blade.php ENDPATH**/ ?>
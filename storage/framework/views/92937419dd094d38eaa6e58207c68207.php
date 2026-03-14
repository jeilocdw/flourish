<?php $__env->startSection('auth-content'); ?>
<form method="POST" action="<?php echo e(route('login')); ?>">
    <?php echo csrf_field(); ?>
    
    <?php if($errors->any()): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo e($errors->first()); ?>

        </div>
    <?php endif; ?>

    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
            Email Address
        </label>
        <input class="shadow appearance-none border rounded w-full py-3 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent" 
               id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus placeholder="Enter your email">
    </div>

    <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
            Password
        </label>
        <input class="shadow appearance-none border rounded w-full py-3 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent" 
               id="password" type="password" name="password" required placeholder="********">
    </div>

    <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline transition duration-200" type="submit">
        Sign In
    </button>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\flourish\flourish\resources\views/auth/login.blade.php ENDPATH**/ ?>
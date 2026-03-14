<?php $__env->startSection('header', 'Edit User'); ?>

<?php $__env->startSection('main'); ?>
<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="<?php echo e(route('users.update', $user)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" value="<?php echo e($user->name); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="<?php echo e($user->email); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Password (leave blank to keep current)</label>
            <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select name="role" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                <option value="admin" <?php echo e($user->role === 'admin' ? 'selected' : ''); ?>>Admin</option>
                <option value="manager" <?php echo e($user->role === 'manager' ? 'selected' : ''); ?>>Manager</option>
                <option value="cashier" <?php echo e($user->role === 'cashier' ? 'selected' : ''); ?>>Cashier</option>
            </select>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Store</label>
            <select name="store_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">Select Store</option>
                <?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($store->id); ?>" <?php echo e($user->store_id == $store->id ? 'selected' : ''); ?>><?php echo e($store->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        
        <div class="mb-4">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" <?php echo e($user->is_active ? 'checked' : ''); ?> class="rounded text-emerald-600">
                <span class="text-sm text-gray-700">Active</span>
            </label>
        </div>
        
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Update</button>
            <a href="<?php echo e(route('users.index')); ?>" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Cancel</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\flourish\flourish\resources\views/users/edit.blade.php ENDPATH**/ ?>
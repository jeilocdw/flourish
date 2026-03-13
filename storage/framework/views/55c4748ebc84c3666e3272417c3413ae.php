<?php $__env->startSection('header', 'Stores'); ?>

<?php $__env->startSection('main'); ?>
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b flex justify-between items-center">
        <form method="GET" class="flex gap-4">
            <input type="text" name="search" placeholder="Search stores..." class="px-4 py-2 border rounded-lg" value="<?php echo e(request('search')); ?>">
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Filter</button>
        </form>
        <a href="<?php echo e(route('stores.create')); ?>" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Add Store</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-4 py-3"><?php echo e($store->name); ?></td>
                    <td class="px-4 py-3"><?php echo e($store->code); ?></td>
                    <td class="px-4 py-3"><?php echo e($store->address ?? '-'); ?></td>
                    <td class="px-4 py-3"><?php echo e($store->phone ?? '-'); ?></td>
                    <td class="px-4 py-3">
                        <?php if($store->is_active): ?>
                            <span class="px-2 py-1 bg-green-100 text-green-600 rounded text-xs">Active</span>
                        <?php else: ?>
                            <span class="px-2 py-1 bg-red-100 text-red-600 rounded text-xs">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <a href="<?php echo e(route('stores.edit', $store)); ?>" class="text-blue-600 hover:underline mr-2">Edit</a>
                        <form method="POST" action="<?php echo e(route('stores.destroy', $store)); ?>" class="inline">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete this store?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">No stores found</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if(method_exists($stores, 'links')): ?>
    <div class="p-4 border-t">
        <?php echo e($stores->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\flourish\flourish\resources\views/stores/index.blade.php ENDPATH**/ ?>
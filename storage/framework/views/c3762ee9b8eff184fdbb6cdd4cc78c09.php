<?php $__env->startSection('header', 'Suppliers'); ?>

<?php $__env->startSection('main'); ?>
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b flex justify-between items-center">
        <form method="GET" class="flex gap-4">
            <input type="text" name="search" placeholder="Search suppliers..." class="px-4 py-2 border rounded-lg" value="<?php echo e(request('search')); ?>">
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Filter</button>
        </form>
        <a href="<?php echo e(route('suppliers.create')); ?>" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Add Supplier</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-4 py-3"><?php echo e($supplier->name); ?></td>
                    <td class="px-4 py-3"><?php echo e($supplier->email ?? '-'); ?></td>
                    <td class="px-4 py-3"><?php echo e($supplier->phone ?? '-'); ?></td>
                    <td class="px-4 py-3"><?php echo e($supplier->address ?? '-'); ?></td>
                    <td class="px-4 py-3">
                        <a href="<?php echo e(route('suppliers.edit', $supplier)); ?>" class="text-blue-600 hover:underline mr-2">Edit</a>
                        <form method="POST" action="<?php echo e(route('suppliers.destroy', $supplier)); ?>" class="inline">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete this supplier?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">No suppliers found</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if(method_exists($suppliers, 'links')): ?>
    <div class="p-4 border-t">
        <?php echo e($suppliers->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\flourish\flourish\resources\views/suppliers/index.blade.php ENDPATH**/ ?>
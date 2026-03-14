<?php $__env->startSection('header', 'Expenses'); ?>

<?php $__env->startSection('main'); ?>
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b flex justify-between items-center">
        <form method="GET" class="flex gap-4">
            <input type="text" name="search" placeholder="Search expenses..." class="px-4 py-2 border rounded-lg" value="<?php echo e(request('search')); ?>">
            <select name="category" class="px-4 py-2 border rounded-lg">
                <option value="">All Categories</option>
                <option value="utilities" <?php echo e(request('category') == 'utilities' ? 'selected' : ''); ?>>Utilities</option>
                <option value="rent" <?php echo e(request('category') == 'rent' ? 'selected' : ''); ?>>Rent</option>
                <option value="salaries" <?php echo e(request('category') == 'salaries' ? 'selected' : ''); ?>>Salaries</option>
                <option value="supplies" <?php echo e(request('category') == 'supplies' ? 'selected' : ''); ?>>Supplies</option>
                <option value="other" <?php echo e(request('category') == 'other' ? 'selected' : ''); ?>>Other</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Filter</button>
        </form>
        <a href="<?php echo e(route('expenses.create')); ?>" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Add Expense</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-4 py-3"><?php echo e($expense->created_at->format('Y-m-d')); ?></td>
                    <td class="px-4 py-3"><?php echo e($expense->description); ?></td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs capitalize"><?php echo e($expense->category); ?></span>
                    </td>
                    <td class="px-4 py-3"><?php echo e(format_currency($expense->amount)); ?></td>
                    <td class="px-4 py-3">
                        <a href="<?php echo e(route('expenses.edit', $expense)); ?>" class="text-blue-600 hover:underline mr-2">Edit</a>
                        <form method="POST" action="<?php echo e(route('expenses.destroy', $expense)); ?>" class="inline">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete this expense?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">No expenses found</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if(method_exists($expenses, 'links')): ?>
    <div class="p-4 border-t">
        <?php echo e($expenses->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\flourish\flourish\resources\views/expenses/index.blade.php ENDPATH**/ ?>
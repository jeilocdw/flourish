<?php $__env->startSection('header', 'Add Unit'); ?>

<?php $__env->startSection('main'); ?>
<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="<?php echo e(route('units.store')); ?>">
        <?php echo csrf_field(); ?>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="e.g., Kilogram" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Short Name</label>
            <input type="text" name="short_name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="e.g., kg" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Base Unit (optional)</label>
            <select name="base_unit" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">None</option>
                <?php $__currentLoopData = $baseUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($unit->id); ?>"><?php echo e($unit->name); ?> (<?php echo e($unit->short_name); ?>)</option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Operator</label>
            <select name="operator" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">None</option>
                <option value="*">Multiply (*)</option>
                <option value="/">Divide (/)</option>
            </select>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Operator Value</label>
            <input type="number" name="operator_value" step="any" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="e.g., 1000">
        </div>
        
        <div class="mb-4">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" checked class="rounded text-emerald-600">
                <span class="text-sm text-gray-700">Active</span>
            </label>
        </div>
        
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Create</button>
            <a href="<?php echo e(route('units.index')); ?>" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Cancel</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\flourish\flourish\resources\views/units/create.blade.php ENDPATH**/ ?>
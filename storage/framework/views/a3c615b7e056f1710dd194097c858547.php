<?php $__env->startSection('header', 'Edit Product'); ?>

<?php $__env->startSection('main'); ?>
<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="<?php echo e(route('products.update', $product)); ?>" class="space-y-4" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                <input type="text" name="name" value="<?php echo e($product->name); ?>" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">SKU *</label>
                <input type="text" name="sku" value="<?php echo e($product->sku); ?>" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Barcode</label>
                <input type="text" name="barcode" value="<?php echo e($product->barcode); ?>" class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                <select name="category_id" class="w-full px-4 py-2 border rounded-lg" required>
                    <option value="">Select Category</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id); ?>" <?php echo e($product->category_id == $category->id ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                <select name="brand_id" class="w-full px-4 py-2 border rounded-lg">
                    <option value="">Select Brand</option>
                    <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($brand->id); ?>" <?php echo e($product->brand_id == $brand->id ? 'selected' : ''); ?>><?php echo e($brand->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unit *</label>
                <select name="unit_id" class="w-full px-4 py-2 border rounded-lg" required>
                    <option value="">Select Unit</option>
                    <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($unit->id); ?>" <?php echo e($product->unit_id == $unit->id ? 'selected' : ''); ?>><?php echo e($unit->name); ?> (<?php echo e($unit->short_name); ?>)</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cost Price *</label>
                <input type="number" name="cost_price" value="<?php echo e($product->cost_price); ?>" step="0.01" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sell Price *</label>
                <input type="number" name="sell_price" value="<?php echo e($product->sell_price); ?>" step="0.01" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tax Rate (%)</label>
                <input type="number" name="tax_rate" value="<?php echo e($product->tax_rate); ?>" step="0.01" class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
                <input type="number" name="stock_quantity" value="<?php echo e($product->productStore->first()?->quantity ?? 0); ?>" class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alert Quantity</label>
                <input type="number" name="alert_quantity" value="<?php echo e($product->productStore->first()?->alert_quantity ?? $product->alert_quantity); ?>" class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                <input type="date" name="expiry_date" value="<?php echo e($product->expiry_date); ?>" class="w-full px-4 py-2 border rounded-lg">
            </div>
        </div>
        
        <div class="flex gap-4">
            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Update Product</button>
            <a href="<?php echo e(route('products.index')); ?>" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\flourish\flourish\resources\views/products/edit.blade.php ENDPATH**/ ?>
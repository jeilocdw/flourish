<?php $__env->startSection('header', 'Products'); ?>

<?php $__env->startSection('main'); ?>
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b">
        <form method="GET" class="flex flex-wrap gap-4 mb-4">
            <input type="text" name="search" placeholder="Search products..." class="px-4 py-2 border rounded-lg" value="<?php echo e(request('search')); ?>">
            <select name="category_id" class="px-4 py-2 border rounded-lg">
                <option value="">All Categories</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($category->id); ?>" <?php echo e(request('category_id') == $category->id ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Filter</button>
        </form>
        
        <div class="flex flex-wrap gap-2">
            <a href="<?php echo e(route('products.create')); ?>" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Add Product</a>
            <a href="<?php echo e(route('products.template')); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Download Template</a>
            <a href="<?php echo e(route('products.export')); ?>" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Export CSV</a>
            <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">Import CSV</button>
        </div>
    </div>
    
    <!-- Import Modal -->
    <div id="importModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold mb-4">Import Products</h3>
            <form method="POST" action="<?php echo e(route('products.import')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select CSV File</label>
                    <input type="file" name="file" accept=".csv,.txt" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Import</button>
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-4 py-3"><?php echo e($product->name); ?></td>
                    <td class="px-4 py-3"><?php echo e($product->sku); ?></td>
                    <td class="px-4 py-3"><?php echo e($product->category?->name); ?></td>
                    <td class="px-4 py-3"><?php echo e(format_currency($product->cost_price)); ?></td>
                    <td class="px-4 py-3"><?php echo e(format_currency($product->sell_price)); ?></td>
                    <td class="px-4 py-3">
                        <?php $stock = $product->productStore->sum('quantity') ?>
                        <span class="<?php echo e($stock < 10 ? 'text-red-600' : ''); ?>"><?php echo e($stock); ?></span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="<?php echo e(route('products.edit', $product)); ?>" class="text-blue-600 hover:underline mr-2">Edit</a>
                        <form method="POST" action="<?php echo e(route('products.destroy', $product)); ?>" class="inline">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete this product?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">No products found</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t">
        <?php echo e($products->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\flourish\flourish\resources\views/products/index.blade.php ENDPATH**/ ?>
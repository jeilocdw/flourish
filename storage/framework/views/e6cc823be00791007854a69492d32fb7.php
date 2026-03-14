<?php $__env->startSection('header', 'Add Purchase'); ?>

<?php $__env->startSection('main'); ?>
<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="<?php echo e(route('purchases.store')); ?>" id="purchase-form">
        <?php echo csrf_field(); ?>
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                <select name="supplier_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Select Supplier</option>
                    <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($supplier->id); ?>"><?php echo e($supplier->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="pending">Pending</option>
                    <option value="received">Received</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Products</label>
            <div id="purchase-items">
                <div class="flex gap-2 mb-2">
                    <select class="product-select flex-1 px-4 py-2 border rounded-lg">
                        <option value="">Select Product</option>
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($product->id); ?>" data-price="<?php echo e($product->cost_price); ?>"><?php echo e($product->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <input type="number" placeholder="Qty" class="quantity w-24 px-4 py-2 border rounded-lg" min="1" value="1">
                    <input type="number" placeholder="Cost" class="unit-cost w-32 px-4 py-2 border rounded-lg" step="0.01">
                    <button type="button" class="add-item px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Add</button>
                </div>
            </div>
            
            <table class="w-full mt-4">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Product</th>
                        <th class="px-4 py-2 text-right">Qty</th>
                        <th class="px-4 py-2 text-right">Unit Cost</th>
                        <th class="px-4 py-2 text-right">Total</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody id="items-list"></tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-4 py-2 text-right font-bold">Total</td>
                        <td class="px-4 py-2 text-right font-bold" id="total-amount">$0.00</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <input type="hidden" name="items" id="items-data">
        
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Create Purchase</button>
            <a href="<?php echo e(route('purchases.index')); ?>" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Cancel</a>
        </div>
    </form>
</div>

<script>
let items = [];

document.querySelector('.add-item').addEventListener('click', function() {
    const select = document.querySelector('.product-select');
    const quantity = document.querySelector('.quantity');
    const unitCost = document.querySelector('.unit-cost');
    
    const productId = select.value;
    const productName = select.options[select.selectedIndex].text;
    const qty = parseInt(quantity.value) || 1;
    const cost = parseFloat(unitCost.value) || 0;
    
    if (!productId || cost <= 0) {
        alert('Please select a product and enter cost');
        return;
    }
    
    items.push({
        product_id: productId,
        name: productName,
        quantity: qty,
        unit_cost: cost,
        total: qty * cost
    });
    
    renderItems();
    
    select.value = '';
    quantity.value = 1;
    unitCost.value = '';
});

function renderItems() {
    const tbody = document.getElementById('items-list');
    tbody.innerHTML = items.map((item, index) => `
        <tr>
            <td class="px-4 py-2">${item.name}</td>
            <td class="px-4 py-2 text-right">${item.quantity}</td>
            <td class="px-4 py-2 text-right">$${item.unit_cost.toFixed(2)}</td>
            <td class="px-4 py-2 text-right">$${item.total.toFixed(2)}</td>
            <td class="px-4 py-2 text-right">
                <button type="button" onclick="removeItem(${index})" class="text-red-600 hover:underline">Remove</button>
            </td>
        </tr>
    `).join('');
    
    const total = items.reduce((sum, item) => sum + item.total, 0);
    document.getElementById('total-amount').textContent = '$' + total.toFixed(2);
    document.getElementById('items-data').value = JSON.stringify(items);
}

function removeItem(index) {
    items.splice(index, 1);
    renderItems();
}

document.querySelector('.product-select').addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    const price = option.dataset.price || '';
    document.querySelector('.unit-cost').value = price;
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\flourish\flourish\resources\views/purchases/create.blade.php ENDPATH**/ ?>
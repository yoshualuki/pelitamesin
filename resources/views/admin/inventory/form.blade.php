@props(['inventory' => null, 'products'])

<div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
    <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-800">
        <h2 class="text-2xl font-semibold text-white">
            {{ $inventory ? 'Edit Inventory Stock' : 'Add New Inventory Stock' }}
        </h2>
        <p class="mt-1 text-blue-100 text-sm">
            {{ $inventory ? 'Update existing inventory item details' : 'Add new stock to your inventory' }}
        </p>
    </div>
    
    <form method="POST" action="{{ $inventory ? route('inventory.update', $inventory->id) : route('admin.inventory.store') }}" class="p-6">
        @csrf
        @if($inventory) @method('PUT') @endif
        
        <div class="grid grid-cols-1 gap-6">
            <!-- Product Selection -->
            <div class="relative">
                <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">Product <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select id="product_id" name="product_id" required
                            class="appearance-none block w-full px-4 py-2.5 text-base border border-gray-300 rounded-lg shadow-sm 
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                   transition duration-150 ease-in-out">
                        <option value="">Select a product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" 
                                    data-stock="{{ $product->current_stock }}"
                                    data-price="{{ $product->price }}"
                                    {{ old('product_id', $inventory->product_id ?? '') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} (SKU: {{ $product->sku }})
                                @if($product->current_stock)
                                    - Current Stock: {{ $product->current_stock }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                @error('product_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Quantity Input -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-red-500">*</span></label>
                    <div class="relative rounded-md shadow-sm">
                        <input type="number" id="quantity" name="quantity" min="1" required
                               value="{{ old('quantity', $inventory->quantity ?? '1') }}" 
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm 
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                      transition duration-150 ease-in-out">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">units</span>
                        </div>
                    </div>
                    @error('quantity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Unit Cost Input -->
                <div>
                    <label for="unit_cost" class="block text-sm font-medium text-gray-700 mb-1">Unit Cost <span class="text-red-500">*</span></label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" step="0.01" min="0.01" id="unit_cost" name="unit_cost" required
                               value="{{ old('unit_cost', $inventory->unit_cost ?? '') }}" 
                               class="block w-full pl-7 pr-12 py-2.5 border border-gray-300 rounded-lg shadow-sm 
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                      transition duration-150 ease-in-out">
                    </div>
                    @error('unit_cost')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Total Cost Calculation -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estimated Total Cost</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="text" id="total_cost" readonly
                               class="block w-full pl-7 pr-12 py-2.5 bg-gray-100 border border-gray-300 rounded-lg 
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>
            
            <!-- Additional Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Supplier Information -->
                <div>
                    <label for="supplier" class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                    <input type="text" id="supplier" name="supplier" 
                           value="{{ old('supplier', $inventory->supplier ?? '') }}" 
                           class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm 
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                  transition duration-150 ease-in-out">
                    @error('supplier')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Batch/Lot Number -->
                <div>
                    <label for="batch_number" class="block text-sm font-medium text-gray-700 mb-1">Batch/Lot Number</label>
                    <input type="text" id="batch_number" name="batch_number" 
                           value="{{ old('batch_number', $inventory->batch_number ?? '') }}" 
                           class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm 
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                  transition duration-150 ease-in-out">
                    @error('batch_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Notes Field -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea id="notes" name="notes" rows="3"
                          class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm 
                                 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                 transition duration-150 ease-in-out">{{ old('notes', $inventory->notes ?? '') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end space-x-3">
            <a href="{{ route('admin.inventory') }}" 
               class="inline-flex items-center px-4 py-2.5 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                {{ $inventory ? 'Update Inventory' : 'Add to Inventory' }}
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('product_id');
        const quantityInput = document.getElementById('quantity');
        const unitCostInput = document.getElementById('unit_cost');
        const totalCostInput = document.getElementById('total_cost');
        
        // Calculate total cost when quantity or unit cost changes
        function calculateTotal() {
            const quantity = parseFloat(quantityInput.value) || 0;
            const unitCost = parseFloat(unitCostInput.value) || 0;
            const total = quantity * unitCost;
            totalCostInput.value = total.toFixed(2);
        }
        
        // Auto-fill unit cost when product is selected
        productSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption && selectedOption.dataset.price) {
                unitCostInput.value = selectedOption.dataset.price;
                calculateTotal();
            }
        });
        
        quantityInput.addEventListener('input', calculateTotal);
        unitCostInput.addEventListener('input', calculateTotal);
        
        // Initialize calculation on page load
        calculateTotal();
    });
</script>
@endpush
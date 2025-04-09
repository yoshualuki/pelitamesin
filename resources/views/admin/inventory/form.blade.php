@props(['inventory' => null, 'products'])

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-2xl font-semibold text-gray-800">
            {{ $inventory ? 'Edit Inventory Item' : 'Add New Inventory Item' }}
        </h2>
    </div>
    
    <form method="POST" action="{{ $inventory ? route('inventory.update', $inventory->id) : route('admin.inventory.store') }}" class="p-6">
        @csrf
        @if($inventory) @method('PUT') @endif
        
        <div class="grid grid-cols-1 gap-6">
            <div>
                <label for="product_id" class="block text-sm font-medium text-gray-700">Product</label>
                <select id="product_id" name="product_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">Select a product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id', $inventory->product_id ?? '') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} ({{ $product->sku }})
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $inventory->quantity ?? '') }}" 
                           class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('quantity')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="unit_cost" class="block text-sm font-medium text-gray-700">Unit Cost ($)</label>
                    <input type="number" step="0.01" id="unit_cost" name="unit_cost" value="{{ old('unit_cost', $inventory->unit_cost ?? '') }}" 
                           class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('unit_cost')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end">
            <a href="{{ route('admin.inventory') }}" class="btn-secondary mr-3">
                Cancel
            </a>
            <button type="submit" class="btn-primary">
                {{ $inventory ? 'Update' : 'Save' }} Inventory
            </button>
        </div>
    </form>
</div>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Order') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('store.orders.store') }}" x-data="orderForm()">
                        @csrf

                        <!-- Supplier Selection -->
                        <div>
                            <x-input-label for="supplier_id" :value="__('Supplier')" />
                            <select id="supplier_id" name="supplier_id" x-model="selectedSupplierId" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ __('Select Supplier') }}</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('supplier_id')" class="mt-2" />
                        </div>

                        <!-- Order Items -->
                        <div class="mt-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold">{{ __('Order Items') }}</h3>
                                <button type="button" @click="addItem" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Add Item') }}
                                </button>
                            </div>

                            <template x-for="(item, index) in items" :key="index">
                                <div class="grid grid-cols-12 gap-4 mb-4">
                                    <!-- Product Selection -->
                                    <div class="col-span-6">
                                        <x-input-label :value="__('Product')" />
                                        <select :name="'items[' + index + '][product_id]'" x-model="item.product_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                            <option value="">{{ __('Select Product') }}</option>
                                            <template x-for="product in availableProducts" :key="product.id">
                                                <option :value="product.id" x-text="product.name"></option>
                                            </template>
                                        </select>
                                        <template x-if="errors['items.' + index + '.product_id']">
                                            <div class="mt-2 text-sm text-red-600" x-text="errors['items.' + index + '.product_id']"></div>
                                        </template>
                                    </div>

                                    <!-- Quantity -->
                                    <div class="col-span-4">
                                        <x-input-label :value="__('Quantity')" />
                                        <x-text-input type="number" :name="'items[' + index + '][quantity]'" x-model="item.quantity" class="mt-1 block w-full" min="1" />
                                        <template x-if="errors['items.' + index + '.quantity']">
                                            <div class="mt-2 text-sm text-red-600" x-text="errors['items.' + index + '.quantity']"></div>
                                        </template>
                                    </div>

                                    <!-- Remove Button -->
                                    <div class="col-span-2 flex items-end">
                                        <button type="button" @click="removeItem(index)" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            {{ __('Remove') }}
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <x-input-error :messages="$errors->get('items')" class="mt-2" />
                        </div>

                        <div class="mt-6 flex justify-end">
                            <x-primary-button>
                                {{ __('Create Order') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function orderForm() {
            return {
                selectedSupplierId: '{{ old('supplier_id') }}',
                items: [],
                errors: @json($errors->toArray()),
                availableProducts: [],

                init() {
                    @if(old('items'))
                        this.items = @json(old('items'));
                    @else
                        this.addItem();
                    @endif

                    this.$watch('selectedSupplierId', (value) => {
                        if (value) {
                            const supplier = @json($suppliers->keyBy('id'));
                            this.availableProducts = supplier[value].products.map(product => ({
                                id: product.id,
                                name: '{{ app()->getLocale() }}' === 'ar' ? product.name_ar : product.name_en
                            }));
                        } else {
                            this.availableProducts = [];
                        }
                    });
                },

                addItem() {
                    this.items.push({
                        product_id: '',
                        quantity: 1
                    });
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                    if (this.items.length === 0) {
                        this.addItem();
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>

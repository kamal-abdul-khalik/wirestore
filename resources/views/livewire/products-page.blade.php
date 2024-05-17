<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <section class="py-10 rounded-lg font-poppins dark:bg-zinc-800">
        <div class="px-4 py-4 mx-auto bg-white rounded-lg shadow-lg lg:py-6 md:px-6">
            <div class="flex flex-wrap mb-24 -mx-3">
                <div class="w-full pr-2 lg:w-1/4 lg:block">
                    <div class="p-4 mb-5 border rounded-lg border-zinc-200 dark:border-zinc-900 dark:bg-zinc-900">
                        <h2 class="text-2xl font-bold dark:text-zinc-400">Categories</h2>
                        <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-zinc-400"></div>
                        <ul>
                            @foreach ($categories as $category)
                                <li class="mb-4" wire:key='{{ $category->id }}'>
                                    <label for="category-{{ $category->id }}"
                                        class="flex items-center dark:text-zinc-400 ">
                                        <input type="checkbox" wire:model.live='selectedCategories'
                                            value="{{ $category->id }}" id="category-{{ $category->id }}"
                                            class="w-4 h-4 mr-2">
                                        <span class="text-lg">{{ $category->name }}</span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="p-4 mb-5 bg-white border border-zinc-200 dark:bg-zinc-900 dark:border-zinc-900">
                        <h2 class="text-2xl font-bold dark:text-zinc-400">Brand</h2>
                        <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-zinc-400"></div>
                        <ul>
                            @foreach ($brands as $brand)
                                <li class="mb-4" wire:key='{{ $brand->id }}'>
                                    <label for="brand-{{ $brand->id }}" class="flex items-center dark:text-zinc-300">
                                        <input type="checkbox" wire:model.live='selectedBrands'
                                            value="{{ $brand->id }}" id="brand-{{ $brand->id }}"
                                            class="w-4 h-4 mr-2">
                                        <span class="text-lg dark:text-zinc-400">{{ $brand->name }}</span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="p-4 mb-5 bg-white border border-zinc-200 dark:bg-zinc-900 dark:border-zinc-900">
                        <h2 class="text-2xl font-bold dark:text-zinc-400">Product Status</h2>
                        <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-zinc-400"></div>
                        <ul>
                            <li class="mb-4">
                                <label for="featured" class="flex items-center dark:text-zinc-300">
                                    <input type="checkbox" id="featured" wire:model.live='featured'
                                        class="w-4 h-4 mr-2">
                                    <span class="text-lg dark:text-zinc-400">Featered</span>
                                </label>
                            </li>
                            <li class="mb-4">
                                <label for="onSale" class="flex items-center dark:text-zinc-300">
                                    <input type="checkbox" id="onSale" wire:model.li ve='onSale'
                                        class="w-4 h-4 mr-2">
                                    <span class="text-lg dark:text-zinc-400">On Sale</span>
                                </label>
                            </li>
                        </ul>
                    </div>

                    <div class="p-4 mb-5 bg-white border border-zinc-200 dark:bg-zinc-900 dark:border-zinc-900">
                        <h2 class="text-2xl font-bold dark:text-zinc-400">Price</h2>
                        <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-zinc-400"></div>
                        <div>
                            <div class="text-lg font-bold text-blue-400">
                                {{ Number::currency($priceRange, 'IDR', 'id') }}</div>
                            <input type="range"
                                class="w-full h-1 mb-4 bg-blue-100 rounded appearance-none cursor-pointer"
                                max="30000000" value="100000" step="100000" wire:model.live='priceRange'>
                            <div class="flex justify-between ">
                                <span
                                    class="inline-block text-sm font-bold text-blue-400 ">{{ Number::currency(500000, 'IDR', 'id') }}</span>
                                <span
                                    class="inline-block text-sm font-bold text-blue-400 ">{{ Number::currency(30000000, 'IDR', 'id') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full px-3 lg:w-3/4">
                    <div class="px-3 mb-4">
                        <div
                            class="items-center justify-between hidden px-3 py-2 bg-zinc-100 md:flex dark:bg-zinc-900 ">
                            <div class="flex items-center justify-between">
                                <select wire:model.live='sort'
                                    class="block w-40 text-base cursor-pointer bg-zinc-100 dark:text-zinc-400 dark:bg-zinc-900">
                                    <option value="latest">Sort by latest</option>
                                    <option value="price">Sort by Price</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center ">
                        @forelse ($products as $product)
                            <div class="w-full px-3 mb-6 sm:w-1/2 md:w-1/3" wire:key='{{ $product->name }}'>
                                <div class="border rounded-lg">
                                    <div class="relative bg-zinc-200">
                                        <a href="/products/{{ $product->slug }}" class="" wire:navigate>
                                            <img src="{{ url('storage', $product->images[0]) }}"
                                                alt="{{ $product->name }}"
                                                class="object-cover w-full h-56 mx-auto rounded-lg">
                                        </a>
                                    </div>
                                    <div class="p-3 ">
                                        <div class="flex items-center justify-between gap-2 mb-2">
                                            <h3 class="text-xl font-medium dark:text-zinc-400">
                                                {{ $product->name }}
                                            </h3>
                                        </div>
                                        <p class="text-lg ">
                                            <span class="text-green-600 dark:text-green-600">
                                                {{ Number::currency($product->price, in: 'IDR', locale: 'id') }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="flex justify-center p-4 border-t border-zinc-300 dark:border-zinc-700">
                                        <a wire:click.prevent='addToCart({{ $product->id }})' href="#"
                                            class="flex items-center space-x-2 text-zinc-500 dark:text-zinc-400 hover:text-red-500 dark:hover:text-red-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="w-4 h-4 bi bi-cart3 " viewBox="0 0 16 16">
                                                <path
                                                    d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z">
                                                </path>
                                            </svg>
                                            <span wire:loading.remove wire:target='addToCart({{ $product->id }})'>
                                                Add to Cart
                                            </span>
                                            <span wire:loading wire:target='addToCart({{ $product->id }})'>
                                                Adding....
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex items-center justify-center w-full py-16 mx-4 rounded-lg bg-zinc-100">
                                <div class="text-2xl text-blue-500">No product here</div>
                            </div>
                        @endforelse
                    </div>
                    <!-- pagination start -->
                    <div class="flex justify-center mt-6">
                        {{ $products->links(data: ['scrollTo' => false]) }}
                    </div>
                    <!-- pagination end -->
                </div>
            </div>
        </div>
    </section>

</div>

@forelse ($foods as $food)
    <div class="col-sm-6 col-md-4">
        <a href="{{ route('pos.menu.details', $food->ingredient_id) }}" class="text-decoration-none" id="menu-details">
            <div class="card product-card">
                <div class="card-body">
                    <!-- <div class="product-image">
                        <img src="{{ uploaded_file($food->image) }}" class="img-fluid w-100" alt="{{ $food->name }}" />
                    </div> -->

                    <div class="product-details">
                        <div class="">
                            <h6 class="name">{{ $food->ingredient->name}}</h6>
                            <!-- <p class="text-primary-800 font-bold mb-0">{{ convert_amount($food->ingredient->purchase_price) }}</p> -->
                            <p class="text-primary-800 font-bold mb-0">Stock: {{ $food->qty_amount }}</p>
                        </div>
                        <!-- <p class="description">{{ $food->calorie }} kcal | v</p> -->
                    </div>

                    <!-- {{-- <div class="allergy">
                        @foreach ($food->allergies as $allergy)
                            <img src="{{ uploaded_file($allergy->image) }}" alt="{{ $allergy->name }}" />
                        @endforeach
                    </div> --}} -->
                </div>
            </div>
        </a>
    </div>
@empty
    <div class="col-sm-6 mx-auto">
        <div class="card product-card">
            <div class="card-body text-center">
                <p class="text-danger mb-0">Product not found!!</p>
            </div>
        </div>
    </div>
@endforelse


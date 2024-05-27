@props(['carousel' => 'order-carousel'])

<section {{ $attributes->merge(['class' => 'section order-section']) }}>

    <div class="orders-title">
        Orders  
    </div>

    <div class="orders-container ps" id="orders-container">
        <div id="{{ $carousel }}" class="orders"></div>
    </div>

    

    {{-- <div class="order-processing-section">
        <a href="#" class="btn btn-primary show-order-details" id="showBtn">
            <i class="fa-solid fa-store"></i>
            <span class="ms-1">Order Details</span>
        </a>
        <a href="#" class="btn btn-primary" id="editOrder">
            <i class="fa-solid fa-edit"></i>
            <span class="ms-1">Edit Order</span>
        </a>
        <a href="#" class="btn btn-primary" id="cancelOrder">
            <i class="fa-solid fa-xmark"></i>
            <span class="ms-1">Cancel Order</span>
        </a>
    </div> --}}
</section>
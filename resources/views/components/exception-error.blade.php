@props(['code', 'message'])

<div class="col-12 col-md-9 col-lg-8 mx-auto">
    <div class="d-flex justify-content-center align-items-center" style="height:80vh">
        <div class="text-center">
            <h1 class="fs-1 fw-bold" style="letter-spacing: 15px;">{{ $code }}</h3>
            <p>{{ $message }}</p>
        </div>
    </div>
    
</div>
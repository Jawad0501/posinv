
<x-form-modal title="Table Information" size="xl">
    <div class="row">
        @forelse ($tables as $table)
            <div class="col-sm-6 col-md-4 col-xl-3 mb-4">
                <div class="border rounded-10 text-center">
                    <div>
                        <img class="img-fluid" src="{{ uploaded_file($table->image) }}" alt="{{ $table->name }}" style="height: 150px">
                    </div>
                    <div class="">
                        <p class="fs-22 fw-bolder mb-0">{{ $table->name }}</p>
                        <p class="px-1 font-secondary fs-12 mb-0">Sit Capacity: {{ $table->capacity }}</p>
                        <p class="font-secondary fs-12">Available: {{ $table->available }}</p>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="mb-0 text-danger">Table not available!!</p>
            </div>
        @endforelse
    </div>
</x-form-modal>



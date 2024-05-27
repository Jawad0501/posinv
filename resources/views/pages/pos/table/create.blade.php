<x-form-modal title="Table Reservation" size="xl" action="{{ route('pos.table.store') }}" id="form">
    <div class="row">
        @foreach ($tables as $key => $table)
            <div class="col-md-4 col-sm-6 col-lg-4 mb-3">
                <div class="rounded-10 text-center p-3" id="table_{{ $table->id }}" onclick="getTableId({{ $table->id }})" style="border: 1px solid {{ $table->available >= $table->capacity ? '#00DD2E':'#FF2B18' }}; cursor:pointer">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="">
                            <p class="fs-22 fw-bolder mb-0">{{ $table->name }}</p>

                            <div class="d-flex font-secondary fs-12">
                                <p class="mb-0">Sit Capacity: {{ $table->capacity }}</p>
                                {{-- <p class="mb-0 ms-3">Available: {{ $table->available }}</p> --}}
                            </div>
                        </div>
                        <div>
                            <img src="{{ uploaded_file($table->image) }}" alt="{{ $table->name }}"
                                style="height: 100px">
                        </div>
                    </div>

                    {{-- @php($max = $table->available) --}}
                    {{-- <input type="number" class="form-control mt-3 table__person" name="tables[{{ $key }}][person]" min="1"
                        placeholder="Enter person"
                        @if (session()->has('tables'))
                            @foreach (session()->get('tables') as $sessionTable)
                                @if ($sessionTable['table_id'] == $table->id)
                                    value="{{ $sessionTable['person'] }}"
                                    @if (session()->has('cart_update'))
                                        @php($max = $table->available + $sessionTable['person'])
                                    @endif
                                @endif
                            @endforeach
                        @endif
                        max="{{ $max }}"
                    />
                    <small class="form-text text-danger"></small> --}}
                </div>
            </div>
        @endforeach
        {{-- <x-slot:extra>
            <button type="button" id="process_without_table" class="btn btn-primary px-3 text-white">Process without Table</button>
        </x-slot> --}}

    </div>


    <script>
        function getTableId(id){
            var url = route('pos.table.seeAvailability', id);
            $.ajax({
                type: 'GET',
                url: url,
                success: function (response) {
                    if(response.table_available == true){
                        $('#table_id').val(id);
                        toastr.clear();
                        toastr.success(`Table Number ${response.table_number} Selected`);
                        $('#modal').modal('hide');
                    }
                }
            });
        }
    </script>
</x-form-modal>

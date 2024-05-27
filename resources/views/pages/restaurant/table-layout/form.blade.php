<x-form-modal
    :title="isset($tableLayout) ? 'Update table':'Add new table'"
    action="{{ isset($tableLayout) ? route('table-layout.update', $tableLayout->id) : route('table-layout.store')}}"
    :button="isset($tableLayout) ? 'Update':'Submit'"
    id="fileForm"
>
    @isset($tableLayout)
        @method('PUT')
    @endisset

    <x-form-group
        name="number"
        placeholder="Enter table number..."
        :value="$tableLayout->number ?? ''"
    />

    <x-form-group
        name="name"
        placeholder="Enter table name..."
        :value="$tableLayout->name ?? ''"
    />

    <x-form-group
        name="capacity"
        type="number"
        placeholder="Enter table capacity..."
        :value="$tableLayout->capacity ?? ''"

    />

    {{-- <x-form-group name="categories" isType="select" class="select2" :required="false" onchange="showFoodField(event)">
        <option value="">Select Category</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </x-form-group>

    <div id="foods_field" class="d-none">
        <x-form-group name="foods" isType="select" class="select2" :required="false">
        </x-form-group>
    </div> --}}


    <x-form-group name="image" type="file" :required="false" />

</x-form-modal>

<script>
    function showFoodField(e){

        let category_id = e.target.value;
        let url = "{{route('get-category-food', 'id')}}"
        url = url.replace('id', `${category_id}`);

        if(e.target.value != 0){
            $.get(url, function(data, status){
                if(status == 'success'){
                    let element = document.getElementById('foods_field');

                    if(element.classList.contains('d-none')){
                        element.classList.remove('d-none');
                    }

                    let select_element = document.getElementById('foods');
                    let options = "<option value=''>Select Food</option>"
                    for(let i=0; i<data.foods.length; i++){
                        options = options + `<option value=${data.foods[i].id}>${data.foods[i].name}</option>`
                    }

                    select_element.innerHTML = options;
                }
            })
        }
        else{
            let element = document.getElementById('foods_field');

            if(element.classList.contains('d-none')){
            }
            else{
                element.classList.add('d-none')
            }
        }
    }
</script>

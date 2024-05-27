<x-form-modal 
    title="Upload Ingredient" 
    action="{{ route('ingredient.upload')}}"
    button="Upload"
    id="fileForm"
>
    <x-form-group
        name="upload file"
        for="file"
        :isInput="false"
    >
        <input type="file" name="file" id="file" class="form-control" />
    </x-form-group>

    <x-card-heading-button :url="setting('ingredient_upload_file_sample')" title="Download Sample" :download="true" icon="download" class="dark-bg" />
</x-form-modal>
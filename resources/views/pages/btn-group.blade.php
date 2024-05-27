@foreach ($data as $btn)
    @isset($btn['can'])
        @can($btn['can'])
            <x-table-button :url="$btn['url']" :type="$btn['type']" :id="$btn['id'] ?? true" />
        @endcan 
    @else 
        <x-table-button :url="$btn['url']" :type="$btn['type']" :id="$btn['id'] ?? true" />
    @endisset
@endforeach
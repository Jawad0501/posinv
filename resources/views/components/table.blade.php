@props(['items'])

<div class="table-responsive">
    <table {{ $attributes->merge(['class' => 'table table-bordered table-striped table-hover', 'id' => 'table']) }}>
        <thead>
            <tr>
                @foreach ($items as $item)
                    <th scope="col">{{ $item }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
        @isset($tfoot)
            <tfoot>
                <tr>
                    {{ $tfoot }}
                </tr>
            </tfoot>
        @endisset

    </table>
</div>

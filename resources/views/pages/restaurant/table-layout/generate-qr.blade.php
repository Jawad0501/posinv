<x-form-modal :title="'Generate QR Code'" action="" :button="'Download'" id="generateQrForm" :button_type="'button'" :on_click="'downloadQr()'">

    <x-form-group name="table_number" placeholder="Enter table number..." :value="$tableLayout->number" />

    <x-form-group name="categories" isType="select" class="select2" :required="false">
        <option value="">Select Category</option>
        @foreach ($categories as $category)
            <option value="{{ $category->slug }}">{{ $category->name }}</option>
        @endforeach
    </x-form-group>

    <x-form-group name="type" isType="select" class="">
        <option value="png" selected>PNG (default)</option>
        <option value="svg">SVG</option>
    </x-form-group>

    <div id="qr_div_{{ $tableLayout->number }}" class="d-flex justify-content-center mt-4">
        {{-- {!! QrCode::size(100)->generate(Request::url()) !!} --}}
    </div>

    <a id="qrDownload_{{ $tableLayout->number }}" class="d-none" href="" download="">Download</a>


</x-form-modal>


<script src='https://cdnjs.cloudflare.com/ajax/libs/html-to-image/1.9.0/html-to-image.js'></script>
<script>
    $('#generateQrForm').ready(
        function() {
            let number = '{!! $tableLayout->number !!}'
            let qr_div = $(`#qr_div_${number}`);
            let table_number = $('#table_number').val();
            let category = 0;
            let type = $('#type').val();
            let url = route('menu', {
                _query: {
                    table_number,
                    category_no: category
                }
            })
            $(qr_div).qrcode({
                width: 200,
                height: 200,
                text: `${url}#${category}`
            });

            if (type == 'png') {
                let qrDataURL = qr_div.find('canvas')[0].toDataURL("image/png");
                let qr_downloadLink = $(`#qrDownload_${table_number}`)[0];
                qr_downloadLink.href = qrDataURL;
                qr_downloadLink.download = `${table_number}.png`
            } else {
                htmlToImage.toSvg(qr_div[0], {})
                    .then(function(dataUrl) {
                        let svg = decodeURIComponent(dataUrl.split(',')[1])
                        const base64doc = btoa(unescape(encodeURIComponent(svg)));

                        let qr_downloadLink = $(`#qrDownload_${table_number}`)[0];
                        qr_downloadLink.href = base64doc;
                        qr_downloadLink.download = `${table_number}.svg`
                    });
            }

        }
    );

    $('#categories').change(
        function() {
            let number = '{!! $tableLayout->number !!}'
            let qr_div = $(`#qr_div_${number}`);
            let table_number = $('#table_number').val();
            let category = $('#categories').val();
            let type = $('#type').val();
            let url = route('menu', {_query: {table_number, category_no: category}})
            $(qr_div).html('')
            $(qr_div).qrcode({
                width: 200,
                height: 200,
                text: `${url}#${category}`
            });
            if (type == 'png') {
                let qrDataURL = qr_div.find('canvas')[0].toDataURL("image/png");
                let qr_downloadLink = $(`#qrDownload_${table_number}`)[0];
                qr_downloadLink.href = qrDataURL;
                qr_downloadLink.download = `${table_number}.png`
            } else {
                htmlToImage.toSvg(qr_div, {})
                    .then(function(dataUrl) {
                        let svg = decodeURIComponent(dataUrl.split(',')[1])
                        const base64doc = btoa(unescape(encodeURIComponent(svg)));

                        let qr_downloadLink = $(`#qrDownload_${table_number}`)[0];
                        qr_downloadLink.href = base64doc;
                        qr_downloadLink.download = `${table_number}.svg`
                    });
            }
        }
    );

    function downloadQr() {
        let number = {!! json_encode($tableLayout->number) !!}
        let download_link = document.getElementById(`qrDownload_${number}`);
        download_link.click();
    }
</script>

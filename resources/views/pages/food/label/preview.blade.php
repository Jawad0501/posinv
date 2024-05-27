<table align="center" style="border-spacing: {{ $barcode_details->col_distance * 1 }}in {{ $barcode_details->row_distance * 1 }}in; overflow: hidden !important;">
    @foreach ($menus as $menu)

        @if ($loop->index % $barcode_details->stickers_in_one_row == 0)
            <tr>
        @endif

        <td align="center" valign="center">
            <div style="overflow: hidden !important;display: flex; flex-wrap: wrap;align-content: center;width: {{ $barcode_details->width * 1 }}in; height: {{ $barcode_details->height * 1 }}in; justify-content: center;">

                <div>
                    <b style="display: block !important; font-size: 20px">{{ config('app.name') }}</b>

                    <span style="display: block !important; font-size: 15px">
                        {{ $menu->name }} {{ $menu->variant->name }}
                    </span>

                    {{-- Price --}}
                    <span style="font-size: 17px;">
                        Price:
                        <b>{{ convert_amount($menu->variant->price) }}</b>
                    </span>
                    <br>
                    {{-- <span style="font-size: 13px">
                        <b>Exp Date:</b>
                        {{ $product['expire_date'] }}
                    </span>
                    @if ($barcode_details->is_continuous)
                        <br>
                    @endif

                    <span style="font-size: 14px">
                        <b>Packing Date:</b>
                        {{ $product['packing_date'] }}
                    </span>
                    <br> --}}

                    {{-- Barcode --}}
                    <img
                        style="max-width:90% !important;height: {{ $barcode_details->height * 0.24 }}in !important; display: block;"
                        src="data:image/png;base64,{{ DNS1D::getBarcodePNG($menu->variant->sub_sku, 'C128', 3, 33) }}"
                    >
                    <span style="font-size: 10px !important">
                        {{ $menu->variant->sub_sku }}
                    </span>
                </div>
            </div>
        </td>

        @if ($loop->iteration % $barcode_details->stickers_in_one_row == 0)
            </tr>
        @endif
    @endforeach
</table>

<style type="text/css">
    td {
        border: 1px dotted lightgray;
    }

    @media print {
        table {
            page-break-after: always;
        }
        @page {
            size: {{ $paper_width }}in {{ $paper_height }}in;
            margin-top: {{ $margin_top }}in !important;
            margin-bottom: {{ $margin_top }}in !important;
            margin-left: {{ $margin_left }}in !important;
            margin-right: {{ $margin_left }}in !important;
        }
    }
</style>

<script>
    window.print()
</script>

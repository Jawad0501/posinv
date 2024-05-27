@props(['server' => true])
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/JSZip-2.5.0/jszip.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/pdfmake-0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/pdfmake-0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/DataTables-1.12.1/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/DataTables-1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/AutoFill-2.4.0/js/dataTables.autoFill.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/AutoFill-2.4.0/js/autoFill.bootstrap5.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/Buttons-2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/Buttons-2.2.3/js/buttons.bootstrap5.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/Buttons-2.2.3/js/buttons.colVis.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/Buttons-2.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/Buttons-2.2.3/js/buttons.print.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/ColReorder-1.5.6/js/dataTables.colReorder.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/DateTime-1.1.2/js/dataTables.dateTime.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/FixedColumns-4.1.0/js/dataTables.fixedColumns.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/FixedHeader-3.2.4/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/KeyTable-2.7.0/js/dataTables.keyTable.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/Responsive-2.3.0/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/Responsive-2.3.0/js/responsive.bootstrap5.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/RowGroup-1.2.0/js/dataTables.rowGroup.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/RowReorder-1.2.8/js/dataTables.rowReorder.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/Scroller-2.0.7/js/dataTables.scroller.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/SearchBuilder-1.3.4/js/dataTables.searchBuilder.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/SearchBuilder-1.3.4/js/searchBuilder.bootstrap5.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/SearchPanes-2.0.2/js/dataTables.searchPanes.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/SearchPanes-2.0.2/js/searchPanes.bootstrap5.min.js"></script>
{{-- <script type="text/javascript" src="{{asset('build/assets/backend')}}/plugins/DataTables/Select-1.4.0/js/dataTables.select.min.js"></script> --}}
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/StateRestore-1.1.1/js/dataTables.stateRestore.min.js"></script>
<script type="text/javascript" src="{{ asset('build/assets/backend') }}/plugins/DataTables/StateRestore-1.1.1/js/stateRestore.bootstrap5.min.js"></script>

@if ($server)
    <script>
        let dtDom =
            `<'row'<'col-sm-5 col-md-3 mb-3'l><'col-sm-7 col-md-5 text-center mb-3'B><'col-12 col-md-4 text-center text-md-end'f>rt> <'row'<'col-sm-12'tr>><'row'<'col-sm-4'i><'col-sm-8'p>>`;
        $.extend(true, $.fn.dataTable.defaults, {
            ordering: true,
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            oLanguage: {
                sSearchPlaceholder: "Search..."
            },
            language: {
                zeroRecords: "Nothing found - sorry",
                // info: "Showing page _PAGE_ of _PAGES_",
                infoEmpty: "No records available",
                infoFiltered: "(filtered from _MAX_ total records)"
            },
            lengthMenu: [
                [10, 25, 50, 100, 150, 200, 300, 400, 500, -1],
                [10, 25, 50, 100, 150, 200, 300, 400, 500, "All"]
            ],
            pageLength: 10,
            dom: dtDom,
            buttons: [
                'copy', 'excel', 'pdfHtml5', 'csv', 'print' //, 'colvis'
            ],
            order: [[0, 'desc']],
        });
    </script>
@endif

{{-- <script src="{{asset('build/assets/backend')}}/plugins/table/datatable/datatables.js"></script>
<script src="{{asset('build/assets/backend')}}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('build/assets/backend')}}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script> --}}

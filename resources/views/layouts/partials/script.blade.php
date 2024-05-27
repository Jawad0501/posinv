<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script src="{{ asset('build/assets/backend') }}/plugins/toastr/toastr.min.js" type="text/javascript"></script>
<script src="{{ asset('build/assets/backend') }}/plugins/sweetalerts/sweetalert2.min.js"></script>
<script src="{{ asset('build/assets/backend') }}/plugins/select2/dist/js/select2.min.js" type="text/javascript"></script>
<script src="{{ asset('build/assets/backend') }}/plugins/flatpickr/flatpickr.min.js" type="text/javascript"></script>
<script src="{{ asset('build/assets/backend') }}/plugins/summernote/summernote-lite.js" type="text/javascript"></script>
<script src="{{ asset('build/assets/backend') }}/plugins/metismenu/js/metisMenu.min.js"></script>
<script src="{{ asset('build/assets/backend') }}/plugins/simplebar/js/simplebar.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>


<script src="{{ asset('build/assets/backend/plugins/OwlCarousel/dist/owl.carousel.min.js') }}" ></script>
<script src="{{ asset('build/assets/backend/plugins/custom-srollbar-div/js/scrollBar.js') }}" ></script>
<script src="{{ asset('build/assets/backend') }}/plugins/clockpicker/dist/bootstrap-clockpicker.js" type="text/javascript"></script>


@vite('resources/js/backend/app.js')

<script type="text/javascript">
    $(document).ready(function() {
        $('.summernote').summernote({
            placeholder: 'Write...',
            tabsize: 2,
            height: 150,
            toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']], //, 'picture', 'video'
            ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        $(".clockpicker").clockpicker({
            align: 'left',
            autoclose: true,
            'default': 'now'
        });
        $(".datepicker").flatpickr();
        $(".datetimepicker").flatpickr({enableTime:true});
    });
</script>

@stack('js')

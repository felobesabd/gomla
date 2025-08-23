<!--begin::Footer-->
<div id="kt_app_footer" class="app-footer no-print">
    <!--begin::Footer container-->
    <div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
        <!--begin::Copyright-->
        <div class="text-dark order-2 order-md-1">
            <span class="text-muted fw-semibold me-1">2023&copy;</span>
            <a href="{{url(target())}}" target="_blank" class="text-gray-800 text-hover-primary">{{config('settings.app_name')}}</a>
        </div>
        <!--end::Copyright-->
        <!--begin::Menu-->
        <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
            <li class="menu-item">
                <a href="{{url('/')}}" target="_blank" class="menu-link px-2">About</a>
            </li>
            <li class="menu-item">
                <a href="{{url('/')}}" target="_blank" class="menu-link px-2">Support</a>
            </li>
            <li class="menu-item">
                <a href="{{url('/')}}" target="_blank" class="menu-link px-2">Purchase</a>
            </li>
        </ul>
        <!--end::Menu-->
    </div>
    <!--end::Footer container-->
</div>
<!--end::Footer-->

</div>
<!--end:::Main-->
</div>
<!--end::Wrapper-->
</div>
<!--end::Page-->
<!-- Mouse Cursor start -->
<div class="mouse-move mouse-outer"></div>
<div class="mouse-move mouse-inner"></div>
<!-- Mouse Cursor Ends -->
</div>
<!--end::App-->

<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{url('design/admin')}}/assets/plugins/global/plugins.bundle.js"></script>
<script src="{{url('design/admin')}}/assets/js/scripts.bundle.js"></script>
<script src="{{url('design/assets/js/helper.js')}}"></script>
<script src="{{url('design/admin')}}/assets/js/custom.js"></script>
<script src="{{url('design/admin')}}/assets/js/moment.min.js"></script>
<script src="{{url('design/admin')}}/assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<!-- Include Select2 JS -->
{{--<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>--}}
<script src="{{url('design/admin')}}/assets/select2/select2.min.js"></script>



<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.18/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.18/vfs_fonts.js"></script>






<!--end::Global Javascript Bundle-->
@stack('footer')
</body>
<!--end::Body-->

</html>

@include('admin.layout.header')
@include('admin.layout.navbar')
@include('admin.layout.sidebar')

@include('admin.layout.message')
<!--begin::Content wrapper-->
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid pt-5">
        @yield('content')
    </div>
    <!--end::Content-->
</div>
<!--end::Content wrapper-->
@include('admin.layout.footer')

@extends('admin.layout.master')

@section('title')

@endsection
الحاويات
@push('header')
@endpush

@section('content')
<!--begin::Content container-->
<div id="kt_app_content_container" class="app-container container-xxl">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-8 col-auto">
                <h3 class="page-title">الحاويات</h3>
            </div>

            <div class="col-sm-4 col">
                <a href="{{ route('admin.containers.create') }}" class="btn btnColor float-end mt-2">إضافة حاوية</a>
            </div>
        </div>
    </div>

    <!--begin::Card-->
    <div class="card">
        <!--begin::Card header-->
        {{--<div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="search" id="searchbox" class="form-control form-control-solid w-250px ps-12" placeholder="Search" />
                </div>
                <!--end::Search-->
            </div>
            <!--end::Card title-->
        </div>--}}
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-6">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5 ajax-data-table">
                <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-125px">#</th>
                        <th class="min-w-125px">رقم الحاوية</th>
                        <th class="min-w-125px">العنصر</th>
                        <th class="min-w-125px">المورد</th>
                        <th class="min-w-125px">الوحدة</th>
                        <th class="min-w-125px">العدد</th>
                        <th class="min-w-125px">الكمية</th>
                        <th class="min-w-125px">الوصول</th>
                        <th class="min-w-125px">الإجراءات</th>
                    </tr>
                </thead>
            </table>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</div>
<!--end::Content container-->
@endsection

@push('footer')
<script src="{{ url('design/admin/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script>
    var columns = [
        {
            data: null,
            name: '#',
            className: "text-center ",
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
            data: 'container_number',
            name: 'container_number',
            className: "text-start",
        },
        {
            data: 'item_id',
            name: 'item_id',
            className: "text-start",
        },
        {
            data: 'supplier_id',
            name: 'supplier_id',
            className: "text-start",
        },
        {
            data: 'unit_id',
            name: 'unit_id',
            className: "text-start",
        },
        {
            data: 'number',
            name: 'number',
            className: "text-start",
        },
        {
            data: 'qty',
            name: 'qty',
            className: "text-start",
        },
        {
            data: 'arrival_date',
            name: 'arrival_date',
            className: "text-start",
        },
        {
            data: 'action',
            name: 'action',
            className: "text-center",
            orderable: false,
            searchable: false
        },
    ];

    console.log(columns);

    var ajax_url = "{!! route('admin.containers.data-table') !!}";

    $(function() {
        createDatatable(columns, ajax_url);
    });

    $.fn.dataTable.ext.errMode = 'none';
</script>
@endpush

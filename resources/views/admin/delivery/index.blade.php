@extends('admin.layout.master')

@section('title')
    تسليم المندوبين
@endsection

@push('header')
@endpush

@section('content')
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-8 col-auto">
                    <h3 class="page-title no-print">تسليم المندوبين</h3>
                </div>

                <div class="col-sm-4 col">
                    <a href="{{ route('admin.delivery.save_view') }}" class="btn btnColor no-print float-end mt-2">
                        إضافة تسليم
                    </a>
                </div>
            </div>
        </div>

        <!--begin::Card-->
        <div class="card">
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table-->

                <div class="row d-flex justify-content-between align-items-center pt-12" style="padding-bottom: 15px">
                    {{--                <div class="print-only col-sm-6 col-md-6 col-auto">--}}
                    {{--                    <h2 class="page-title">Accident Report</h2>--}}
                    {{--                </div>--}}
                    {{--                <div class="print-only col-sm-6 col-md-6 col-auto">--}}
                    {{--                    <h2 class="page-title" style="float: right">Print Time : {{ now() }}</h2>--}}
                    {{--                </div>--}}
                    {{--               <hr class="print-only" style="color: black">--}}
                </div>

                <table class="table align-middle table-row-dashed fs-6 gy-5 ajax-data-table">
                    <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-10px">#</th>
                        <th class="min-w-10px">المندوب</th>
                        <th class="min-w-10px">رقم المستند</th>
                        <th class="min-w-10px">تم التسليم</th>
                        <th class="min-w-10px">الحالة</th>
                        <th class="min-w-10px no-print">الإجراءات</th>
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
                },
                orderable: false,
                searchable: false
            },
            {
                data: 'representative_id',
                name: 'representative_id',
                className: "text-start ",
            },
            {
                data: 'document_no',
                name: 'document_no',
                className: "text-start",
            },
            {
                data: 'delivered_at',
                name: 'delivered_at',
                className: "text-center",
            },
            {
                data: 'status',
                name: 'status',
                className: "text-center ",
            },
            {
                data: 'action',
                name: 'action',
                className: "text-center no-print ",
                orderable: false,
                searchable: false,
            },
        ];

        console.log(columns);

        var ajax_url = "{!! route('admin.delivery.index') !!}";

        $(function () {
            createDatatable(columns, ajax_url);
        });

        $.fn.dataTable.ext.errMode = 'none';
    </script>
@endpush

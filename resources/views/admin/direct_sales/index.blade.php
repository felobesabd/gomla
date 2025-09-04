@extends('admin.layout.master')

@section('title')
    المبيعات المباشرة
@endsection

@push('header')
@endpush

@section('content')
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-8 col-auto">
                    <h3 class="page-title no-print">المبيعات المباشرة</h3>
                </div>
                <div class="col-sm-4 col">
                    <a href="{{ route('admin.direct_sales.create') }}" class="btn btnColor no-print float-end mt-2">
                        إضافة بيع مباشر
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body pt-0">
                <table class="table align-middle table-row-dashed fs-6 gy-5 ajax-data-table">
                    <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-10px">#</th>
                        <th class="min-w-10px">رقم المستند</th>
                        <th class="min-w-10px">اسم العميل</th>
                        {{--<th class="min-w-10px">هاتف العميل</th>--}}
                        <th class="min-w-10px">المبلغ الإجمالي</th>
                        <th class="min-w-10px">تاريخ البيع</th>
                        <th class="min-w-10px">الحالة</th>
                        <th class="min-w-10px no-print">الإجراءات</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('footer')
    <script src="{{ url('design/admin/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script>
        var columns = [
            {
                data: null,
                name: '#',
                className: "text-center",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                orderable: false,
                searchable: false
            },
            {
                data: 'document_no',
                name: 'document_no',
                className: "text-start",
            },
            {
                data: 'customer_name',
                name: 'customer_name',
                className: "text-start",
            },
            /*{
                data: 'customer_phone',
                name: 'customer_phone',
                className: "text-start",
            },*/
            {
                data: 'total_amount',
                name: 'total_amount',
                className: "text-center",
            },
            {
                data: 'sold_at',
                name: 'sold_at',
                className: "text-center",
            },
            {
                data: 'status',
                name: 'status',
                className: "text-center",
            },
            {
                data: 'action',
                name: 'action',
                className: "text-center no-print",
                orderable: false,
                searchable: false,
            },
        ];

        var ajax_url = "{!! route('admin.direct_sales.index') !!}";

        $(function () {
            createDatatable(columns, ajax_url);
        });

        $.fn.dataTable.ext.errMode = 'none';
    </script>
@endpush

@extends('admin.layout.master')

@section('title')
    العناصر
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
                <h3 class="page-title">العناصر</h3>
            </div>

            <div class="col-sm-4 col">
                <a href="{{route('admin.items.create')}}" class="btn btnColor float-end mt-2">اضافة عنصر</a>
            </div>
        </div>
    </div>

    <!--begin::Card-->
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5 ajax-data-table">
                <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-125px">#</th>
                        <th class="min-w-125px">الاسم</th>
                        <th class="min-w-125px">تم إنشاؤه</th>
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
            data: 'item_name',
            name: 'item_name',
            className: "text-start",
        },
        {
            data: 'created_at',
            name: 'created_at',
            className: "text-center",
            render: function(data, type, row) {
                return data ? data.slice(0, 10) : '';
            }
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

    var ajax_url = "{!! route('admin.items.data-table') !!}";

    $(function() {
        createDatatable(columns, ajax_url);
    });

    $.fn.dataTable.ext.errMode = 'none';
</script>
@endpush

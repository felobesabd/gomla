@extends('admin.layout.master')

@section('title')
Department
@endsection

@push('header')
@endpush

@section('content')
<!--begin::Content container-->
<div id="kt_app_content_container" class="app-container container-xxl">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-8 col-auto">
                <h3 class="page-title">Department</h3>
            </div>

            <div class="col-sm-4 col">
                <a href="{{route('admin.departments.create')}}" class="btn btnColor float-end mt-2">Add Department</a>
            </div>
        </div>
    </div>

    <!--begin::Card-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <!--begin::Card title-->
                <div class="d-flex fv-row me-3">
                    <label class="fs-6 fw-semibold" style="width: 40px">From Date:</label>
                    <input type="text" class="form-control datepicker" id="from_date" style="width: 110px"/>
                </div>

                <div class="d-flex fv-row me-3">
                    <label class="fs-6 fw-semibold" style="width: 40px">To Date:</label>
                    <input type="text" class="form-control datepicker" id="to_date" style="width: 110px"/>
                </div>

                <div class="d-flex fv-row me-3">
                    <label class="fs-6 fw-semibold me-3" style="width: 80px">Select Employee:</label>
                    <select class="form-control" id="report-employee" style="height: 44px;">
                        <option selected value="0">All</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex fv-row me-3">
                    <label class="fs-6 fw-semibold me-3" style="width: 80px">Select Department</label>
                    <select class="form-control" id="report-department" style="height: 44px;">
                        <option selected value="0">All</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name_en }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!--end::Card title-->
        </div>
        <hr>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-6">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5 ajax-data-table">
                <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-125px">#</th>
                        <th class="min-w-125px">Name</th>
                        <th class="min-w-125px">AC-No</th>
                        <th class="min-w-125px">Employee</th>
                        <th class="min-w-125px">Joining</th>
                        <th class="min-w-125px no-print">Actions</th>
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
    /*let user_role = "{{request('user_role')}}";*/
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
            data: 'name_en',
            name: 'name_en',
            className: "text-start",
        },
        {
            data: 'code',
            name: 'code',
            className: "text-center",
        },
        {
            data: 'name',
            name: 'name',
            className: "text-start",
        },
        {
            data: 'joining_date',
            name: 'joining_date',
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

    var ajax_url = "{!! route('admin.departments.data-table') !!}";

    $(function() {
        createDatatable(columns, ajax_url, [0, 'desc'], '0,1,2,3,4');
    });

    $.fn.dataTable.ext.errMode = 'none';
</script>
@endpush

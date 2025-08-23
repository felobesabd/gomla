@extends('admin.layout.master')

@section('title')
Suppliers
@endsection

@push('header')
@endpush

@section('content')
<!--begin::Content container-->
<div id="kt_app_content_container" class="app-container container-xxl">
    <form class="form" action="{{ route('admin.suppliers.show', ['supplier' => $supplier->id]) }}" method="post">
        @csrf
        <input name="_method" type="hidden" value="PATCH" />
        <div class="row">
            <div class="card card-flush py-10">
                <!--begin::Modal body-->
                <div class="modal-body px-lg-17">
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Company Name</label>
                        <input type="text" class="form-control disabled" name="company_name" value="{{ $supplier->company_name }}"/>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Phone Number</label>
                        <input type="text" class="form-control disabled" name="phone_number" value="{{ $supplier->phone_number }}"/>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Address</label>
                        <input type="text" class="form-control disabled" name="address" value="{{ $supplier->address }}"/>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Commercial Register Number</label>
                        <input type="text" class="form-control disabled" name="commercial_register_no"
                               value="{{ $supplier->commercial_register_no }}"/>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Number Of Tax</label>
                        <input type="text" class="form-control disabled" name="tax_value_added"
                               value="{{ $supplier->tax_value_added }}"/>
                    </div>

                    @foreach($supplierContacts as $key => $supplierContact)
                        <div class="supplier-details d-flex justify-content-between mb-3" id="supplier-details">
                            <div class="col-sm-2 fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">Supplier Name</label>
                                <input type="text" class="form-control disabled" name="supplier_name[{{ $key }}]"
                                       value="{{ $supplierContact->supplier_name }}"/>
                            </div>

                            <div class="col-sm-2 fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">Email</label>
                                <input type="text" class="form-control disabled" name="email[{{ $key }}]"
                                       value="{{ $supplierContact->email }}"/>
                            </div>

                            <div class="col-sm-2 fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">Phone</label>
                                <input type="text" class="form-control disabled" name="phone[{{ $key }}]"
                                       value="{{ $supplierContact->phone }}"/>
                            </div>

                            <div class="col-sm-2 fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">Whats App</label>
                                <input type="text" class="form-control disabled" name="whats_app[{{ $key }}]"
                                       value="{{ $supplierContact->whats_app }}"/>
                            </div>

                            <div class="col-sm-2 fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">Department</label>
                                <input type="text" class="form-control disabled" name="department[{{ $key }}]"
                                       value="{{ $supplierContact->department }}"/>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!--end::Modal body-->
                <div class="modal-footer flex-center">
                    <!--begin::Button-->
                    <button type="submit" class="btn btn-primary disabled">
                        <span class="indicator-label">Submit</span>
                    </button>
                    <!--end::Button-->
                </div>
            </div>
        </div>
    </form>
</div>
<!--end::Content container-->
@endsection

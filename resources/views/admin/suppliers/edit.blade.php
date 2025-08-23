@extends('admin.layout.master')

@section('title')
    تعديل مورد
@endsection

@push('header')
@endpush

@section('content')
<!--begin::Content container-->
<div id="kt_app_content_container" class="app-container container-xxl">
    <form class="form" action="{{ route('admin.suppliers.update', ['supplier' => $supplier->id]) }}" method="post">
        @csrf
        <input name="_method" type="hidden" value="PATCH" />
        <div class="row">
            <div class="card card-flush py-10">
                <!--begin::Modal body-->
                <div class="modal-body px-lg-17">
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">الاسم</label>
                        <input type="text" class="form-control" name="supplier_name"
                               value="{{ $supplier->supplier_name }}" required/>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">الايميل</label>
                        <input type="text" class="form-control" name="email"
                               value="{{ $supplier->email }}"/>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">الهاتف</label>
                        <input type="text" class="form-control" name="phone"
                               value="{{ $supplier->phone }}"/>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">البلد</label>
                        <input type="text" class="form-control" name="country"
                               value="{{ $supplier->country }}"/>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">العنوان</label>
                        <textarea type="text" class="form-control" name="address">{{ $supplier->address }}</textarea>
                    </div>
                </div>
                <!--end::Modal body-->
                <div class="modal-footer flex-center">
                    <!--begin::Button-->
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">تعديل</span>
                    </button>
                    <!--end::Button-->
                </div>
            </div>
        </div>
    </form>
</div>
<!--end::Content container-->
@endsection

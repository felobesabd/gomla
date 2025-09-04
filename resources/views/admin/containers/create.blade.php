@extends('admin.layout.master')

@section('title')
    الوحدات
@endsection

@push('header')
@endpush

@section('content')
<!--begin::Content container-->
<div id="kt_app_content_container" class="app-container container-xxl">
    <form class="form" action="{{ route('admin.containers.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="card card-flush py-10">
                <!--begin::Modal body-->
                <div class="modal-body px-lg-17">
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">العنصر</label>
                        <select class="form-select" name="item_id" required>
                            <option value="">حدد العنصر</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->item_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">المورد</label>
                        <select class="form-select" name="supplier_id" required>
                            <option value="">اختر المورد</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->supplier_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">الوحدة</label>
                        <select class="form-select" id="unit_id" name="unit_id" required>
                            <option value="">اختر الوحدة</option>
                            @foreach ($units as $unit)
                                <option
                                    value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}
                                    data-weight="{{ $unit->weight }}">
                                    {{ $unit->unit_name }} -- {{ $unit->weight }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">العدد</label>
                        <input type="number" step="0.01" class="form-control" id="number" name="number"
                               value="{{ old('number', 0) }}" required>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">الكمية</label>
                        <input type="text" class="form-control" id="qty" name="qty"
                               value="{{ old('qty', 0) }}" readonly>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">تاريخ الوصول</label>
                        <input type="date" class="form-control" id="arrival_date" name="arrival_date"
                               value="{{ old('arrival_date') }}">
                    </div>
                </div>
                <!--end::Modal body-->
                <div class="modal-footer flex-center">
                    <!--begin::Button-->
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">اضافة</span>
                    </button>
                    <!--end::Button-->
                </div>
            </div>
        </div>
    </form>
</div>
<!--end::Content container-->
@endsection

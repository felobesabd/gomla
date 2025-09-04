@extends('admin.layout.master')

@section('title')
    تسليم المندوبين
@endsection

@push('header')
@endpush

@section('content')
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
        <form class="form" action="{{ route('admin.delivery.save', isset($delivery_obj) ? $delivery_obj->id : '') }}"
              method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="card card-flush py-10">
                    <!--begin::Modal body-->
                    <div class="modal-body px-lg-17">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold mb-2">اختر المندوب</label>
                            <select class="form-control" name="representative_id" required>
                                <option value="">اختر</option>
                                @foreach($representatives as $representative)
                                    <option
                                        value="{{ $representative->id }}" {{ isset($delivery_obj) &&$delivery_obj->representative_id == $representative->id ? 'selected' : ''}}>
                                        {{ $representative->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold mb-2">ميعاد التسليم</label>
                            <input type="datetime-local" class="form-control" name="delivered_at"
                                   value="{{ old('delivered_at', isset($delivery_obj) && isset($delivery_obj->delivered_at) ? \Carbon\Carbon::parse($delivery_obj->delivered_at)->format('Y-m-d\TH:i') : '') }}"/>
                        </div>

{{--                        @if(isset($delivery_items) && !$delivery_items->isEmpty())

                            @foreach($delivery_items as $delivery_item)

                                <div class="report-items d-flex justify-content-between mb-3">
                                    <div class="col-sm-2 fv-row mb-7">
                                        <label class="fs-6 fw-semibold mb-2">العنصر</label>
                                        <select class="form-control" name="item_id[]"
                                                style="width: 239px" required>
                                            <option value="">اختر</option>
                                            @foreach($items as $index => $item)
                                                <option
                                                    value="{{ $item->id }}"
                                                    data-weight="{{ $item->weight }}"
                                                    {{ $delivery_item->item_id == $item->id ? 'selected' : '' }}
                                                >
                                                    {{ $item->item_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-2 fv-row mb-7">
                                        <label class="fs-6 fw-semibold mb-2">الوحدة</label>
                                        <select class="form-control" name="unit_id[]"
                                                style="width: 239px" required>
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

                                    <div class="col-sm-1 fv-row mb-7">
                                        <label class="fs-6 fw-semibold mb-2">العدد</label>
                                        <input type="number" step="0.01" class="form-control" id="number"
                                               name="quantity[]" value="{{ $delivery_item->quantity }}"
                                               required>
                                    </div>

                                    <div class="col-sm-1 fv-row mb-7">
                                        <label class="fs-6 fw-semibold mb-2">الكمية</label>
                                        <input type="text" class="form-control" id="qty" name="weight[]"
                                               value="{{ $delivery_item->weight }}" readonly>
                                    </div>

                                    <div class="">
                                        <button type="button" class="delete-sales-items mt-8" id="delete-sales-items">
                                            X
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="report-items d-flex justify-content-between mb-3">
                                <div class="col-sm-2 fv-row mb-7">
                                    <label class="fs-6 fw-semibold mb-2">العنصر</label>
                                    <select class="form-control" name="item_id[]" required>
                                        <option value="">اختر</option>
                                        @foreach($items as $index => $item)
                                            <option
                                                value="{{ $item->id }}"
                                                {{ old('item_id') == $item->id ? 'selected' : '' }}
                                                data-weight="{{ $item->weight }}"
                                            >
                                                {{ $item->item_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-2 fv-row mb-7">
                                    <label class="fs-6 fw-semibold mb-2">الوحدة</label>
                                    <select class="form-control" name="unit_id[]" id="unit_id" required>
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

                                <div class="col-sm-2 fv-row mb-7">
                                    <label class="fs-6 fw-semibold mb-2">العدد</label>
                                    <input type="number" step="0.01" class="form-control" id="number"
                                           name="quantity[]" value="{{ old('quantity', 0) }}"
                                           required>
                                </div>

                                <div class="col-sm-2 fv-row mb-7">
                                    <label class="fs-6 fw-semibold mb-2">الكمية</label>
                                    <input type="text" class="form-control" id="qty" name="weight[]"
                                           value="{{ old('weight', 0) }}" readonly>
                                </div>

                                <div class="">
                                    <button type="button" class="delete-sales-items mt-8" id="delete-sales-items">
                                        X
                                    </button>
                                </div>
                            </div>
                        @endif

                        <div>
                            <button type="button" class="add-sales-items" id="add-sales-items">إضافة</button>
                        </div>--}}

                        @if(isset($delivery_items) && !$delivery_items->isEmpty())
                            @foreach($delivery_items as $delivery_item)
                                <div class="delivery-item-row d-flex justify-content-between mb-3">
                                    <div class="col-sm-2 form-group mb-7">
                                        <label class="fs-6 fw-semibold mb-2">العنصر</label>
                                        <select class="form-control item-select" name="item_id[]" required>
                                            <option value="">اختر</option>
                                            @foreach($items as $item)
                                                <option
                                                    value="{{ $item->id }}"
                                                    data-weight="{{ $item->weight }}"
                                                    {{ $delivery_item->item_id == $item->id ? 'selected' : '' }}
                                                >
                                                    {{ $item->item_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-2 form-group mb-7">
                                        <label class="fs-6 fw-semibold mb-2">الوحدة</label>
                                        <select class="form-control unit-select" name="unit_id[]" required>
                                            <option value="">اختر الوحدة</option>
                                            @foreach($units as $unit)
                                                <option
                                                    value="{{ $unit->id }}"
                                                    {{ $delivery_item->unit_id == $unit->id ? 'selected' : '' }}
                                                    data-weight="{{ $unit->weight }}"
                                                >
                                                    {{ $unit->unit_name }} -- {{ $unit->weight }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-2 form-group mb-7">
                                        <label class="fs-6 fw-semibold mb-2">العدد</label>
                                        <input type="number" step="0.01" class="form-control quantity-input" name="quantity[]"
                                               value="{{ $delivery_item->quantity }}" required>
                                    </div>

                                    <div class="col-sm-2 form-group mb-7">
                                        <label class="fs-6 fw-semibold mb-2">الكمية</label>
                                        <input type="text" class="form-control weight-input" name="weight[]"
                                               value="{{ $delivery_item->weight }}" readonly>
                                    </div>

                                    <div>
                                        <button type="button" class="btn btn-danger remove-item-row mt-8">X</button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="delivery-item-row d-flex justify-content-between mb-3">
                                <div class="col-sm-2 form-group mb-7">
                                    <label class="fs-6 fw-semibold mb-2">العنصر</label>
                                    <select class="form-control item-select" name="item_id[]" required>
                                        <option value="">اختر</option>
                                        @foreach($items as $item)
                                            <option
                                                value="{{ $item->id }}"
                                                {{ old('item_id') == $item->id ? 'selected' : '' }}
                                                data-weight="{{ $item->weight }}"
                                            >
                                                {{ $item->item_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-2 form-group mb-7">
                                    <label class="fs-6 fw-semibold mb-2">الوحدة</label>
                                    <select class="form-control unit-select" name="unit_id[]" required>
                                        <option value="">اختر الوحدة</option>
                                        @foreach($units as $unit)
                                            <option
                                                value="{{ $unit->id }}"
                                                {{ old('unit_id') == $unit->id ? 'selected' : '' }}
                                                data-weight="{{ $unit->weight }}"
                                            >
                                                {{ $unit->unit_name }} -- {{ $unit->weight }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-2 form-group mb-7">
                                    <label class="fs-6 fw-semibold mb-2">العدد</label>
                                    <input type="number" step="0.01" class="form-control quantity-input" name="quantity[]"
                                           value="{{ old('quantity', 0) }}" required>
                                </div>

                                <div class="col-sm-2 form-group mb-7">
                                    <label class="fs-6 fw-semibold mb-2">الكمية</label>
                                    <input type="text" class="form-control weight-input" name="weight[]"
                                           value="{{ old('weight', 0) }}" readonly>
                                </div>

                                <div>
                                    <button type="button" class="btn btn-danger remove-item-row mt-8">X</button>
                                </div>
                            </div>
                        @endif

                        <div>
                            <button type="button" class="btn btn-primary add-item-row" id="add-item-row">إضافة</button>
                        </div>

                        <!-- Hidden input for tracking deleted items -->
                        <input type="hidden" class="deleted-item-indexes" name="deleted_item_indexes" value="[]">
                    </div>

                    <!--end::Modal body-->
                    <div class="modal-footer flex-center">
                        <!--begin::Button-->
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">تقديم</span>
                        </button>
                        <!--end::Button-->
                    </div>
                </div>

            </div>
        </form>
    </div>

    <script>
        if (typeof jQuery === 'undefined') {
            console.error('jQuery is not loaded. Please ensure jQuery is included.');
        } else {
            class DeliveryItemManager {
                // Calculate quantity for a specific row with weight validation
                static calculateQuantity(row) {
                    const $itemSelect = row.find('.item-select');
                    const $unitSelect = row.find('.unit-select');
                    const $quantityInput = row.find('.quantity-input');
                    const $weightInput = row.find('.weight-input');

                    const selectedItem = $itemSelect[0].options[$itemSelect[0].selectedIndex];
                    const itemWeight = selectedItem ? parseFloat(selectedItem.getAttribute('data-weight')) || 0 : 0;

                    const selectedUnit = $unitSelect[0].options[$unitSelect[0].selectedIndex];
                    const unitWeight = selectedUnit ? parseFloat(selectedUnit.getAttribute('data-weight')) || 0 : 0;

                    const quantity = parseFloat($quantityInput.val()) || 0;
                    const totalWeight = (unitWeight * quantity).toFixed(2);

                    console.log(itemWeight, totalWeight); // 0 '30.00'

                    // Validate total weight against available item weight
                    if (totalWeight > itemWeight) {
                        alert('الكمية المطلوبة تتجاوز الوزن المتاح في المستودع! الوزن المتاح: ' + itemWeight);
                        $quantityInput.val(0);
                        $weightInput.val('0.00');
                        return;
                    }

                    $weightInput.val(totalWeight);
                }

                static initializeRow($row) {
                    // Bind event listeners for quantity calculation
                    $row.find('.item-select').on('change', () => this.calculateQuantity($row));
                    $row.find('.unit-select').on('change', () => this.calculateQuantity($row));
                    $row.find('.quantity-input').on('input', () => this.calculateQuantity($row));

                    // Trigger initial calculation
                    this.calculateQuantity($row);
                }

                static cloneRow() {
                    const $templateRow = $('.delivery-item-row').first().clone();

                    // Reset form elements
                    $templateRow.find('input, select').val('');

                    // Initialize the new row
                    this.initializeRow($templateRow);

                    // Insert before the add button
                    $templateRow.insertBefore('#add-item-row');
                }

                static removeRow($button) {
                    const $row = $button.closest('.delivery-item-row');
                    const index = $button.data('index');
                    const id = $button.data('id');
                    const route = $button.data('route');

                    if ($button.data('item-tracking')) {
                        $.ajax({
                            url: route,
                            dataType: 'json',
                            success: (data) => console.log(data),
                            error: (xhr, status, error) => console.error('AJAX error:', error)
                        });
                    }

                    if (index !== undefined && index !== null) {
                        let deleteIndexes = $('.deleted-item-indexes').val() || '[]';
                        deleteIndexes = JSON.parse(deleteIndexes);
                        deleteIndexes[index] = id;
                        $('.deleted-item-indexes').val(JSON.stringify(deleteIndexes));
                    }

                    $row.remove();

                    // Remove add button if no rows remain
                    if ($('.delivery-item-row').length === 0) {
                        $('#add-item-row').remove();
                    }
                }

                static initialize() {
                    // Initialize existing rows
                    $('.delivery-item-row').each((index, row) => {
                        this.initializeRow($(row));
                    });

                    // Add new row
                    $('#add-item-row').on('click', () => this.cloneRow());

                    // Remove row
                    $(document).on('click', '.remove-item-row', function() {
                        DeliveryItemManager.removeRow($(this));
                    });
                }
            }

            // Initialize when document is ready
            $(document).ready(() => {
                DeliveryItemManager.initialize();
            });
        }
    </script>
    <!--end::Content container-->
@endsection

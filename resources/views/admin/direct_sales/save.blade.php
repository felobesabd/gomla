@extends('admin.layout.master')

@section('title')
    المبيعات المباشرة
@endsection

@push('header')
@endpush

@section('content')
    <div id="kt_app_content_container" class="app-container container-xxl">
        <form class="form" action="{{ route(isset($sale_obj) ? 'admin.direct_sales.update' : 'admin.direct_sales.store', isset($sale_obj) ? $sale_obj->id : '') }}" method="post">
            @csrf
            <div class="row">
                <div class="card card-flush py-10">
                    <div class="modal-body px-lg-17">
                        <!-- Customer Information -->
                        <div class="row">
                            <div class="col-md-6 fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">اسم العميل</label>
                                <input type="text" class="form-control" name="customer_name"
                                       value="{{ old('customer_name', $sale_obj->customer_name ?? '') }}" required>
                            </div>
                            <div class="col-md-6 fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">هاتف العميل</label>
                                <input type="text" class="form-control" name="customer_phone"
                                       value="{{ old('customer_phone', $sale_obj->customer_phone ?? '') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">العنوان</label>
                                <textarea class="form-control" name="customer_address">{{ old('customer_address', $sale_obj->customer_address ?? '') }}</textarea>
                            </div>

                            <div class="col-md-6 fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">ملاحظات</label>
                                <textarea class="form-control" name="notes">{{ old('notes', $sale_obj->notes ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Sale Items (same structure as delivery items) -->
                        @if(isset($sale_items) && !$sale_items->isEmpty())
                            @foreach($sale_items as $sale_item)
                                <div class="sale-item-row d-flex justify-content-between mb-3">
                                    <div class="col-sm-2 form-group mb-7">
                                        <label class="fs-6 fw-semibold mb-2">العنصر</label>
                                        <select class="form-control item-select" name="item_id[]" required>
                                            <option value="">اختر</option>
                                            @foreach($items as $item)
                                                <option value="{{ $item->id }}" data-weight="{{ $item->weight }}"
                                                    {{ $sale_item->item_id == $item->id ? 'selected' : '' }}>
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
                                                <option value="{{ $unit->id }}" data-weight="{{ $unit->weight }}"
                                                    {{ $sale_item->unit_id == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->unit_name }} -- {{ $unit->weight }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-1 form-group mb-7">
                                        <label class="fs-6 fw-semibold mb-2">السعر</label>
                                        <input type="number" step="0.01" class="form-control unit-price" name="unit_price[]"
                                               value="{{ $sale_item->unit_price }}" required>
                                    </div>

                                    <div class="col-sm-1 form-group mb-7">
                                        <label class="fs-6 fw-semibold mb-2">العدد</label>
                                        <input type="number" step="0.01" class="form-control quantity-input" name="quantity[]"
                                               value="{{ $sale_item->quantity }}" required>
                                    </div>

                                    <div class="col-sm-1 form-group mb-7">
                                        <label class="fs-6 fw-semibold mb-2">الكمية</label>
                                        <input type="text" class="form-control weight-input" name="weight[]"
                                               value="{{ $sale_item->weight }}" readonly>
                                    </div>

                                    <div class="col-sm-1 form-group mb-7">
                                        <label class="fs-6 fw-semibold mb-2">الإجمالي</label>
                                        <input type="text" class="form-control total-price" readonly
                                               value="{{ $sale_item->total_price }}">
                                    </div>

                                    <div>
                                        <button type="button" class="btn btn-danger remove-item-row mt-8">X</button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="sale-item-row d-flex justify-content-between mb-3">
                                <div class="col-sm-2 form-group mb-7">
                                    <label class="fs-6 fw-semibold mb-2">العنصر</label>
                                    <select class="form-control item-select" name="item_id[]" required>
                                        <option value="">اختر</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}" data-weight="{{ $item->weight }}">
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
                                            <option value="{{ $unit->id }}" data-weight="{{ $unit->weight }}">
                                                {{ $unit->unit_name }} -- {{ $unit->weight }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-1 form-group mb-7">
                                    <label class="fs-6 fw-semibold mb-2">السعر</label>
                                    <input type="number" step="0.01" class="form-control unit-price" name="unit_price[]" value="0" required>
                                </div>

                                <div class="col-sm-1 form-group mb-7">
                                    <label class="fs-6 fw-semibold mb-2">العدد</label>
                                    <input type="number" step="0.01" class="form-control quantity-input" name="quantity[]" value="0" required>
                                </div>

                                <div class="col-sm-1 form-group mb-7">
                                    <label class="fs-6 fw-semibold mb-2">الكمية</label>
                                    <input type="text" class="form-control weight-input" name="weight[]" value="0" readonly>
                                </div>

                                <div class="col-sm-1 form-group mb-7">
                                    <label class="fs-6 fw-semibold mb-2">الإجمالي</label>
                                    <input type="text" class="form-control total-price" value="0" readonly>
                                </div>

                                <div>
                                    <button type="button" class="btn btn-danger remove-item-row mt-8">X</button>
                                </div>
                            </div>
                        @endif

                        <div>
                            <button type="button" class="btn btn-primary add-item-row" id="add-item-row">إضافة</button>
                        </div>

                        <input type="hidden" class="deleted-item-indexes" name="deleted_item_indexes" value="[]">
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">حفظ</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Similar JavaScript as delivery but with price calculations
            class SaleItemManager {
                static calculateItem(row) {
                    const $itemSelect = row.find('.item-select');
                    const unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
                    const quantity = parseFloat(row.find('.quantity-input').val()) || 0;
                    const unitWeight = parseFloat(row.find('.unit-select option:selected').data('weight')) || 0;

                    // Get selected item and its available weight
                    const selectedItem = $itemSelect.find('option:selected');
                    const itemAvailableWeight = parseFloat(selectedItem.data('weight')) || 0;

                    const totalWeight = (unitWeight * quantity).toFixed(2);
                    const totalPrice = (unitPrice * quantity).toFixed(2);

                    // Validate if total weight exceeds available item weight
                    if (parseFloat(totalWeight) > itemAvailableWeight) {
                        // Show error message
                        alert('الكمية المطلوبة (' + totalWeight + ') تتجاوز الوزن المتاح في المخزن (' + itemAvailableWeight + ') للمنتج: ' + selectedItem.text());

                        // Reset quantity to 0 and recalculate
                        row.find('.quantity-input').val(0);
                        row.find('.weight-input').val('0.00');
                        row.find('.total-price').val('0.00');
                        return;
                    }

                    row.find('.weight-input').val(totalWeight);
                    row.find('.total-price').val(totalPrice);
                }

                static initializeRow($row) {
                    $row.find('.unit-select, .quantity-input, .unit-price').on('input', () => this.calculateItem($row));
                    this.calculateItem($row);
                }

                static cloneRow() {
                    const $templateRow = $('.sale-item-row').first().clone();
                    $templateRow.find('input').val('');
                    $templateRow.find('select').val('');
                    this.initializeRow($templateRow);
                    $templateRow.insertBefore('#add-item-row');
                }

                static removeRow($button) {
                    $button.closest('.sale-item-row').remove();
                }

                static initialize() {
                    $('.sale-item-row').each((index, row) => {
                        this.initializeRow($(row));
                    });

                    $('#add-item-row').on('click', () => this.cloneRow());
                    $(document).on('click', '.remove-item-row', function() {
                        SaleItemManager.removeRow($(this));
                    });
                }
            }

            SaleItemManager.initialize();
        });
    </script>
@endsection

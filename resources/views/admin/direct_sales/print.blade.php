@extends('admin.layout.print')

@section('title')
    فاتورة بيع مباشر - #{{ $sale->document_no }}
@endsection

@section('content')
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header text-center">
            <h1>فاتورة بيع مباشر</h1>
            <p>رقم المستند: #{{ $sale->document_no }}</p>
            <p>تاريخ البيع: {{ $sale->sold_at->format('Y-m-d H:i') }}</p>
        </div>

        <!-- Customer Information -->
        <div class="customer-info mt-4">
            <h3>معلومات العميل</h3>
            <table class="table table-bordered">
                <tr>
                    <th>اسم العميل:</th>
                    <td>{{ $sale->customer_name }}</td>
                </tr>
                @if($sale->customer_phone)
                    <tr>
                        <th>الهاتف:</th>
                        <td>{{ $sale->customer_phone }}</td>
                    </tr>
                @endif
                @if($sale->customer_email)
                    <tr>
                        <th>البريد الإلكتروني:</th>
                        <td>{{ $sale->customer_email }}</td>
                    </tr>
                @endif
                @if($sale->customer_address)
                    <tr>
                        <th>العنوان:</th>
                        <td>{{ $sale->customer_address }}</td>
                    </tr>
                @endif
            </table>
        </div>

        <!-- Sale Items -->
        <div class="sale-items mt-4">
            <h3>المنتجات</h3>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>اسم المنتج</th>
                    <th>الوحدة</th>
                    <th>السعر</th>
                    <th>الكمية</th>
                    <th>الإجمالي</th>
                </tr>
                </thead>
                <tbody>
                @foreach($sale->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->item->item_name }}</td>
                        <td>{{ $item->unit->unit_name }} -- {{ $item->unit->weight }}</td>
                        <td>{{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="5" class="text-end">المبلغ الإجمالي:</th>
                    <th>{{ number_format($sale->total_amount, 2) }}</th>
                </tr>
                </tfoot>
            </table>
        </div>

        <!-- Notes -->
        @if($sale->notes)
            <div class="notes mt-4">
                <h3>ملاحظات</h3>
                <p>{{ $sale->notes }}</p>
            </div>
        @endif

        <!-- Signature Section -->
        <div class="representative-info mt-4">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 30%;">توقيع البائع:</th>
                    <td style="width: 70%;"></td>
                </tr>
                <tr>
                    <th style="width: 30%;">توقيع العميل:</th>
                    <td style="width: 70%;"></td>
                </tr>
            </table>
        </div>

        {{--<div class="signature-section mt-5">
            <div class="row">
                <div class="col-6">
                    <div class="signature-box">
                        <p class="text-start">توقيع البائع</p>
                    </div>
                </div>

                <div class="col-6">
                    <div class="signature-box">
                        <p class="text-start">توقيع العميل</p>
                     </div>
                </div>
            </div>
        </div>--}}

        <!-- Footer -->
        <div class="invoice-footer mt-5">
            <p>شكراً لتعاملكم معنا</p>
            <p>تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</p>
        </div>

        <div class="action-buttons no-print mt-4 text-center">
            <a href="{{ route('admin.direct_sales.index') }}" class="btn btn-secondary me-2">
                رجوع
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                طباعة
            </button>
        </div>
    </div>

    <script>
        // Auto print when page loads
        // window.onload = function() {
        //     window.print();
        // }
    </script>
@endsection

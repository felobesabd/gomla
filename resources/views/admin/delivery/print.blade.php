@extends('admin.layout.print')

@section('title')
    فاتورة تسليم مندوب - #{{ $delivery->document_no }}
@endsection

@section('content')
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header text-center">
            <h1>فاتورة تسليم مندوب</h1>
            <p>رقم المستند: #{{ $delivery->document_no }}</p>
            <p>تاريخ
                التسليم: {{ $delivery->delivered_at ? $delivery->delivered_at->format('Y-m-d H:i') : 'لم يتم التسليم بعد' }}</p>
        </div>

        <!-- Representative Information -->
        <div class="representative-info mt-4">
            <h3>معلومات المندوب</h3>
            <table class="table table-bordered">
                <tr>
                    <th style="width: 30%;">اسم المندوب:</th>
                    <td style="width: 70%;">{{ $delivery->representative->name }}</td>
                </tr>
            </table>
        </div>

        <!-- Delivery Items -->
        <div class="delivery-items mt-4">
            <h3>المنتجات المسلمة</h3>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>اسم المنتج</th>
                    <th>الوحدة</th>
                    <th>الكمية</th>
                    <th>الوزن</th>
                </tr>
                </thead>
                <tbody>
                @foreach($delivery->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->item->item_name }}</td>
                        <td>{{ $item->unit->unit_name }} -- {{ $item->unit->weight }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->weight }} كجم</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Status Information -->
        {{--<div class="status-info mt-4">
            <h3>حالة التسليم</h3>
            <table class="table table-bordered">
                <tr>
                    <th>الحالة:</th>
                    <td>
                        @if($delivery->status == 0)
                            <span class="text-warning">قيد الانتظار</span>
                        @elseif($delivery->status == 1)
                            <span class="text-success">تم التأكيد</span>
                        @else
                            <span class="text-danger">تم الإلغاء</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>تاريخ الإنشاء:</th>
                    <td>{{ $delivery->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @if($delivery->updated_at)
                    <tr>
                        <th>تاريخ آخر تعديل:</th>
                        <td>{{ $delivery->updated_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endif
            </table>
        </div>--}}

        <!-- Signature Section -->
        <div class="representative-info mt-4">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 30%;">توقيع المسؤول:</th>
                    <td style="width: 70%;"></td>
                </tr>
                <tr>
                    <th>توقيع المندوب:</th>
                    <td></td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="invoice-footer mt-5">
            <p>شكراً لتعاملكم معنا</p>
            <p>تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</p>
        </div>

        <div class="action-buttons no-print mt-4 text-center">
            <a href="{{ route('admin.delivery.index') }}" class="btn btn-secondary me-2">
                رجوع
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                طباعة
            </button>
        </div>
    </div>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() {
        //     window.print();
        // }
    </script>
@endsection

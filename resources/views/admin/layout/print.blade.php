<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Bootstrap 5 RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl;
            margin: 0;
            padding: 20px;
            background-color: #fff;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #fff;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .invoice-header h1 {
            margin: 0;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        table th {
            background-color: #f8f9fa;
        }
        .text-end {
            text-align: left !important;
        }
        .invoice-footer {
            text-align: center;
            margin-top: 50px;
            border-top: 2px solid #333;
            padding-top: 20px;
        }
        .signature-section {
            margin-top: 40px;
        }
        .signature-box {
            border: 1px solid #ddd;
            text-align: center;
            height: 70px;
        }
        .action-buttons {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px dashed #ccc;
        }
        @media print {
            body {
                padding: 0;
            }
            .invoice-container {
                border: none;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .action-buttons {
                display: none !important;
            }
        }
    </style>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl;
            margin: 0;
            padding: 20px;
            background-color: #fff;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #fff;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .invoice-header h1 {
            margin: 0;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        table th {
            background-color: #f8f9fa;
        }
        .text-end {
            text-align: left !important;
        }
        .text-start {
            text-align: right !important;
        }
        .invoice-footer {
            text-align: center;
            margin-top: 50px;
            border-top: 2px solid #333;
            padding-top: 20px;
        }
        .signature-section {
            margin-top: 40px;
        }
        .signature-box {
            border: 1px solid #ddd;
            text-align: center;
        }
        .action-buttons {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px dashed #ccc;
        }
        @media print {
            body {
                padding: 0;
            }
            .invoice-container {
                border: none;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .action-buttons {
                display: none !important;
            }
        }
    </style>
</head>
<body>
@yield('content')
</body>
</html>

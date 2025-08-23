<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
	<!--begin::Head-->
    <head><base href=""/>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta property="og:locale" content="{{ app()->getLocale() == 'ar' ? 'ar_AR' : 'en_US' }}" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="Dashboard" />
        <meta property="og:site_name" content="Dashboard" />
        <link rel="shortcut icon" href="{{url('design/admin')}}/assets/media/logos/favicon.ico" />
        <title>@yield('title')</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!--begin::Fonts(mandatory for all pages)-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
        <!--end::Fonts-->

        <!--begin::Vendor Stylesheets(used for this page only)-->
        <link href="{{url('design/admin')}}/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
        <!--end::Vendor Stylesheets-->

        <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
        <link href="{{url('design/admin')}}/assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.3/css/buttons.dataTables.min.css">

        <link rel="shortcut icon" href="{{asset('assets/media/logos/favicon.ico')}}" />
        <!--begin::Fonts(mandatory for all pages)-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
        <!--end::Fonts-->
        <!--begin::Vendor Stylesheets(used for this page only)-->
        <link href="{{asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
        <!--end::Vendor Stylesheets-->
        <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
        @if(app()->getLocale() == 'en')
            <link href="{{url('design/admin')}}/assets/css/custom.css" rel="stylesheet" type="text/css" />
            <link href="{{asset('design/admin/assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
            <link href="{{asset('design/admin/assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
        @endif
        <!--end::Global Stylesheets Bundle-->

        <!--begin::Arabic RTL Support-->
        @if(app()->getLocale() == 'ar')
            <link href="{{url('design/admin')}}/assets/css/custom.css" rel="stylesheet" type="text/css" />
            <link href="{{ asset('design/admin/assets/css/style.bundle.rtl.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('design/admin/assets/plugins/global/plugins.bundle.rtl.css') }}" rel="stylesheet" type="text/css" />
        @endif
        <!--end::Arabic RTL Support-->

        <!-- Include jQuery UI for autocomplete -->
        <link rel = "stylesheet" type = "text/css" href = "https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" />
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

        <link href="{{url('design/admin')}}/assets/css/reports.css" rel="stylesheet" type="text/css" />

        <!-- Include Select2 CSS -->
        <link href="{{url('design/admin')}}/assets/select2/select2.css" rel="stylesheet"/>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://www.gstatic.com/charts/loader.js"></script>

        <!--end::Global Stylesheets Bundle-->
        @stack('header')
    </head>
	<!--end::Head-->

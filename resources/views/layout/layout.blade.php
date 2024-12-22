<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Nonny">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ url('public/favicon.png') }}">

    <title>LFMS</title>
    <!-- Custom styles for tables -->
    <link href="{{ url('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js'></script>

    {{-- jquery for speech transcription and select2--}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- FullCalendar Dependencies -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.js'></script>

    {{-- select2 css --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    {{-- fontawesome icons --}}
    <script src="https://kit.fontawesome.com/0963bbf854.js" crossorigin="anonymous"></script>

    <!-- Custom fonts for this template-->
    <link href="{{ url('/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ url('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        @include('layout.sidebar')
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                {{-- <div class="container-fluid"> --}}
                @include('layout.header')
                @yield('content')
                @yield('scripts')
                {{-- </div> --}}
            </div>
        </div>
    </div>
    @include('layout.footer')

    <!-- Bootstrap core JavaScript-->
    <script src="{{ url('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ url('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->

    <script src="{{ url('assets/js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ url('vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ url('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ url('assets/js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ url('assets/js/demo/chart-pie-demo.js') }}"></script>
    <script src="{{ url('js/demo/datatables-demo.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
</body>

</html>

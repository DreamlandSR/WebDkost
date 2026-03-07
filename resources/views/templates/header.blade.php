<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('img/asset 7.png') }}">
    <title>Halaman {{ $judul ?? 'Default Title' }}</title>

    @if(isset($minimal_header) && $minimal_header)
        <!-- Opsi 1: Memuat Default CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;700&display=swap" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @else
        <!-- Opsi 2: Memuat CSS Kustom -->
        <link rel="stylesheet" href="{{ asset('vendors/feather/feather.css') }}">
        <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
        <link rel="stylesheet" href="{{ asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
        <link rel="stylesheet" href="{{ asset('vendors/ti-icons/css/themify-icons.css') }}">
        <link rel="stylesheet" href="{{ asset('css/vertical-layout-light/style.css') }}">
        <link rel="stylesheet" href="{{ asset('vendors/fontawesome/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('js/select.dataTables.min.css') }}">
    @endif

    <!-- Custom CSS -->
    @if(isset($css) && is_array($css))
        @foreach($css as $cssFile)
            <link rel="stylesheet" href="{{ asset('css/' . $cssFile) }}">
        @endforeach
    @endif

    <script>
        const BASEURL = "{{ url('/') }}";
    </script>
</head>
<body>

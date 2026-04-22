<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ $title ?? 'Dashboard' }} - Inventaris GKI Delima</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex,nofollow,noarchive" />
    <link rel="icon" href="{{ asset('icon.png') }}" type="image/x-icon" />

    <!-- Design System -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('css')
</head>

<body>
    <div class="app-layout">
        <!-- Sidebar -->
        @include('layouts._sidebar')

        <!-- Main Content -->
        <main class="app-content">
            <!-- Top Bar -->
            @include('layouts._navbar')

            <!-- Content -->
            <div class="content-wrapper">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- SweetAlert -->
    @include('sweetalert::alert')

    <!-- Delete confirmation -->
    <script>
        function deleteData(id) {
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>

    @stack('js')
</body>

</html>

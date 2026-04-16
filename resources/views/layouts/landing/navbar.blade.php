<!-- Navbar -->
<div class="w-full border-b border-dashed border-sky-800 bg-sky-900 p-5">
    <div class="container mx-auto">
        <div class="flex justify-between items-center">
            <a href="/" class="flex items-center gap-2">
                <img src="{{ asset('warehouse.png') }}" class="w-7 h-7 object-center object-cover" />
                <h1 class="text-white text-2xl font-semibold">Gudangku</h1>
            </a>
            <div class="flex gap-4 text-white">
                @guest
                    <a href="{{ route('login') }}" class="border px-2 py-1 rounded-lg font-medium hover:bg-sky-900">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="border px-2 py-1 rounded-lg font-medium hover:bg-sky-900">
                        Daftar
                    </a>
                @endguest
                @auth
                    <div class="hidden md:flex items-center gap-4">

                        @role('Admin|Super Admin')
                            <a href="{{ route('admin.dashboard') }}" class="rounded-lg border px-2 py-1">Dashboard</a>
                        @endrole
                        @role('Customer')
                            <a href="{{ route('customer.dashboard') }}" class="rounded-lg border px-2 py-1">Dashboard</a>
                        @endrole
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

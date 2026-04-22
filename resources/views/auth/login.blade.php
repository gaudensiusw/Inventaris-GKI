<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Login - Sistem Inventaris GKI Delima</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
    </style>
</head>

<body class="antialiased min-h-screen flex items-center justify-center p-4">

    <div class="max-w-5xl w-full flex flex-col md:flex-row items-center gap-10 md:gap-20">

        <!-- Left Side: Branding -->
        <div class="hidden md:flex flex-col flex-1 pl-4">
            <div
                class="flex items-center w-max bg-blue-500 rounded-full text-white text-sm font-semibold mb-6 overflow-hidden shadow-sm">
                <span class="bg-blue-600 px-4 py-2">GKI</span>
                <span class="px-4 py-2">GKI Delima</span>
            </div>

            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-2 leading-tight">
                Sistem Inventaris <br>
                <span class="text-blue-600">GKI Delima</span>
            </h1>

            <p class="text-gray-600 mb-10 max-w-md text-base leading-relaxed">
                Kelola aset gereja dengan mudah dan efisien menggunakan sistem inventaris modern dengan QR code scanner.
            </p>

            <div class="flex gap-4">
                <div
                    class="bg-white border hover:shadow-md transition duration-300 rounded-xl p-4 flex flex-col min-w-[140px] shadow-sm">
                    <span class="text-blue-600 font-bold text-2xl mb-1">500+</span>
                    <span class="text-xs text-gray-500 uppercase tracking-wide font-medium">Total Barang</span>
                </div>
                <div
                    class="bg-white border hover:shadow-md transition duration-300 rounded-xl p-4 flex flex-col min-w-[140px] shadow-sm">
                    <span class="text-green-600 font-bold text-2xl mb-1 flex items-center">
                        QR
                    </span>
                    <span class="text-xs text-gray-500 uppercase tracking-wide font-medium">Scan Ready</span>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full max-w-md">
            <div class="bg-white p-8 sm:p-10 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">

                <div class="flex justify-center mb-6">
                    <div class="bg-blue-50 text-blue-600 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2">
                            </path>
                            <path d="M20 12h-13l3 -3m0 6l-3 -3"></path>
                        </svg>
                    </div>
                </div>

                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Selamat Datang</h2>
                    <p class="text-gray-500 text-sm">Silakan login untuk melanjutkan</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="hidden" name="role_selector" id="role_input" value="admin">

                    @if(session('role_error'))
                        <div class="bg-red-50 border border-red-200 text-red-600 text-sm p-3 rounded-lg mb-4">
                            {{ session('role_error') }}
                        </div>
                    @endif

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 outline-none transition duration-200"
                            placeholder="Username">
                        @error('email')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 outline-none transition duration-200"
                            placeholder="Password">
                        @error('password')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-8">
                        <span class="block text-sm font-medium text-gray-700 mb-3">Pilih Role (Panduan Login)</span>
                        <div class="flex gap-3">
                            <div id="role-admin" onclick="selectRole('admin')"
                                class="flex-1 cursor-pointer rounded-lg border-2 border-red-200 bg-red-50 p-3 text-center transition-all flex flex-col items-center justify-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6 text-red-500"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path
                                        d="M12 3l8 4.5l0 8.5a12 12 0 0 1 -8 8.5a12 12 0 0 1 -8 -8.5l0 -8.5l8 -4.5">
                                    </path>
                                </svg>
                                <span class="text-xs font-semibold text-red-700">Admin</span>
                            </div>

                            <div id="role-user" onclick="selectRole('user')"
                                class="flex-1 cursor-pointer rounded-lg border-2 border-transparent bg-gray-50 p-3 text-center transition-all flex flex-col items-center justify-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6 text-gray-400"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                </svg>
                                <span class="text-xs font-semibold text-gray-600">User</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex justify-center items-center gap-2">
                        Login
                    </button>
                </form>
            </div>
        </div>

    </div>

    <script>
    function selectRole(role) {
        const adminEl = document.getElementById('role-admin');
        const userEl = document.getElementById('role-user');
        document.getElementById('role_input').value = role;

        if (role === 'admin') {
            adminEl.className = 'flex-1 cursor-pointer rounded-lg border-2 border-red-200 bg-red-50 p-3 text-center transition-all flex flex-col items-center justify-center gap-1';
            adminEl.querySelector('svg').className = 'w-6 h-6 text-red-500';
            adminEl.querySelector('span').className = 'text-xs font-semibold text-red-700';

            userEl.className = 'flex-1 cursor-pointer rounded-lg border-2 border-transparent bg-gray-50 p-3 text-center transition-all flex flex-col items-center justify-center gap-1';
            userEl.querySelector('svg').className = 'w-6 h-6 text-gray-400';
            userEl.querySelector('span').className = 'text-xs font-semibold text-gray-600';
        } else {
            userEl.className = 'flex-1 cursor-pointer rounded-lg border-2 border-blue-200 bg-blue-50 p-3 text-center transition-all flex flex-col items-center justify-center gap-1';
            userEl.querySelector('svg').className = 'w-6 h-6 text-blue-500';
            userEl.querySelector('span').className = 'text-xs font-semibold text-blue-700';

            adminEl.className = 'flex-1 cursor-pointer rounded-lg border-2 border-transparent bg-gray-50 p-3 text-center transition-all flex flex-col items-center justify-center gap-1';
            adminEl.querySelector('svg').className = 'w-6 h-6 text-gray-400';
            adminEl.querySelector('span').className = 'text-xs font-semibold text-gray-600';
        }
    }
    </script>

</body>

</html>
<aside class="navbar navbar-vertical navbar-expand-lg navbar-dark">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a href="/" class="navbar-brand navbar-brand-autodark">
            <h3 class="font-weight-bold">INVENTORY SYSTEM</h3>
        </a>
        <div class="navbar-nav flex-row d-lg-none">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-toggle="dropdown">
                    <span class="avatar" style="background-image: url({{ Auth::user()->avatar }})"></span>
                    <div class="d-none d-xl-block pl-2">
                        <div>{{ Auth::user()->name }}</div>
                        <div class="mt-1 small text-muted">{{ Auth::user()->name }}</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z"></path>
                            <path d="M7 6a7.75 7.75 0 1 0 10 0"></path>
                            <line x1="12" y1="4" x2="12" y2="12"></line>
                        </svg>
                        Logout
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </a>
                </div>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="navbar-menu">
            <ul class="navbar-nav pt-lg-3">
                <div class="hr-text hr-text-left ml-2 mb-2 mt-2">Dashboard</div>
                @role('Customer')
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('customer.dashboard') ? 'active' : '' }}"
                            href="{{ route('customer.dashboard') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block mr-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-apps"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <rect x="4" y="4" width="6" height="6" rx="1"></rect>
                                    <rect x="4" y="14" width="6" height="6" rx="1"></rect>
                                    <rect x="14" y="14" width="6" height="6" rx="1"></rect>
                                    <line x1="14" y1="7" x2="20" y2="7"></line>
                                    <line x1="17" y1="4" x2="17" y2="10"></line>
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Dashboard
                            </span>
                        </a>
                    </li>
                    <div class="hr-text hr-text-left ml-2 mb-2 mt-2">Menu</div>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('customer.order*') ? 'active' : '' }}"
                            href="{{ route('customer.order.index') }}">
                            <span class=" nav-link-icon d-md-none
                            d-lg-inline-block mr-1">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-shopping-cart-plus" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <circle cx="6" cy="19" r="2"></circle>
                                    <circle cx="17" cy="19" r="2"></circle>
                                    <path d="M17 17h-11v-14h-2"></path>
                                    <path d="M6 5l6.005 .429m7.138 6.573l-.143 .998h-13"></path>
                                    <path d="M15 6h6m-3 -3v6"></path>
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Permintaan Barang
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('customer.rent*') ? 'active' : '' }}"
                            href="{{ route('customer.rent.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block mr-1">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-clipboard-list" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path
                                        d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2">
                                    </path>
                                    <rect x="9" y="3" width="6" height="4" rx="2"></rect>
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Status Barang Pinjaman
                            </span>
                        </a>
                    </li>
                    <div class="hr-text hr-text-left ml-2 mb-2 mt-2">Konfigurasi</div>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.setting.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block mr-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path
                                        d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z">
                                    </path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Akun
                            </span>
                        </a>
                    </li>
                @endrole
                @hasanyrole('Admin|Super Admin')
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block mr-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-apps"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <rect x="4" y="4" width="6" height="6" rx="1">
                                    </rect>
                                    <rect x="4" y="14" width="6" height="6" rx="1">
                                    </rect>
                                    <rect x="14" y="14" width="6" height="6" rx="1">
                                    </rect>
                                    <line x1="14" y1="7" x2="20" y2="7"></line>
                                    <line x1="17" y1="4" x2="17" y2="10"></line>
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Dashboard
                            </span>
                        </a>
                    </li>
                    <div class="hr-text hr-text-left ml-2 mb-2 mt-2">Menu</div>
                    @can('index-category')
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.category*') ? 'active' : '' }}"
                                href="{{ route('admin.category.index') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block mr-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-copy"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <rect x="8" y="8" width="12" height="12" rx="2"></rect>
                                        <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2"></path>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Kategori
                                </span>
                            </a>
                        </li>
                    @endcan
                    @can('index-room')
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.room*') ? 'active' : '' }}"
                                href="{{ route('admin.room.index') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block mr-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-door" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 12v.01" /><path d="M3 21h18" /><path d="M6 21v-16a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v16" /></svg>
                                </span>
                                <span class="nav-link-title">
                                    Ruangan
                                </span>
                            </a>
                        </li>
                    @endcan
                    @can('index-item')
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.item*') ? 'active' : '' }}"
                                href="{{ route('admin.item.index') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block mr-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-box" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /><line x1="12" y1="12" x2="20" y2="7.5" /><line x1="12" y1="12" x2="12" y2="21" /><line x1="12" y1="12" x2="4" y2="7.5" /></svg>
                                </span>
                                <span class="nav-link-title">
                                    Aset (Items)
                                </span>
                            </a>
                        </li>
                    @endcan
                    <div class="hr-text hr-text-left ml-2 mb-2 mt-2">Peminjaman</div>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.order*') ? 'active' : '' }}"
                            href="{{ route('admin.order.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block mr-1">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-shopping-cart-plus" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <circle cx="6" cy="19" r="2"></circle>
                                    <circle cx="17" cy="19" r="2"></circle>
                                    <path d="M17 17h-11v-14h-2"></path>
                                    <path d="M6 5l6.005 .429m7.138 6.573l-.143 .998h-13"></path>
                                    <path d="M15 6h6m-3 -3v6"></path>
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Data Pengajuan
                            </span>
                        </a>
                    </li>
                    <div class="hr-text hr-text-left ml-2 mb-2 mt-2">User Manajemen</div>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.permission*') ? 'active' : '' }}"
                            href="{{ route('admin.permission.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block mr-1">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-user-exclamation" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                    <line x1="19" y1="7" x2="19" y2="10"></line>
                                    <line x1="19" y1="14" x2="19" y2="14.01"></line>
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Permission
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.role*') ? 'active' : '' }}"
                            href="{{ route('admin.role.index') }}">
                            <span class="   nav-link-icon d-md-none d-lg-inline-block mr-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-check"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                    <path d="M16 11l2 2l4 -4"></path>
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Role
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.user*') ? 'active' : '' }}"
                            href="{{ route('admin.user.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block mr-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-circle"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <circle cx="12" cy="10" r="3"></circle>
                                    <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855"></path>
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                User
                            </span>
                        </a>
                    </li>
                    <div class="hr-text hr-text-left ml-2 mb-2 mt-2">Konfigurasi</div>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.setting.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block mr-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path
                                        d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z">
                                    </path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Akun
                            </span>
                        </a>
                    </li>
                @endhasanyrole
            </ul>
        </div>
    </div>
</aside>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">

    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

        <aside class="flex-shrink-0 w-64 flex flex-col border-r border-gray-200 bg-white transition-all duration-300"
            :class="sidebarOpen ? 'translate-x-0 ease-out' :
                '-translate-x-full ease-in md:translate-x-0 md:static fixed z-30 h-full'">

            <div class="flex items-center justify-center h-16 bg-gray-900 text-white font-bold text-xl">
                ECO DASHBOARD
            </div>

            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1 px-2">
                    <li>
                        @if (Auth::user()->role === 'owner')
                            <a href="{{ route('dashboard') }}"
                                class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('dashboard') ? 'bg-gray-100 font-semibold' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                    </path>
                                </svg>
                                Dashboard
                            </a>
                        @endif
                    </li>

                    <li>
                        @if (Auth::user()->role === 'owner')
                            <a href="{{ route('products.index') }}"
                                class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md {{ request()->routeIs('products.*') ? 'bg-gray-100 font-semibold text-gray-900' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                Produk
                            </a>
                            <a href="{{ route('categories.index') }}"
                                class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md {{ request()->routeIs('categories.*') ? 'bg-gray-100 font-semibold text-gray-900' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                    </path>
                                </svg>
                                Kategori
                            </a>
                        @endif
                    </li>

                    <li>
                        <a href="{{ route('transactions.create') }}"
                            class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md {{ request()->routeIs('transactions.create') ? 'bg-gray-100 font-semibold text-gray-900' : '' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 17v-3m-3 3h.01M13 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Kasir (POS)
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('transactions.index') }}"
                            class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md {{ request()->routeIs('transactions.index') || request()->routeIs('transactions.show') ? 'bg-gray-100 font-semibold text-gray-900' : '' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            Pesanan Masuk
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('transactions.history') }}"
                            class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md {{ request()->routeIs('transactions.history') ? 'bg-gray-100 font-semibold text-gray-900' : '' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            Riwayat Transaksi
                        </a>
                    </li>

                    <li>
                        @if (Auth::user()->role === 'owner')
                            <a href="{{ route('users.index') }}"
                                class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md {{ request()->routeIs('users.*') ? 'bg-gray-100 font-semibold text-gray-900' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                                Staff / Users
                            </a>
                        @endif
                    </li>
                </ul>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">

            <header class="flex justify-between items-center py-4 px-6 bg-white border-b border-gray-200">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none md:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $header ?? 'Dashboard' }}
                </h2>

                <div class="flex items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                {{ $slot }}
            </main>
        </div>

        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            class="fixed inset-0 z-20 bg-black opacity-50 transition-opacity md:hidden"></div>
    </div>
</body>

</html>

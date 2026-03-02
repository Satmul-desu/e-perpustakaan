@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
            Selamat Datang di {{ config('app.name', 'Toko Online') }}
        </h1>
        <p class="text-xl text-gray-600 dark:text-gray-300 mb-8">
            Platform e-commerce modern untuk semua kebutuhan belanja Anda
        </p>

        <div class="grid md:grid-cols-3 gap-8 mb-12">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                <div class="text-3xl mb-4">🛍️</div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Beragam Produk</h3>
                <p class="text-gray-600 dark:text-gray-300">Temukan berbagai produk berkualitas dari penjual terpercaya</p>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                <div class="text-3xl mb-4">🚚</div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Pengiriman Cepat</h3>
                <p class="text-gray-600 dark:text-gray-300">Layanan pengiriman yang cepat dan aman ke seluruh Indonesia</p>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                <div class="text-3xl mb-4">💳</div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Pembayaran Aman</h3>
                <p class="text-gray-600 dark:text-gray-300">Berbagai metode pembayaran yang aman dan terpercaya</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @guest
                <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                    Daftar Sekarang
                </a>
                <a href="{{ route('login') }}" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                    Masuk
                </a>
            @else
                <a href="{{ url('/dashboard') }}" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                    Ke Dashboard
                </a>
            @endguest
        </div>
    </div>
</div>
@endsection

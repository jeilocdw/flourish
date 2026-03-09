@extends('layouts.master')

@section('title', 'Login - Flourish Supermarket')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-emerald-600 to-teal-800">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Flourish</h1>
            <p class="text-gray-500">Supermarket POS</p>
        </div>
        
        @yield('auth-content')
    </div>
</div>
@endsection

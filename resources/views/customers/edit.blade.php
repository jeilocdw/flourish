@extends('layouts.app')

@section('header', 'Edit Customer')

@section('main')
<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('customers.update', $customer) }}">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" value="{{ $customer->name }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ $customer->email }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
            <input type="text" name="phone" value="{{ $customer->phone }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
            <textarea name="address" rows="2" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">{{ $customer->address }}</textarea>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
            <input type="text" name="city" value="{{ $customer->city }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
            <input type="text" name="country" value="{{ $customer->country }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </div>
        
        <div class="mb-4">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" {{ $customer->is_active ? 'checked' : '' }} class="rounded text-emerald-600">
                <span class="text-sm text-gray-700">Active</span>
            </label>
        </div>
        
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Update</button>
            <a href="{{ route('customers.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Cancel</a>
        </div>
    </form>
</div>
@endsection

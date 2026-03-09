@extends('layouts.app')

@section('header', 'Units')

@section('main')
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b flex justify-between items-center">
        <form method="GET" class="flex gap-4">
            <input type="text" name="search" placeholder="Search units..." class="px-4 py-2 border rounded-lg" value="{{ request('search') }}">
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Filter</button>
        </form>
        <a href="{{ route('units.create') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Add Unit</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Short Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Base Unit</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($units as $unit)
                <tr>
                    <td class="px-4 py-3">{{ $unit->name }}</td>
                    <td class="px-4 py-3">{{ $unit->short_name }}</td>
                    <td class="px-4 py-3">{{ $unit->base_unit ? $unit->baseUnit?->short_name : '-' }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('units.edit', $unit) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                        <form method="POST" action="{{ route('units.destroy', $unit) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete this unit?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">No units found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(method_exists($units, 'links'))
    <div class="p-4 border-t">
        {{ $units->links() }}
    </div>
    @endif
</div>
@endsection

@extends('layouts.app')
@section('content')
    <div class="bg-white rounded-lg shadow-sm">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-semibold text-gray-800">NGO Followers</h1>
            <h3 class="text-sm text-gray-500 inline">List of all followers of this NGO</h3>
        </div>

        <!-- Table container -->
        <div class="overflow-x-auto" id="table-container">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-gray-800 font-semibold">S.N</th>
                        <th class="px-6 py-3 text-left text-gray-800 font-semibold">Follower Name</th>
                    </tr>
                </thead>
                <tbody id="followers-table-body" class="bg-white divide-y divide-gray-200">
                    @if($followers->isEmpty())
                        <tr>
                            <td colspan="2" class="px-6 py-8 text-center text-gray-500">No followers found.</td>
                        </tr>
                    @else
                        @foreach($followers as $follower)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $follower->name }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
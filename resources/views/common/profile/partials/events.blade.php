@if ($events->isEmpty())
    <div class="bg-white rounded-2xl shadow-md p-8 text-center">
        <span class="iconify text-4xl text-gray-300 mx-auto block mb-4" data-icon="fluent:calendar-20-filled"
            data-width="40" data-height="40"></span>
        <h3 class="text-xl font-bold text-gray-900 mb-2">No Events</h3>
        <p class="text-gray-500">This organization hasn't created any events yet.</p>
    </div>
@else
    <div class="space-y-4">
        @foreach ($events as $event)
            <div class="flex gap-4 rounded-lg border border-gray-100 bg-white p-4 shadow-sm">
                <div class="w-28 h-20 overflow-hidden rounded-md flex-shrink-0 bg-gray-100">
                    @if ($event->cover_image_path_name)
                        <img src="{{ asset('storage/' . $event->cover_image_path_name) }}" alt="{{ $event->title }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <span class="iconify" data-icon="fluent:calendar-20-filled" data-width="28"
                                data-height="28"></span>
                        </div>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <h3 class="text-md font-semibold text-gray-900 truncate">{{ $event->title }}</h3>
                    <p class="text-sm text-gray-500 mt-1 truncate">{{ $event->location }}</p>
                    <div class="mt-2 flex items-center gap-2 text-sm text-gray-500">
                        <span>{{ $event->start_date ? $event->start_date->format('M d, Y') : '' }}</span>
                        <a href="{{ route('ngo.event.details', $event->id) }}"
                            class="ml-auto text-red-500 font-semibold text-sm">View</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

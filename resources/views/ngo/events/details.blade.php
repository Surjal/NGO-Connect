@extends('layouts.app')
@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('ngo.events') }}"
                    class="inline-flex items-center text-red-500 hover:text-red-700 font-semibold transition-colors">
                    <span class="iconify inline-block mr-2" data-icon="fluent:arrow-left-20-filled" data-width="20"
                        data-height="20"></span>
                    Back to Events
                </a>
            </div>
            <div class="bg-white rounded-sm overflow-hidden shadow-xl">
                <div class="relative w-full h-96 overflow-hidden bg-gradient-to-br from-red-100 to-red-50">
                    @if ($event->cover_image_path_name)
                        <img src="{{ asset('storage/' . $event->cover_image_path_name) }}" alt="{{ $event->title }}"
                            class="w-full h-full object-cover">
                    @else
                        <div
                            class="w-full h-full flex items-center justify-center bg-gradient-to-br from-red-500 to-red-500">
                            <span class="iconify text-white" data-icon="fluent:calendar-20-filled" data-width="128"
                                data-height="128"></span>
                        </div>
                    @endif
                    <div class="absolute top-6 right-6">
                        <span
                            class="inline-block px-4 py-2 bg-white rounded-full text-sm font-bold {{ $event->type == 0 ? 'text-red-700 bg-red-100' : 'text-gray-700 bg-gray-100' }}">
                            {{ $event->type == 0 ? 'Online Event' : 'Offline Event' }}
                        </span>
                    </div>
                    <div class="absolute top-6 left-6">
                        @php
                            $statusClass = '';
                            $statusText = strtoupper($event->status ?? 'UNKNOWN');

                            switch ($event->status ?? 'unknown') {
                                case 'upcoming':
                                    $statusClass = 'bg-red-100 text-red-800 border-red-300';
                                    break;
                                case 'live':
                                    $statusClass = 'bg-green-100 text-green-800 border-green-300';
                                    break;
                                case 'completed':
                                    $statusClass = 'bg-gray-100 text-gray-800 border-gray-300';
                                    break;
                                default:
                                    $statusClass = 'bg-yellow-100 text-yellow-800 border-yellow-300';
                            }
                        @endphp

                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusClass }}">
                            {{ $statusText }}
                        </span>
                    </div>

                </div>
                <div class="p-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $event->title }}</h1>
                    <p class="w-full text-lg text-gray-600 mb-8 whitespace-pre-wrap break-words">{{ $event->description }}
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 pb-8 border-b border-gray-200">
                        <div class="space-y-6">
                            <div>
                                <div class="flex items-center space-x-3 mb-4">
                                    <h3 class="text-lg font-bold text-gray-900">Start Date & Time</h3>
                                </div>
                                <div class="p-4 rounded-lg border border-red-400">
                                    <p class="text-2xl font-bold text-gray-900">{{ $event->start_date->format('M d, Y') }}
                                    </p>
                                    <p class="text-lg text-red-500 font-semibold mt-1">
                                        {{ $event->start_date->format('h:i A') }}</p>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center space-x-3 mb-4">
                                    <h3 class="text-lg font-bold text-gray-900">End Date & Time</h3>
                                </div>
                                <div class="p-4 rounded-lg border border-red-400">
                                    <p class="text-2xl font-bold text-gray-900">{{ $event->end_date->format('M d, Y') }}
                                    </p>
                                    <p class="text-lg text-red-500 font-semibold mt-1">
                                        {{ $event->end_date->format('h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <div class="flex items-center space-x-3 mb-4">
                                    <h3 class="text-lg font-bold text-gray-900">Location</h3>
                                </div>
                                <div class="p-4 rounded-lg border border-red-400">
                                    <p class="text-lg font-semibold text-gray-900">{{ $event->location }}</p>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center space-x-3 mb-4">
                                    <h3 class="text-lg font-bold text-gray-900">Volunteer Capacity</h3>
                                </div>
                                <div class="p-4 rounded-lg border border-red-400">
                                    <p class="text-2xl font-bold text-red-500">{{ $event->volunteers()->count() }} <span
                                            class="text-gray-600 text-lg">/ {{ $event->capacity }}</span></p>
                                    <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-red-500 h-2 rounded-full transition-all duration-300"
                                            style="width: {{ $event->capacity != 0 ? ($event->volunteers()->count() / $event->capacity) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance & QR Check-in Section -->
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <div class="flex flex-col md:flex-row gap-8 items-start">
                            <div class="flex-1 space-y-4">
                                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                    <span class="iconify text-red-600" data-icon="fluent:qr-code-20-filled"></span>
                                    Attendance & Check-in
                                </h3>
                                <p class="text-gray-600 text-sm">
                                    Use the QR code below to verify volunteer attendance on-site. When volunteers scan this code, their presence will be recorded and verified against your volunteer list.
                                </p>
                                <div class="bg-red-50 border border-red-100 p-4 rounded-xl flex items-start gap-3">
                                    <span class="iconify text-red-500 mt-0.5" data-icon="fluent:info-20-filled"></span>
                                    <p class="text-[11px] text-red-700 font-medium">
                                        Only accepted volunteers will be able to check in successfully. This ensures that only authorized participants receive impact points and certificates.
                                    </p>
                                </div>
                            </div>

                            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col items-center gap-4">
                                @if($event->check_in_token)
                                    <div class="p-4 bg-white rounded-2xl border-2 border-dashed border-red-100 relative group">
                                        {{-- Use CDN QR Generator as fallback --}}
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('people.event.checkin', $event->check_in_token)) }}" 
                                             alt="Check-in QR Code" 
                                             class="w-40 h-40">
                                    </div>
                                    <div class="text-center">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Event Token</p>
                                        <p class="text-xs font-mono text-gray-600 truncate w-32">{{ substr($event->check_in_token, 0, 8) }}...</p>
                                    </div>
                                @else
                                    <div class="p-4 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center gap-2">
                                        <span class="iconify text-gray-300 text-3xl" data-icon="fluent:qr-code-20-regular"></span>
                                        <p class="text-[9px] text-gray-400 font-bold text-center">Token Missing</p>
                                    </div>
                                    <p class="text-[10px] text-red-500 font-bold text-center leading-tight">Please Edit & Save the event<br>to regenerate the QR code.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        @if ($event->category)
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                                    <span class="iconify mr-2 text-red-600" data-icon="fluent:tag-20-filled" data-width="20"
                                        data-height="20"></span>
                                    Category
                                </h3>
                                <p class="text-gray-700 ml-8 text-base">{{ $event->category }}</p>
                            </div>
                        @endif

                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                                <span class="iconify mr-2 text-red-600" data-icon="fluent:checkmark-circle-20-filled"
                                    data-width="20" data-height="20"></span>
                                Requirements
                            </h3>
                            <p class="text-gray-700 ml-8 text-base whitespace-pre-line">{{ $event->requirements }}</p>
                        </div>
                    </div>

                    <!-- Project Milestones & Transparency Section -->
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                <span class="iconify text-orange-500" data-icon="fluent:board-20-filled"></span>
                                Project Milestones
                            </h3>
                            <button onclick="document.getElementById('addMilestoneModal').classList.remove('hidden')" 
                                    class="text-xs font-black text-red-600 uppercase tracking-widest hover:text-red-700 transition-colors flex items-center gap-1">
                                <span class="iconify" data-icon="fluent:add-circle-20-filled"></span>
                                Add Milestone
                            </button>
                        </div>

                        @if($event->milestones->count() > 0)
                            <div class="space-y-4">
                                @foreach($event->milestones as $milestone)
                                    <div class="p-5 rounded-2xl border border-gray-100 bg-gray-50/30 hover:bg-white hover:shadow-sm transition-all group">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3">
                                                    <span class="w-6 h-6 rounded-full bg-white border border-gray-200 flex items-center justify-center text-[10px] font-black text-gray-400">
                                                        {{ $loop->iteration }}
                                                    </span>
                                                    <h4 class="text-sm font-bold text-gray-900">{{ $milestone->title }}</h4>
                                                    @php
                                                        $statusMeta = [
                                                            'pending' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'label' => 'Pending'],
                                                            'in_progress' => ['bg' => 'bg-red-100', 'text' => 'text-red-600', 'label' => 'In Progress'],
                                                            'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'label' => 'Completed']
                                                        ];
                                                        $meta = $statusMeta[$milestone->status] ?? $statusMeta['pending'];
                                                    @endphp
                                                    <span class="px-2 py-0.5 {{ $meta['bg'] }} {{ $meta['text'] }} rounded-full text-[9px] font-black uppercase tracking-wider">
                                                        {{ $meta['label'] }}
                                                    </span>
                                                </div>
                                                @if($milestone->description)
                                                    <p class="text-xs text-gray-500 mt-2 ml-9 leading-relaxed">{{ $milestone->description }}</p>
                                                @endif
                                            </div>

                                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <form action="{{ route('ngo.milestones.update', $milestone->id) }}" method="POST" class="flex gap-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" onchange="this.form.submit()" class="text-[10px] h-8 rounded-lg border-gray-200 bg-white font-bold uppercase tracking-tight py-1">
                                                        <option value="pending" {{ $milestone->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="in_progress" {{ $milestone->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                        <option value="completed" {{ $milestone->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                    </select>
                                                </form>
                                                <form action="{{ route('ngo.milestones.delete', $milestone->id) }}" method="POST" onsubmit="return confirm('Delete milestone?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-100 transition-colors">
                                                        <span class="iconify" data-icon="fluent:delete-20-regular"></span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50/50 border border-dashed border-gray-100 rounded-3xl p-8 text-center">
                                <span class="iconify text-3xl text-gray-200 mx-auto mb-3" data-icon="fluent:board-20-regular"></span>
                                <p class="text-sm text-gray-400 font-bold mb-4">No milestones defined yet.</p>
                                <button onclick="document.getElementById('addMilestoneModal').classList.remove('hidden')" 
                                        class="px-5 py-2 bg-white border border-gray-200 text-gray-600 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-gray-50 transition-all">
                                    Define First Phase
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Milestone Modal -->
                    <div id="addMilestoneModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="document.getElementById('addMilestoneModal').classList.add('hidden')"></div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                            <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                                <form action="{{ route('ngo.milestones.store', $event->id) }}" method="POST" class="p-8">
                                    @csrf
                                    <div class="mb-6">
                                        <h3 class="text-xl font-black text-gray-900 mb-2">Add Project Milestone</h3>
                                        <p class="text-sm text-gray-500 font-medium">Define a clear phase for your impact tracking.</p>
                                    </div>
                                    <div class="space-y-5">
                                        <div>
                                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Milestone Title</label>
                                            <input type="text" name="title" required placeholder="e.g., Procurement of Materials" 
                                                   class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all font-bold text-gray-800 placeholder-gray-300">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Detail (Optional)</label>
                                            <textarea name="description" rows="3" placeholder="Briefly describe what this phase involves..." 
                                                      class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all font-medium text-gray-800 placeholder-gray-300"></textarea>
                                        </div>
                                    </div>
                                    <div class="mt-8 flex gap-3">
                                        <button type="button" onclick="document.getElementById('addMilestoneModal').classList.add('hidden')"
                                                class="flex-1 px-6 py-4 bg-gray-50 text-gray-400 font-bold rounded-2xl hover:bg-gray-100 transition-colors">Cancel</button>
                                        <button type="submit" 
                                                class="flex-1 px-6 py-4 bg-red-600 text-white font-bold rounded-2xl shadow-lg shadow-red-200 hover:bg-red-700 transition-all transform active:scale-95">Add Milestone</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @if ($event->timing !== 'old')
                        <div class="mt-5 pt-8 border-t border-gray-200 flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('ngo.event.edit', $event->id) }}"
                                class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 transition-colors duration-200 shadow-md hover:shadow-lg">
                                <span class="iconify mr-2" data-icon="fluent:edit-20-filled" data-width="20"
                                    data-height="20"></span>
                                Edit Event
                            </a>
                            <button type="button"
                                onclick="if(confirm('Are you sure you want to delete this event?')) { document.getElementById('deleteForm').submit(); }"
                                class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 shadow-md hover:shadow-lg">
                                <span class="iconify mr-2" data-icon="fluent:delete-20-filled" data-width="20"
                                    data-height="20"></span>
                                Delete Event
                            </button>
                            <a href="{{ route('ngo.events') }}"
                                class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition-colors duration-200 shadow-md hover:shadow-lg">
                                <span class="iconify mr-2" data-icon="fluent:arrow-left-20-filled" data-width="20"
                                    data-height="20"></span>
                                Back to List
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            <form id="deleteForm" action="{{ route('ngo.event.delete', $event->id) }}" method="POST"
                style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
@endsection

@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="glass-panel overflow-hidden">
            <!-- Header -->
            <div class="px-8 py-6 border-b border-white/20 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
                    <p class="text-sm text-gray-500 mt-1">View and manage registered users</p>
                </div>
                <div>
                    <a href="{{ route('admin.user.register') }}"
                        class="btn-primary py-2.5 px-5 text-sm flex items-center gap-2">
                        <i class="fas fa-user-plus"></i> Add User
                    </a>
                </div>
            </div>

            <!-- Search -->
            <div class="px-8 py-6 bg-white/30 backdrop-blur-sm border-b border-white/20">
                <div class="relative max-w-md">
                    <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                    <input type="text" id="search-user-name" placeholder="Search By User Name..."
                        class="input-premium pl-10 w-full">
                </div>
            </div>

            <!-- Table container -->
            <div class="overflow-x-auto" id="table-container">
                <table class="w-full">
                    <thead class="bg-gray-50/50 border-b border-white/20">
                        <tr>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">User
                            </th>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email
                            </th>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status
                            </th>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Joined
                            </th>
                            <th class="px-8 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody id="user-table-body" class="divide-y divide-gray-100">
                        <!-- Filled by JS -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-8 py-6 border-t border-white/20 bg-gray-50/30 flex items-center justify-between"
                id="pagination-container">
                <!-- Filled by JS -->
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function() {
            const route = '?user';
            let timeout;

            $('#search-user-name').on('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => fetchUsers(1), 350);
            });

            // ----- PAGINATION CLICK -----
            $(document).on('click', '#pagination-container a', function(e) {
                e.preventDefault();
                const url = new URL($(this).attr('href'));
                const page = url.searchParams.get('page') || 1;
                fetchUsers(page);
            });

            // ----- MAIN FETCH -----
            function fetchUsers(page = 1) {
                const params = {
                    page: page,
                    name: $('#search-user-name').val().trim(),
                };

                $.get(route, params, function(res) {
                    renderTable(res.users, res.current_page);
                    renderPagination(res.links, res.current_page);
                }).fail(() => alert('Error loading users'));
            }

            // ----- RENDER TABLE -----
            function renderTable(users, page) {
                const tbody = $('#user-table-body').empty();

                if (users.length === 0) {
                    tbody.append(`
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-search text-4xl text-gray-300 mb-3"></i>
                                    <p class="font-medium">No users found.</p>
                                </div>
                            </td>
                        </tr>
                    `);
                    return;
                }

                users.forEach((user, i) => {
                    const sn = (page - 1) * 10 + i + 1;
                    var routeUserDetail = "{{ route('admin.user.show', ':id') }}";
                    routeUserDetail = routeUserDetail.replace(':id', user.id);

                    const statusBadge = user.suspended ?
                        `<span class="px-2 py-1 rounded-md bg-red-50 text-red-600 text-xs font-bold border border-red-100">Suspended</span>` :
                        `<span class="px-2 py-1 rounded-md bg-green-50 text-green-600 text-xs font-bold border border-green-100">Active</span>`;

                    // Format date logic for JS (basic)
                    const date = new Date(user.created_at);
                    const dateStr = date.toLocaleDateString('en-US', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric'
                    });

                    const row = `
                        <tr class="hover:bg-red-50/30 transition-colors group">
                            <td class="px-8 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-bold text-sm mr-4 overflow-hidden">
                                        ${user.profile_photo 
                                            ? `<img src="/storage/${user.profile_photo}" class="w-full h-full object-cover">` 
                                            : user.name.charAt(0).toUpperCase()}
                                    </div>
                                    <div class="text-sm font-bold text-gray-900">${escapeHtml(user.name)}</div>
                                </div>
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap text-sm text-gray-600">${escapeHtml(user.email)}</td>
                             <td class="px-8 py-5 whitespace-nowrap">
                                ${statusBadge}
                            </td>
                             <td class="px-8 py-5 whitespace-nowrap text-sm text-gray-500">
                                ${dateStr}
                            </td>
                             <td class="px-8 py-5 whitespace-nowrap text-right text-sm font-medium">
                                <a href="${routeUserDetail}" class="text-gray-400 hover:text-red-600 transition-colors">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                });
            }

            // ----- RENDER PAGINATION -----
            function renderPagination(links, current) {
                const container = $('#pagination-container').empty();

                if (links.length > 3) {
                    let html = '<div class="flex items-center gap-1">';
                    links.forEach(link => {
                        const active = link.label == current;
                        const activeClass = active ? 'bg-red-600 text-white shadow-md shadow-red-200' :
                            'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200';
                        const disabled = !link.url ? 'opacity-50 cursor-not-allowed' : '';

                        let label = link.label;
                        if (label.includes('Previous')) label = '<i class="fas fa-chevron-left"></i>';
                        if (label.includes('Next')) label = '<i class="fas fa-chevron-right"></i>';

                        html += link.url ?
                            `<a href="${link.url}" class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-all ${activeClass} ${disabled}">${label}</a>` :
                            `<span class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold bg-transparent text-gray-400 ${disabled}">${label}</span>`;
                    });
                    html += '</div>';
                    container.append(html);
                }
            }

            // ----- HELPERS -----
            function escapeHtml(text) {
                if (!text) return '';
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // ----- INITIAL LOAD -----
            fetchUsers();
        });
    </script>
@endsection

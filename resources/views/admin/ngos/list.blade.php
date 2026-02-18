@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="glass-panel overflow-hidden">
            <!-- Header -->
            <div class="px-8 py-6 border-b border-white/20 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">NGO Management</h1>
                    <p class="text-sm text-gray-500 mt-1">View and manage registered organizations</p>
                </div>
                <div>
                    <a href="{{ route('register.ngo') }}" class="btn-primary py-2.5 px-5 text-sm flex items-center gap-2">
                        <i class="fas fa-plus"></i> Add NGO
                    </a>
                </div>
            </div>

            <!-- Search -->
            <div class="px-8 py-6 bg-white/30 backdrop-blur-sm border-b border-white/20">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="relative">
                        <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                        <input type="text" id="search-ngo-name" placeholder="Search Name..."
                            class="input-premium pl-10 w-full">
                    </div>
                    <div class="relative">
                        <i class="fas fa-tag absolute left-4 top-3.5 text-gray-400"></i>
                         <input type="text" id="search-category" placeholder="Category..."
                            class="input-premium pl-10 w-full">
                    </div>
                    <div class="relative">
                        <i class="fas fa-map-marker-alt absolute left-4 top-3.5 text-gray-400"></i>
                        <input type="text" id="search-address" placeholder="Address..."
                            class="input-premium pl-10 w-full">
                    </div>
                    <div class="relative">
                        <i class="fas fa-id-card absolute left-4 top-3.5 text-gray-400"></i>
                        <input type="text" id="search-registration" placeholder="Reg. No..."
                            class="input-premium pl-10 w-full">
                    </div>
                </div>
            </div>

            <!-- Table container -->
            <div class="overflow-x-auto" id="table-container">
                <table class="w-full">
                    <thead class="bg-gray-50/50 border-b border-white/20">
                        <tr>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">NGO Name</th>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Address</th>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Reg. No</th>
                             <th class="px-8 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="ngo-table-body" class="divide-y divide-gray-100">
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
            const route = '?ngo';
            let timeout;

            // ----- INPUT SEARCH (debounced) -----
            $('#search-ngo-name, #search-category, #search-address, #search-registration').on('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => fetchNgos(1), 350);
            });

            // ----- PAGINATION CLICK -----
            $(document).on('click', '#pagination-container a', function(e) {
                e.preventDefault();
                const url = new URL($(this).attr('href'));
                const page = url.searchParams.get('page') || 1;
                fetchNgos(page);
            });

            // ----- MAIN FETCH -----
            function fetchNgos(page = 1) {
                const params = {
                    page: page,
                    name: $('#search-ngo-name').val().trim(),
                    category: $('#search-category').val().trim(),
                    address: $('#search-address').val().trim(),
                    registration: $('#search-registration').val().trim(),
                };

                $.get(route, params, function(res) {
                    renderTable(res.ngos, res.current_page);
                    renderPagination(res.links, res.current_page);
                }).fail(() => alert('Error loading NGOs'));
            }

            // ----- RENDER TABLE -----
            function renderTable(ngos, page) {
                const tbody = $('#ngo-table-body').empty();

                if (ngos.length === 0) {
                    tbody.append(`
                <tr>
                    <td colspan="5" class="px-8 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-search text-4xl text-gray-300 mb-3"></i>
                            <p class="font-medium">No NGOs found matching criteria.</p>
                        </div>
                    </td>
                </tr>
            `);
                    return;
                }

                ngos.forEach((ngo, i) => {
                    const sn = (page - 1) * 10 + i + 1;
                    var routengodetail = "{{ route('admin.ngos.show', ':id') }}";
                    routengodetail = routengodetail.replace(':id', ngo.id);
                    
                    // Safe access to nested properties
                    const category = ngo.ngo && ngo.ngo.category ? ngo.ngo.category : 'N/A';
                    const address = ngo.ngo && ngo.ngo.address ? ngo.ngo.address : 'N/A';
                    const regNo = ngo.ngo && ngo.ngo.registration_number ? ngo.ngo.registration_number : 'N/A';

                    const row = `
                <tr class="hover:bg-red-50/30 transition-colors group">
                    <td class="px-8 py-5 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-bold text-sm mr-4">
                                ${ngo.name.charAt(0).toUpperCase()}
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900">${escapeHtml(ngo.name)}</div>
                                <div class="text-xs text-gray-500">${escapeHtml(ngo.email)}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5 whitespace-nowrap">
                         <span class="px-3 py-1 rounded-full bg-red-50 text-red-600 text-xs font-bold border border-red-100">
                            ${escapeHtml(category)}
                         </span>
                    </td>
                    <td class="px-8 py-5 whitespace-nowrap text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt text-gray-300 mr-1"></i> ${escapeHtml(address)}
                    </td>
                    <td class="px-8 py-5 whitespace-nowrap text-sm font-mono text-gray-500">
                        ${escapeHtml(regNo)}
                    </td>
                     <td class="px-8 py-5 whitespace-nowrap text-right text-sm font-medium">
                        <a href="${routengodetail}" class="text-gray-400 hover:text-red-600 transition-colors">
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
                
                 if (links.length > 3) { // Only show if we have pages
                    let html = '<div class="flex items-center gap-1">';
                    links.forEach(link => {
                        const active = link.label == current; // Note: loose comparison because link.label might be string
                        const activeClass = active ? 'bg-red-600 text-white shadow-md shadow-red-200' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200';
                        const disabled = !link.url ? 'opacity-50 cursor-not-allowed' : '';
                        
                        // Clean label (remove &laquo; etc)
                        let label = link.label;
                        if(label.includes('Previous')) label = '<i class="fas fa-chevron-left"></i>';
                        if(label.includes('Next')) label = '<i class="fas fa-chevron-right"></i>';

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
            fetchNgos();
        });
    </script>
@endsection

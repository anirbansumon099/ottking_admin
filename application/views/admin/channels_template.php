<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight">Channels Management</h1>
            <p class="text-xs text-slate-400 mt-1">Manage and organize your IPTV channels</p>
        </div>
        <a href="<?= base_url('admin/channels/add'); ?>" class="btn btn-gradient px-4 py-2 rounded-lg text-sm font-semibold text-white flex items-center gap-2 hover:shadow-lg transition-all">
            <i class="fa-solid fa-plus"></i>
            Add New Channel
        </a>
    </div>

    <!-- Search & Filter Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <input type="text" placeholder="Search channels..." class="col-span-1 md:col-span-2 form-control bg-slate-900/50 border-white/10 text-white placeholder-slate-500 rounded-lg py-2 px-4" id="searchInput">
        <select class="form-select bg-slate-900/50 border-white/10 text-slate-300 rounded-lg py-2 px-4" id="filterSelect">
            <option value="">All Categories</option>
            <option value="sports">Sports</option>
            <option value="movies">Movies</option>
            <option value="entertainment">Entertainment</option>
        </select>
    </div>

    <!-- Channels Grid/Table -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        
        <!-- Sample Channel Card -->
        <div class="glass-card border border-white/10 rounded-lg overflow-hidden hover:border-indigo-500/30 transition-all duration-300 group">
            <div class="h-32 bg-gradient-to-br from-indigo-600/20 to-purple-600/20 relative overflow-hidden">
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="fa-solid fa-tv text-indigo-400/50 text-4xl"></i>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div>
                    <h3 class="font-bold text-white text-sm truncate">Sports Channel</h3>
                    <p class="text-xs text-slate-400">Sports & Live Events</p>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-white/5">
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-emerald-500/10 text-emerald-400">Active</span>
                    <div class="flex gap-2">
                        <button class="p-1.5 text-slate-400 hover:text-white hover:bg-white/5 rounded transition-all" title="Edit">
                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                        </button>
                        <button class="p-1.5 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded transition-all" title="Delete">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sample Channel Card 2 -->
        <div class="glass-card border border-white/10 rounded-lg overflow-hidden hover:border-purple-500/30 transition-all duration-300 group">
            <div class="h-32 bg-gradient-to-br from-purple-600/20 to-pink-600/20 relative overflow-hidden">
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="fa-solid fa-film text-purple-400/50 text-4xl"></i>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div>
                    <h3 class="font-bold text-white text-sm truncate">Movies Channel</h3>
                    <p class="text-xs text-slate-400">Hollywood & Bollywood</p>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-white/5">
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-emerald-500/10 text-emerald-400">Active</span>
                    <div class="flex gap-2">
                        <button class="p-1.5 text-slate-400 hover:text-white hover:bg-white/5 rounded transition-all" title="Edit">
                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                        </button>
                        <button class="p-1.5 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded transition-all" title="Delete">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sample Channel Card 3 -->
        <div class="glass-card border border-white/10 rounded-lg overflow-hidden hover:border-amber-500/30 transition-all duration-300 group">
            <div class="h-32 bg-gradient-to-br from-amber-600/20 to-orange-600/20 relative overflow-hidden">
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="fa-solid fa-music text-amber-400/50 text-4xl"></i>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div>
                    <h3 class="font-bold text-white text-sm truncate">Music Channel</h3>
                    <p class="text-xs text-slate-400">Music & Videos</p>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-white/5">
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-yellow-500/10 text-yellow-400">Inactive</span>
                    <div class="flex gap-2">
                        <button class="p-1.5 text-slate-400 hover:text-white hover:bg-white/5 rounded transition-all" title="Edit">
                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                        </button>
                        <button class="p-1.5 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded transition-all" title="Delete">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Responsive Table for larger screens -->
    <div class="hidden lg:block glass-card border border-white/10 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-900/50 border-b border-white/5">
                    <tr class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-3">Channel Name</th>
                        <th class="px-6 py-3">Category</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Subscribers</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-3 font-medium text-white">HBO MAX</td>
                        <td class="px-6 py-3 text-slate-400">Movies</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400">Active</span>
                        </td>
                        <td class="px-6 py-3 text-slate-400">1,234</td>
                        <td class="px-6 py-3">
                            <div class="flex gap-2">
                                <a href="<?= base_url('admin/channels/edit/1'); ?>" class="text-indigo-400 hover:text-indigo-300">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button class="text-red-400 hover:text-red-300">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation" class="flex justify-center">
        <ul class="inline-flex gap-1">
            <li><button class="px-3 py-2 rounded text-xs font-semibold bg-white/10 text-white hover:bg-white/20">Previous</button></li>
            <li><button class="px-3 py-2 rounded text-xs font-semibold bg-indigo-600 text-white">1</button></li>
            <li><button class="px-3 py-2 rounded text-xs font-semibold bg-white/10 text-white hover:bg-white/20">2</button></li>
            <li><button class="px-3 py-2 rounded text-xs font-semibold bg-white/10 text-white hover:bg-white/20">Next</button></li>
        </ul>
    </nav>

</div>

<script>
// Search functionality
document.getElementById('searchInput')?.addEventListener('keyup', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    console.log('Searching for:', searchTerm);
    // Add your search logic here
});
</script>

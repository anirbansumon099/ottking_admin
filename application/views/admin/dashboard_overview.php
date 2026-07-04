<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight">System Overview</h1>
            <p class="text-xs text-slate-400 mt-1">Welcome back, <span class="text-indigo-400 font-semibold"><?= $this->session->userdata('username'); ?></span>. Here is what's happening with oTtking today.</p>
        </div>
        <div class="flex items-center gap-2.5 bg-slate-900/50 backdrop-blur-md border border-white/5 px-4 py-2 rounded-xl text-xs font-semibold text-slate-300">
            <i class="fa-solid fa-calendar-days text-indigo-400"></i>
            <span><?= date('d M, Y'); ?></span>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="glass-card p-5 border border-white/5 bg-slate-900/40 rounded-2xl relative overflow-hidden group hover:border-indigo-500/20 transition-all duration-300">
            <div class="flex justify-between items-start">
                <div class="space-y-2">
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Live Channels</p>
                    <h3 class="text-2xl font-black text-white">142</h3>
                </div>
                <div class="w-10 h-10 bg-indigo-500/10 border border-indigo-500/20 rounded-xl flex items-center justify-center text-indigo-400 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-tv text-sm"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1.5 text-[11px] text-emerald-400 font-semibold">
                <i class="fa-solid fa-arrow-trend-up"></i>
                <span>+5 added this week</span>
            </div>
        </div>

        <div class="glass-card p-5 border border-white/5 bg-slate-900/40 rounded-2xl relative overflow-hidden group hover:border-purple-500/20 transition-all duration-300">
            <div class="flex justify-between items-start">
                <div class="space-y-2">
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Users</p>
                    <h3 class="text-2xl font-black text-white">1,248</h3>
                </div>
                <div class="w-10 h-10 bg-purple-500/10 border border-purple-500/20 rounded-xl flex items-center justify-center text-purple-400 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-users text-sm"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1.5 text-[11px] text-emerald-400 font-semibold">
                <i class="fa-solid fa-arrow-trend-up"></i>
                <span>+12% device registration</span>
            </div>
        </div>

        <div class="glass-card p-5 border border-white/5 bg-slate-900/40 rounded-2xl relative overflow-hidden group hover:border-amber-500/20 transition-all duration-300">
            <div class="flex justify-between items-start">
                <div class="space-y-2">
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Premium Subs</p>
                    <h3 class="text-2xl font-black text-white">842</h3>
                </div>
                <div class="w-10 h-10 bg-amber-500/10 border border-amber-500/20 rounded-xl flex items-center justify-center text-amber-400 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-crown text-sm"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1.5 text-[11px] text-amber-400 font-semibold">
                <i class="fa-solid fa-bolt"></i>
                <span>68 active packages</span>
            </div>
        </div>

        <div class="glass-card p-5 border border-white/5 bg-slate-900/40 rounded-2xl relative overflow-hidden group hover:border-emerald-500/20 transition-all duration-300">
            <div class="flex justify-between items-start">
                <div class="space-y-2">
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Revenue</p>
                    <h3 class="text-2xl font-black text-white">$3,850</h3>
                </div>
                <div class="w-10 h-10 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-400 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-credit-card text-sm"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1.5 text-[11px] text-emerald-400 font-semibold">
                <i class="fa-solid fa-circle-check"></i>
                <span>Gateway fully operational</span>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-slate-900/30 backdrop-blur-xl border border-white/5 rounded-2xl p-5 flex flex-col">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">Recent Transactions</h2>
                <a href="<?= base_url('admin/billing/transactions'); ?>" class="text-[11px] font-bold text-indigo-400 hover:text-indigo-300 transition-colors text-decoration-none flex items-center gap-1">
                    View All <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </a>
            </div>
            <div class="overflow-x-auto no-scrollbar flex-1">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-white/5 text-[10px] font-bold uppercase text-slate-500 tracking-wider">
                            <th class="pb-3 pl-2">User / ID</th>
                            <th class="pb-3">Package</th>
                            <th class="pb-3">Amount</th>
                            <th class="pb-3 pr-2 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-xs">
                        <tr class="text-slate-300 hover:bg-white/5 transition-colors group">
                            <td class="py-3 pl-2">
                                <div class="font-semibold text-slate-200 group-hover:text-white">Dipto Deb</div>
                                <span class="text-[10px] text-slate-500">TXN-902817</span>
                            </td>
                            <td class="py-3 text-slate-400">Premium 1 Month</td>
                            <td class="py-3 font-bold text-white">$4.99</td>
                            <td class="py-3 pr-2 text-right">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 uppercase">Success</span>
                            </td>
                        </tr>
                        <tr class="text-slate-300 hover:bg-white/5 transition-colors group">
                            <td class="py-3 pl-2">
                                <div class="font-semibold text-slate-200 group-hover:text-white">Anirban Sumon</div>
                                <span class="text-[10px] text-slate-500">TXN-893012</span>
                            </td>
                            <td class="py-3 text-slate-400">Super Pack 6 Month</td>
                            <td class="py-3 font-bold text-white">$24.99</td>
                            <td class="py-3 pr-2 text-right">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 uppercase">Success</span>
                            </td>
                        </tr>
                        <tr class="text-slate-300 hover:bg-white/5 transition-colors group">
                            <td class="py-3 pl-2">
                                <div class="font-semibold text-slate-200 group-hover:text-white">Sumon Sarkar</div>
                                <span class="text-[10px] text-slate-500">TXN-712398</span>
                            </td>
                            <td class="py-3 text-slate-400">Basic 1 Month</td>
                            <td class="py-3 font-bold text-white">$1.99</td>
                            <td class="py-3 pr-2 text-right">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20 uppercase">Failed</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-slate-900/30 backdrop-blur-xl border border-white/5 rounded-2xl p-5">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">Top Stream Activity</h2>
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            </div>
            
            <div class="space-y-3.5">
                <div class="flex items-center justify-between p-2.5 rounded-xl bg-white/5 border border-white/5 group hover:border-white/10 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-600/20 flex items-center justify-center text-indigo-400 font-bold text-xs">S</div>
                        <div>
                            <p class="text-xs font-semibold text-slate-200 group-hover:text-white transition-colors">Sony Entertainment TV</p>
                            <p class="text-[10px] text-slate-500">M3U8 Proxy $\cdot$ Live</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-bold text-indigo-400 bg-indigo-500/10 px-2 py-0.5 rounded-md">245 active</span>
                </div>

                <div class="flex items-center justify-between p-2.5 rounded-xl bg-white/5 border border-white/5 group hover:border-white/10 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-purple-600/20 flex items-center justify-center text-purple-400 font-bold text-xs">Z</div>
                        <div>
                            <p class="text-xs font-semibold text-slate-200 group-hover:text-white transition-colors">Zee Bangla Cinema</p>
                            <p class="text-[10px] text-slate-500">MPD Dash $\cdot$ Cached</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-bold text-purple-400 bg-purple-500/10 px-2 py-0.5 rounded-md">189 active</span>
                </div>

                <div class="flex items-center justify-between p-2.5 rounded-xl bg-white/5 border border-white/5 group hover:border-white/10 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-600/20 flex items-center justify-center text-emerald-400 font-bold text-xs">N</div>
                        <div>
                            <p class="text-xs font-semibold text-slate-200 group-hover:text-white transition-colors">National Geo Bangla</p>
                            <p class="text-[10px] text-slate-500">Direct Link $\cdot$ Active</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-bold text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-md">94 active</span>
                </div>
            </div>
        </div>

    </div>
</div>
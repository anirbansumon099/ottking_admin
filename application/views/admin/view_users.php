<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-white">Registered Users</h2>
        <div class="text-xs text-slate-400">Total Users: <?= count($users) ?></div>
    </div>

    <div class="bg-slate-900/50 border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left text-slate-300">
                <thead class="bg-white/5 text-[10px] uppercase font-bold text-slate-400">
                    <tr>
                        <th class="px-6 py-4 whitespace-nowrap">User Name</th>
                        <th class="px-6 py-4 whitespace-nowrap">Email</th>
                        <th class="px-6 py-4 whitespace-nowrap">Plan</th>
                        <th class="px-6 py-4 whitespace-nowrap text-center">Status</th>
                        <th class="px-6 py-4 whitespace-nowrap text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php if(!empty($users)): foreach($users as $row): ?>
                    <tr class="hover:bg-white/5 transition-all text-sm">
                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-white"><?= htmlspecialchars($row['name'] ?? 'N/A') ?></td>
                        <td class="px-6 py-4 whitespace-nowrap font-mono text-indigo-300"><?= $row['email'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= $row['plan'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="<?= base_url('admin/users/status/'.$row['id'].'?val='.($row['status'] == 1 ? 0 : 1)) ?>" 
                               class="px-3 py-1 rounded-full text-[10px] font-bold uppercase transition-all 
                               <?= ($row['status'] == 1) ? 'bg-green-500/10 text-green-400 hover:bg-green-500/20' : 'bg-red-500/10 text-red-400 hover:bg-red-500/20' ?>">
                                <?= ($row['status'] == 1) ? 'Active' : 'Banned' ?>
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="<?= base_url('admin/users/delete/'.$row['id']) ?>" 
                               onclick="return confirm('Are you sure you want to delete this user?')" 
                               class="text-red-400 hover:text-red-300 font-bold text-xs uppercase">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-10 text-slate-500">No users found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
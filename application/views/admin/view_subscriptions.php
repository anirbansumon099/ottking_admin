<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-white">User Subscriptions</h2>
        <a href="<?= base_url('admin/subscriptions/packages') ?>" class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold">
            Manage Plans <i class="fa-solid fa-arrow-right ml-1"></i>
        </a>
    </div>

    <div class="bg-slate-900/50 border border-white/10 rounded-2xl overflow-hidden">
        <table class="w-full text-left text-slate-300">
            <thead class="bg-white/5 text-[10px] uppercase font-bold text-slate-400">
                <tr>
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Plan</th>
                    <th class="px-6 py-4">Expires At</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (!empty($subscriptions)): foreach ($subscriptions as $row): ?>
                <?php
                    $is_expired = empty($row['plan_expires_at']) || strtotime($row['plan_expires_at']) < time();
                ?>
                <tr class="hover:bg-white/5 transition-all text-sm">
                    <td class="px-6 py-4">
                        <div class="font-semibold text-white"><?= htmlspecialchars($row['name'] ?? $row['username'] ?? 'N/A') ?></div>
                        <div class="text-xs text-slate-500 font-mono"><?= htmlspecialchars($row['email']) ?></div>
                    </td>
                    <td class="px-6 py-4"><?= htmlspecialchars($row['plan']) ?></td>
                    <td class="px-6 py-4 text-xs text-slate-400"><?= $row['plan_expires_at'] ?? 'N/A' ?></td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase <?= $is_expired ? 'bg-red-500/10 text-red-400' : 'bg-green-500/10 text-green-400' ?>">
                            <?= $is_expired ? 'Expired' : 'Active' ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button onclick='openSubModal(<?= json_encode($row) ?>)' class="text-blue-400 font-bold text-xs mr-3">CHANGE PLAN</button>
                        <a href="<?= base_url('admin/subscriptions/extend/'.$row['id']) ?>" class="text-green-400 font-bold text-xs">+30 DAYS</a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="5" class="text-center py-10 text-slate-500">No subscribers found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="subModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 hidden p-4">
    <div class="bg-slate-900 p-6 rounded-2xl w-full max-w-sm border border-white/10">
        <h3 class="text-white font-bold mb-4">Update Subscription</h3>
        <form id="subForm" method="post">
            <select name="plan_id" id="s_plan_id" class="w-full bg-slate-800 p-3 rounded-xl mb-3 text-white">
                <option value="">Free (no plan)</option>
                <?php foreach ($plans as $p): ?>
                    <option value="<?= $p['id'] ?>" data-name="<?= htmlspecialchars($p['name']) ?>"><?= htmlspecialchars($p['name']) ?> - ৳<?= $p['price'] ?></option>
                <?php endforeach; ?>
            </select>
            <label class="text-[10px] text-slate-500 uppercase font-bold mb-1 block">Expires At</label>
            <input type="date" name="plan_expires_at" id="s_plan_expires_at" class="w-full bg-slate-800 p-3 rounded-xl mb-3 text-white">
            <button type="submit" class="w-full bg-indigo-600 py-2 rounded-xl text-white font-bold">Save</button>
        </form>
        <button onclick="document.getElementById('subModal').classList.add('hidden')" class="w-full text-slate-400 mt-3 text-sm hover:text-white">Cancel</button>
    </div>
</div>

<script>
function openSubModal(row) {
    document.getElementById('subForm').action = "<?= base_url('admin/subscriptions/update/') ?>" + row.id;
    document.getElementById('s_plan_id').value = row.plan_id || '';
    document.getElementById('s_plan_expires_at').value = row.plan_expires_at ? row.plan_expires_at.split(' ')[0] : '';
    document.getElementById('subModal').classList.remove('hidden');
}
</script>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-white">Payment Methods</h2>
        <button onclick="openPayModal()" class="bg-indigo-600 px-5 py-2 rounded-xl text-sm font-bold text-white">Add Method</button>
    </div>

    <div class="bg-slate-900/50 border border-white/10 rounded-2xl overflow-hidden">
        <table class="w-full text-left text-slate-300">
            <thead class="bg-white/5 text-[10px] uppercase font-bold text-slate-400">
                <tr>
                    <th class="px-6 py-4">Method Name</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($payments as $row): ?>
                <tr class="border-b border-white/5">
                    <td class="px-6 py-4 font-semibold"><?= $row['name'] ?></td>
                    <td class="px-6 py-4">
                        <span class="<?= $row['status'] == 1 ? 'text-green-400' : 'text-red-400' ?>">
                            <?= $row['status'] == 1 ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="editPay(<?= htmlspecialchars(json_encode($row)) ?>)" class="text-blue-400 font-bold text-xs mr-3">EDIT</button>
                        <a href="<?= base_url('admin/payments/delete/'.$row['id']) ?>" class="text-red-400 font-bold text-xs">DELETE</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="payModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 hidden p-4">
    <div class="bg-slate-900 p-6 rounded-2xl w-full max-w-sm border border-white/10">
        <form id="payForm" method="post" action="<?= base_url('admin/payments/add') ?>">
            <input type="text" name="name" id="p_name" placeholder="Method Name (e.g. Stripe, Bkash)" class="w-full bg-slate-800 p-3 rounded-xl mb-3 text-white" required>
            <input type="text" name="api_key" id="p_key" placeholder="API Key" class="w-full bg-slate-800 p-3 rounded-xl mb-3 text-white">
            <input type="text" name="api_secret" id="p_secret" placeholder="API Secret" class="w-full bg-slate-800 p-3 rounded-xl mb-3 text-white">
            <select name="status" id="p_status" class="w-full bg-slate-800 p-3 rounded-xl mb-4 text-white">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            <button type="submit" class="w-full bg-indigo-600 py-2 rounded-xl text-white font-bold">Save Changes</button>
        </form>
    </div>
</div>

<script>
function openPayModal() { document.getElementById('payModal').classList.remove('hidden'); }
function editPay(data) {
    document.getElementById('payForm').action = "<?= base_url('admin/payments/edit/') ?>" + data.id;
    document.getElementById('p_name').value = data.name;
    document.getElementById('p_key').value = data.api_key;
    document.getElementById('p_secret').value = data.api_secret;
    document.getElementById('p_status').value = data.status;
    openPayModal();
}
</script>
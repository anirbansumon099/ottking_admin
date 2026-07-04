<style>
    .toast {
        position: fixed; bottom: 20px; right: 20px;
        background: #4f46e5; color: white; padding: 12px 24px;
        border-radius: 12px; display: none; z-index: 9999;
        box-shadow: 0 10px 25px rgba(0,0,0,0.3); font-size: 14px;
    }
</style>

<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-bold text-white">API Credentials Management</h2>
        <button onclick="openAddModal()" class="bg-indigo-600 hover:bg-indigo-500 px-5 py-2 rounded-xl text-sm font-semibold text-white shadow-lg transition-all">
            <i class="fa-solid fa-plus mr-2"></i> Add New API
        </button>
    </div>

    <div class="bg-slate-900/50 border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left text-slate-300">
                <thead class="bg-white/5 text-[10px] uppercase font-bold text-slate-400">
                    <tr>
                        <th class="px-6 py-4 whitespace-nowrap">App Name</th>
                        <th class="px-6 py-4 whitespace-nowrap">Version</th>
                        <th class="px-6 py-4 whitespace-nowrap">Key ID</th>
                        <th class="px-6 py-4 whitespace-nowrap">HMAC Secret</th>
                        <th class="px-6 py-4 whitespace-nowrap">Encryption Key</th>
                        <th class="px-6 py-4 whitespace-nowrap text-center">Status</th>
                        <th class="px-6 py-4 whitespace-nowrap text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php if(!empty($api_list)): foreach($api_list as $row): ?>
                    <tr class="hover:bg-white/5 transition-all text-sm">
                        <td class="px-6 py-4 whitespace-nowrap text-white font-semibold"><?= $row['app_name'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= $row['app_version'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap font-mono text-indigo-300"><?= $row['app_key_id'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button onclick="copyToClipboard('<?= $row['hmac_secret'] ?>')" class="text-[10px] bg-indigo-500/10 px-2 py-1 rounded text-indigo-400 font-bold hover:bg-indigo-500/20">COPY</button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button onclick="copyToClipboard('<?= $row['encryption_key'] ?>')" class="text-[10px] bg-purple-500/10 px-2 py-1 rounded text-purple-400 font-bold hover:bg-purple-500/20">COPY</button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 py-1 rounded text-[9px] uppercase font-bold <?= ($row['status'] == 1) ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400' ?>">
                                <?= ($row['status'] == 1) ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button onclick="editApi(<?= htmlspecialchars(json_encode($row)) ?>)" class="text-blue-400 font-bold text-xs uppercase mr-3">Edit</button>
                            <a href="<?= base_url('admin/api_settings/delete/'.$row['id']) ?>" class="text-red-400 font-bold text-xs uppercase" onclick="return confirm('Confirm delete?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="7" class="text-center py-10 text-slate-500">No records found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="apiModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden p-4">
    <div class="bg-slate-900 p-6 rounded-2xl w-full max-w-sm border border-white/10">
        <h3 id="modalTitle" class="text-lg font-bold text-white mb-4">Add New API</h3>
        <form id="apiForm" method="post" action="<?= base_url('admin/api_settings/add') ?>">
            <input type="text" name="app_name" id="app_name" placeholder="App Name" class="w-full bg-slate-800 p-3 rounded-xl mb-3 text-white border border-white/5" required>
            <input type="text" name="app_version" id="app_version" placeholder="Version (e.g. 1.0.0)" class="w-full bg-slate-800 p-3 rounded-xl mb-3 text-white border border-white/5" required>
            <div class="flex gap-2 mb-3">
                <input type="text" name="app_key_id" id="app_key_id" placeholder="API Key ID" class="w-full bg-slate-800 p-3 rounded-xl text-white border border-white/5" required>
                <button type="button" onclick="generateKey()" class="bg-indigo-600 px-4 rounded-xl text-white font-bold">Auto</button>
            </div>
            <select name="status" id="status" class="w-full bg-slate-800 p-3 rounded-xl mb-4 text-white border border-white/5">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            <div class="flex gap-2">
                <button type="button" onclick="document.getElementById('apiModal').classList.add('hidden')" class="flex-1 bg-slate-800 py-2 rounded-xl text-slate-300">Cancel</button>
                <button type="submit" name="submit" value="1" class="flex-1 bg-indigo-600 py-2 rounded-xl text-white">Save</button>
            </div>
        </form>
    </div>
</div>

<div id="toast" class="toast">Copied to clipboard!</div>

<script>
function showToast() {
    const t = document.getElementById('toast');
    t.style.display = 'block';
    setTimeout(() => { t.style.display = 'none'; }, 2000);
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => { showToast(); });
}

function generateKey() {
    document.getElementById('app_key_id').value = Math.random().toString(36).substring(2, 18).toUpperCase();
}

function openAddModal() {
    document.getElementById('modalTitle').innerText = "Add New API";
    document.getElementById('apiForm').action = "<?= base_url('admin/api_settings/add') ?>";
    document.getElementById('apiForm').reset();
    document.getElementById('apiModal').classList.remove('hidden');
}

function editApi(data) {
    document.getElementById('modalTitle').innerText = "Edit API";
    document.getElementById('apiForm').action = "<?= base_url('admin/api_settings/edit/') ?>" + data.id;
    document.getElementById('app_name').value = data.app_name;
    document.getElementById('app_version').value = data.app_version;
    document.getElementById('app_key_id').value = data.app_key_id;
    document.getElementById('status').value = data.status;
    document.getElementById('apiModal').classList.remove('hidden');
}
</script>
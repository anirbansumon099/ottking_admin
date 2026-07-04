<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-white">Manage Admins</h2>
        <button onclick="openAdminModal()" class="bg-indigo-600 px-5 py-2 rounded-xl text-sm font-bold text-white">
            <i class="fa-solid fa-plus"></i> Add New Admin
        </button>
    </div>

    <div class="bg-slate-900/50 border border-white/10 rounded-2xl overflow-hidden">
        <table class="w-full text-left text-slate-300">
            <thead class="bg-white/5 text-[10px] uppercase font-bold text-slate-400">
                <tr>
                    <th class="px-6 py-4">Username</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4 text-center">Role</th>
                    <th class="px-6 py-4 text-center">Verified</th>
                    <th class="px-6 py-4">Last Login</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (!empty($admins)): foreach ($admins as $row): ?>
                <tr class="hover:bg-white/5 transition-all text-sm">
                    <td class="px-6 py-4 font-semibold text-white"><?= htmlspecialchars($row['username']) ?></td>
                    <td class="px-6 py-4 font-mono text-indigo-300"><?= htmlspecialchars($row['email']) ?></td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase bg-purple-500/10 text-purple-400">
                            <?= htmlspecialchars($row['role']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="<?= base_url('admin/view_admin/verify/'.$row['id']) ?>"
                           class="px-2 py-1 rounded-full text-[10px] font-bold uppercase <?= $row['is_verify'] ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400' ?>">
                            <?= $row['is_verify'] ? 'Verified' : 'Unverified' ?>
                        </a>
                    </td>
                    <td class="px-6 py-4 text-xs text-slate-400"><?= $row['last_login'] ?? 'Never' ?></td>
                    <td class="px-6 py-4 text-center">
                        <button onclick='editAdmin(<?= json_encode($row) ?>)' class="text-blue-400 font-bold text-xs mr-3">EDIT</button>
                        <?php if ($row['id'] != $this->session->userdata('user_id')): ?>
                        <a href="<?= base_url('admin/view_admin/delete/'.$row['id']) ?>"
                           onclick="return confirm('Delete this admin account?')"
                           class="text-red-400 font-bold text-xs">DELETE</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6" class="text-center py-10 text-slate-500">No admins found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="adminModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 hidden p-4">
    <div class="bg-slate-900 p-6 rounded-2xl w-full max-w-sm border border-white/10">
        <h3 id="adminModalTitle" class="text-white font-bold mb-4">Add New Admin</h3>
        <form id="adminForm" method="post" action="<?= base_url('admin/view_admin/add') ?>">
            <input type="text" name="username" id="a_username" placeholder="Username" class="w-full bg-slate-800 p-3 rounded-xl mb-3 text-white" required>
            <input type="email" name="email" id="a_email" placeholder="Email" class="w-full bg-slate-800 p-3 rounded-xl mb-3 text-white" required>
            <input type="password" name="password" id="a_password" placeholder="Password" class="w-full bg-slate-800 p-3 rounded-xl mb-3 text-white">
            <p id="a_password_hint" class="text-[10px] text-slate-500 mb-3 hidden">Leave blank to keep the current password.</p>
            <select name="role" id="a_role" class="w-full bg-slate-800 p-3 rounded-xl mb-3 text-white">
                <option value="editor">Editor</option>
                <option value="admin">Admin</option>
                <option value="superadmin">Superadmin</option>
            </select>
            <button type="submit" class="w-full bg-indigo-600 py-2 rounded-xl text-white font-bold">Save Admin</button>
        </form>
        <button onclick="closeAdminModal()" class="w-full text-slate-400 mt-3 text-sm hover:text-white">Cancel</button>
    </div>
</div>

<script>
function openAdminModal() {
    document.getElementById('adminModalTitle').textContent = 'Add New Admin';
    document.getElementById('adminForm').action = "<?= base_url('admin/view_admin/add') ?>";
    document.getElementById('adminForm').reset();
    document.getElementById('a_password').required = true;
    document.getElementById('a_password_hint').classList.add('hidden');
    document.getElementById('adminModal').classList.remove('hidden');
}
function closeAdminModal() {
    document.getElementById('adminModal').classList.add('hidden');
}
function editAdmin(data) {
    document.getElementById('adminModalTitle').textContent = 'Edit Admin';
    document.getElementById('adminForm').action = "<?= base_url('admin/view_admin/edit/') ?>" + data.id;
    document.getElementById('a_username').value = data.username;
    document.getElementById('a_email').value = data.email;
    document.getElementById('a_password').value = '';
    document.getElementById('a_password').required = false;
    document.getElementById('a_password_hint').classList.remove('hidden');
    document.getElementById('a_role').value = data.role;
    document.getElementById('adminModal').classList.remove('hidden');
}
</script>

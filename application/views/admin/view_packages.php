<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-white">Subscription Plans</h2>
        <button onclick="openModal()" class="bg-indigo-600 px-5 py-2 rounded-xl text-sm font-bold text-white">Add New Plan</button>
    </div>

    <div class="bg-slate-900/50 border border-white/10 rounded-2xl overflow-hidden">
        <table class="w-full text-left text-slate-300">
            <thead class="bg-white/5 text-[10px] uppercase font-bold text-slate-400">
                <tr>
                    <th class="px-6 py-4">Plan Name</th>
                    <th class="px-6 py-4">Price</th>
                    <th class="px-6 py-4">Badge</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (!empty($packages)): foreach ($packages as $row): ?>
                <tr>
                    <td class="px-6 py-4"><?= htmlspecialchars($row['name']) ?></td>
                    <td class="px-6 py-4">৳<?= $row['price'] ?></td>
                    <td class="px-6 py-4"><span class="bg-purple-500/20 px-2 py-1 rounded text-purple-400 text-xs"><?= htmlspecialchars($row['badge']) ?></span></td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="editPackage(<?= htmlspecialchars(json_encode($row)) ?>)" class="text-blue-400 font-bold text-xs mr-3">EDIT</button>
                        <button onclick='openChannelsModal(<?= $row['id'] ?>, <?= json_encode($assigned_channels[$row['id']] ?? []) ?>)' class="text-indigo-400 font-bold text-xs mr-3">CHANNELS</button>
                        <a href="<?= base_url('admin/subscriptions/packages/delete/'.$row['id']) ?>"
                           onclick="return confirm('Delete this plan?')" class="text-red-400 font-bold text-xs">DELETE</a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="4" class="text-center py-10 text-slate-500">No plans found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="pacModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 hidden p-4">
    <div class="bg-slate-900 p-6 rounded-2xl w-full max-w-sm border border-white/10">
        <form id="pacForm" method="post" action="<?= base_url('admin/subscriptions/packages/add') ?>">
            <input type="text" name="name" id="name" placeholder="Plan Name" class="w-full bg-slate-800 p-3 rounded-xl mb-3 text-white" required>
            <input type="text" name="price" id="price" placeholder="Price" class="w-full bg-slate-800 p-3 rounded-xl mb-3 text-white" required>
            <input type="text" name="badge" id="badge" placeholder="Badge (e.g. Popular)" class="w-full bg-slate-800 p-3 rounded-xl mb-3 text-white">
            <textarea name="features" id="features" placeholder="Features (comma separated)" class="w-full bg-slate-800 p-3 rounded-xl mb-3 text-white"></textarea>
            <button type="submit" class="w-full bg-indigo-600 py-2 rounded-xl text-white font-bold">Save Plan</button>
        </form>
    </div>
</div>

<!-- package_channels টেবিলের জন্য এই মোডালটা আগে ছিলই না — কোন প্ল্যানে কোন চ্যানেল থাকবে সেটা এখানে সেট করা হয় -->
<div id="channelsModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 hidden p-4">
    <div class="bg-slate-900 p-6 rounded-2xl w-full max-w-md border border-white/10">
        <h3 class="text-white font-bold mb-4">Assign Channels to Plan</h3>
        <form id="channelsForm" method="post">
            <div class="max-h-80 overflow-y-auto space-y-2 mb-4 pr-1">
                <?php if (!empty($channels)): foreach ($channels as $ch): ?>
                <label class="flex items-center gap-3 bg-slate-800 p-3 rounded-lg text-sm text-slate-200">
                    <input type="checkbox" name="channel_ids[]" value="<?= $ch['id'] ?>" class="channel-checkbox">
                    <?= htmlspecialchars($ch['name']) ?>
                </label>
                <?php endforeach; else: ?>
                    <p class="text-slate-500 text-sm">No channels available yet.</p>
                <?php endif; ?>
            </div>
            <button type="submit" class="w-full bg-indigo-600 py-2 rounded-xl text-white font-bold">Save Channels</button>
        </form>
        <button onclick="document.getElementById('channelsModal').classList.add('hidden')" class="w-full text-slate-400 mt-3 text-sm hover:text-white">Cancel</button>
    </div>
</div>

<script>
function openModal() { document.getElementById('pacModal').classList.remove('hidden'); }
function editPackage(data) {
    document.getElementById('pacForm').action = "<?= base_url('admin/subscriptions/packages/edit/') ?>" + data.id;
    document.getElementById('name').value = data.name;
    document.getElementById('price').value = data.price;
    document.getElementById('badge').value = data.badge;
    document.getElementById('features').value = data.features;
    openModal();
}

function openChannelsModal(planId, assignedIds) {
    document.getElementById('channelsForm').action = "<?= base_url('admin/subscriptions/packages/channels/') ?>" + planId;
    document.querySelectorAll('.channel-checkbox').forEach(function (box) {
        box.checked = assignedIds.includes(parseInt(box.value));
    });
    document.getElementById('channelsModal').classList.remove('hidden');
}
</script>

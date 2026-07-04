<div class="p-6 bg-slate-900 rounded-2xl border border-white/10 shadow-xl">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-white text-xl font-bold">Manage Channels</h2>
        <div class="flex gap-2">
            <form action="<?= base_url('admin/channels') ?>" method="GET" class="flex gap-2">
                <input type="text" name="search" placeholder="Search..." value="<?= $this->input->get('search') ?>" 
                       class="bg-slate-800 text-white px-4 py-2 rounded-lg border border-white/10 outline-none">
                
                <select name="category_id" class="bg-slate-800 text-white px-4 py-2 rounded-lg border border-white/10 outline-none">
                    <option value="">All Categories</option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($this->input->get('category_id') == $cat['id']) ? 'selected' : '' ?>>
                            <?= $cat['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <button type="submit" class="bg-blue-600 px-4 py-2 rounded-lg text-white">Filter</button>
            </form>
            
            <button onclick="toggleModal('addChannelModal')" class="bg-indigo-600 px-4 py-2 rounded-lg text-white font-bold">+ Add New</button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-slate-300">
            <thead class="text-xs uppercase bg-white/5">
                <tr>
                    <th class="px-6 py-4 text-left">Logo</th>
                    <th class="px-6 py-4 text-left">Name</th>
                    <th class="px-6 py-4 text-left">Category</th>
                    <th class="px-6 py-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (!empty($channels)): foreach ($channels as $c): ?>
                <tr class="hover:bg-white/5">
                    <td class="px-6 py-4"><img src="<?= $c['logo_url'] ?>" class="w-10 h-10 rounded-full"></td>
                    <td class="px-6 py-4 font-medium"><?= $c['name'] ?></td>
                    <td class="px-6 py-4"><?= $c['category_name'] ?? 'N/A' ?></td>
                    <td class="px-6 py-4 text-center">
                        <a href="<?= base_url('admin/channels/edit/'.$c['id']) ?>" class="text-blue-400 hover:text-blue-300 mr-4">Edit</a>
                        <button type="button" 
                                onclick="deleteConfirm('<?= base_url('admin/channels/delete/'.$c['id']) ?>')" 
                                class="text-red-400 hover:text-red-300">Delete</button>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="4" class="text-center py-10">No channels found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-center">
        <?= isset($pagination) ? $pagination : '' ?>
    </div>
</div>

<!-- এই মডালটা আগে HTML-এ ছিলই না, তাই "Add New" বাটনে ক্লিক করলে কিছুই হতো না -->
<div id="addChannelModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-50">
    <div class="bg-slate-900 p-6 w-full max-w-lg rounded-2xl border border-white/10 shadow-2xl">
        <h3 class="text-white font-bold mb-4">Add New Channel</h3>
        <form action="<?= base_url('admin/channels/add') ?>" method="POST" class="grid grid-cols-2 gap-4">
            <input type="text" name="name" required placeholder="Channel Name"
                   class="col-span-2 p-3 bg-slate-800 rounded-lg text-white border border-white/10 outline-none">

            <select name="category_id" required class="p-3 bg-slate-800 rounded-lg text-white border border-white/10 outline-none">
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                <?php endforeach; ?>
            </select>

            <select name="quality" class="p-3 bg-slate-800 rounded-lg text-white border border-white/10 outline-none">
                <option value="HD">HD</option>
                <option value="FHD">FHD</option>
                <option value="SD">SD</option>
            </select>

            <input type="text" name="stream_url" required placeholder="Stream URL (m3u8/HLS)"
                   class="col-span-2 p-3 bg-slate-800 rounded-lg text-white border border-white/10 outline-none">

            <input type="text" name="logo_url" placeholder="Logo URL"
                   class="col-span-2 p-3 bg-slate-800 rounded-lg text-white border border-white/10 outline-none">

            <textarea name="description" placeholder="Description"
                      class="col-span-2 p-3 bg-slate-800 rounded-lg text-white border border-white/10 outline-none"></textarea>

            <label class="col-span-2 flex items-center gap-2 text-slate-300 text-sm">
                <input type="checkbox" name="is_premium" value="1"> Premium Channel
            </label>

            <button type="submit" class="col-span-2 bg-indigo-600 py-3 rounded-lg text-white font-bold hover:bg-indigo-500 transition-all">
                Save Channel
            </button>
        </form>
        <button onclick="toggleModal('addChannelModal')" class="w-full text-slate-400 mt-4 text-sm hover:text-white">Cancel</button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function toggleModal(id) { $('#' + id).toggleClass('hidden'); }

function deleteConfirm(url) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}
</script>
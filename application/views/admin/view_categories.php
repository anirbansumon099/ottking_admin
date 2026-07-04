<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-white">Manage Categories</h2>
            <p class="text-slate-400 text-sm">Organize your channels into specific categories.</p>
        </div>
        <button onclick="toggleModal('addCategoryModal')" class="bg-indigo-600 px-4 py-2 rounded-xl text-white text-sm font-semibold hover:bg-indigo-500 transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Add New Category
        </button>
    </div>

    <div class="bg-slate-900/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden shadow-xl">
        <table class="w-full text-slate-300">
            <thead class="bg-white/5 text-xs uppercase text-slate-400">
                <tr>
                    <th class="px-6 py-4 text-left">Category Name</th>
                    <th class="px-6 py-4 text-center">Total Channels</th>
                    <th class="px-6 py-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody id="category-list" class="divide-y divide-white/5">
                </tbody>
        </table>
    </div>
</div>

<div id="addCategoryModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-50">
    <div class="bg-slate-900 p-6 w-full max-w-sm rounded-2xl border border-white/10 shadow-2xl">
        <h3 class="text-white font-bold mb-4">Add New Category</h3>
        <form id="addCategoryForm" class="space-y-4">
            <div>
                <label class="text-[10px] text-slate-500 uppercase font-bold mb-1 block">Category Name</label>
                <input type="text" name="category_name" class="w-full bg-slate-800 p-3 rounded-lg text-white border border-white/10 focus:border-indigo-500 outline-none" required>
            </div>
            <button type="submit" class="w-full bg-indigo-600 py-3 rounded-lg text-white font-bold hover:bg-indigo-500 transition-all">Save Category</button>
        </form>
        <button onclick="toggleModal('addCategoryModal')" class="w-full text-slate-400 mt-4 text-sm hover:text-white">Cancel</button>
    </div>
</div>

<!-- Edit মডাল আগে ছিল না -->
<div id="editCategoryModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-50">
    <div class="bg-slate-900 p-6 w-full max-w-sm rounded-2xl border border-white/10 shadow-2xl">
        <h3 class="text-white font-bold mb-4">Edit Category</h3>
        <form id="editCategoryForm" class="space-y-4">
            <input type="hidden" name="id">
            <div>
                <label class="text-[10px] text-slate-500 uppercase font-bold mb-1 block">Category Name</label>
                <input type="text" name="category_name" class="w-full bg-slate-800 p-3 rounded-lg text-white border border-white/10 focus:border-indigo-500 outline-none" required>
            </div>
            <button type="submit" class="w-full bg-indigo-600 py-3 rounded-lg text-white font-bold hover:bg-indigo-500 transition-all">Update Category</button>
        </form>
        <button onclick="toggleModal('editCategoryModal')" class="w-full text-slate-400 mt-4 text-sm hover:text-white">Cancel</button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function toggleModal(id) { $('#' + id).toggleClass('hidden'); }

// ক্যাটাগরি লোড করার ফাংশন
// আগে c.category_name / c.slug পড়ার চেষ্টা করত, কিন্তু ব্যাকএন্ড থেকে আসা
// আসল কলামের নাম হলো c.name — তাই টেবিল কখনোই ঠিকভাবে ভরত না
function loadCategories() {
    $.get('<?= base_url("admin/get_categories_ajax") ?>', function(res) {
        let data = typeof res === 'string' ? JSON.parse(res) : res;
        let rows = '';
        data.forEach(c => {
            rows += `<tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4 font-bold text-white">${c.name}</td>
                        <td class="px-6 py-4 text-center">${c.channel_count}</td>
                        <td class="px-6 py-4 text-center">
                            <button onclick="openEdit('${c.id}', '${c.name}')" class="text-indigo-400 hover:text-indigo-300 mr-3"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button onclick="deleteCategory('${c.id}')" class="text-red-400 hover:text-red-300"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>`;
        });
        $('#category-list').html(rows || '<tr><td colspan="3" class="text-center py-10">No categories found.</td></tr>');
    });
}

// ক্যাটাগরি সাবমিট ফাংশন
$('#addCategoryForm').submit(function(e) {
    e.preventDefault();
    $.post('<?= base_url("admin/add_category_ajax") ?>', $(this).serialize(), function() {
        toggleModal('addCategoryModal');
        loadCategories();
        $('#addCategoryForm')[0].reset();
    });
});

function openEdit(id, name) {
    $('#editCategoryForm input[name="id"]').val(id);
    $('#editCategoryForm input[name="category_name"]').val(name);
    toggleModal('editCategoryModal');
}

$('#editCategoryForm').submit(function(e) {
    e.preventDefault();
    let id = $(this).find('input[name="id"]').val();
    $.post('<?= base_url("admin/edit_category_ajax") ?>/' + id, $(this).serialize(), function() {
        toggleModal('editCategoryModal');
        loadCategories();
    });
});

function deleteCategory(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Channels in this category will not be deleted, but they will lose their category.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?= base_url("admin/delete_category_ajax") ?>/' + id, function() {
                loadCategories();
            });
        }
    });
}

$(document).ready(function() { loadCategories(); });
</script>

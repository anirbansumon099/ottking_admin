<form action="<?= base_url('admin/channels/update/'.$channel['id']) ?>" method="POST"
      class="bg-slate-900 p-6 rounded-2xl border border-white/10">
    <h3 class="text-white font-bold mb-4">Edit Channel: <?= $channel['name'] ?></h3>
    <div class="grid grid-cols-2 gap-4">
        <input type="text" name="name" value="<?= $channel['name'] ?>"
               class="p-3 bg-slate-800 rounded text-white" placeholder="Name" required>

        <select name="category_id" class="p-3 bg-slate-800 rounded text-white" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $channel['category_id']) ? 'selected' : '' ?>>
                    <?= $cat['name'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="text" name="stream_url" value="<?= $channel['stream_url'] ?>"
               class="col-span-2 p-3 bg-slate-800 rounded text-white" placeholder="Stream URL" required>

        <input type="text" name="logo_url" value="<?= $channel['logo_url'] ?>"
               class="col-span-2 p-3 bg-slate-800 rounded text-white" placeholder="Logo URL">

        <textarea name="description" class="col-span-2 p-3 bg-slate-800 rounded text-white"
                  placeholder="Description"><?= $channel['description'] ?></textarea>

        <select name="quality" class="p-3 bg-slate-800 rounded text-white">
            <?php foreach (['HD', 'FHD', 'SD'] as $q): ?>
                <option value="<?= $q ?>" <?= ($channel['quality'] == $q) ? 'selected' : '' ?>><?= $q ?></option>
            <?php endforeach; ?>
        </select>

        <label class="flex items-center gap-2 text-slate-300 text-sm">
            <input type="checkbox" name="is_premium" value="1" <?= $channel['is_premium'] ? 'checked' : '' ?>>
            Premium Channel
        </label>

        <button type="submit" class="col-span-2 bg-indigo-600 p-3 rounded text-white font-bold">Update Channel</button>
    </div>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>App Name</th>
            <th>Version</th>
            <th>Key ID</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($credentials as $row): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['app_name']; ?></td>
            <td><?php echo $row['app_version']; ?></td>
            <td><?php echo $row['app_key_id']; ?></td>
            <td>
                <?php echo ($row['status'] == 1) ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>'; ?>
            </td>
            <td>
                <button class="btn btn-sm btn-info">View Full</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
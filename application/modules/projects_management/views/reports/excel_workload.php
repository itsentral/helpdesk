<h3>LAPORAN RESOURCE WORKLOAD TEAM PROJECT</h3>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr style="background-color: #cccccc;">
            <th>No</th>
            <th>Nama Member</th>
            <th>Username</th>
            <th>Total Assigned Tasks</th>
            <th>Completed Tasks</th>
            <th>Total Estimated Hours</th>
            <th>Total Actual Hours</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($workload)): $no=1; foreach ($workload as $wl): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= html_escape($wl['nm_lengkap'] ? $wl['nm_lengkap'] : $wl['username']); ?></td>
                <td><?= html_escape($wl['username']); ?></td>
                <td><?= $wl['total_assigned_tasks']; ?></td>
                <td><?= $wl['completed_tasks']; ?></td>
                <td><?= $wl['total_estimated_hours']; ?></td>
                <td><?= $wl['total_actual_hours']; ?></td>
            </tr>
        <?php endforeach; endif; ?>
    </tbody>
</table>

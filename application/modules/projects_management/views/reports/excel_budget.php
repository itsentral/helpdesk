<h3>LAPORAN PROJECT COSTING VS BUDGET</h3>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr style="background-color: #cccccc;">
            <th>No</th>
            <th>Kode Project</th>
            <th>Nama Project</th>
            <th>Client</th>
            <th>Project Manager</th>
            <th>Status</th>
            <th>Budget (Rp)</th>
            <th>Est. Hours</th>
            <th>Logged Hours</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($projects)): $no=1; foreach ($projects as $p): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= html_escape($p['project_code']); ?></td>
                <td><?= html_escape($p['project_name']); ?></td>
                <td><?= html_escape($p['client_name'] ? $p['client_name'] : '-'); ?></td>
                <td><?= html_escape($p['pm_name'] ? $p['pm_name'] : '-'); ?></td>
                <td><?= html_escape($p['status']); ?></td>
                <td><?= $p['budget']; ?></td>
                <td><?= $p['total_estimated_hours']; ?></td>
                <td><?= $p['total_logged_hours']; ?></td>
            </tr>
        <?php endforeach; endif; ?>
    </tbody>
</table>

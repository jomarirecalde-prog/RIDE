<?php
$pageTitle = 'Ongoing Projects — RIDE IMS';
$pageHeading = 'Ongoing Projects';
$pageSubtitle = 'Approved projects under monitoring (milestones, reports, documents).';
?>

<div class="card">    <?php if (empty($projects)): ?>
        <p>No ongoing projects yet. Projects appear here after final approval.</p>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Title</th>
                <th>Type</th>
                <th>Leader</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($projects as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['project_code'] ?? '—') ?></td>
                <td><?= htmlspecialchars($p['title']) ?></td>
                <td><?= htmlspecialchars($p['project_type']) ?></td>
                <td><?= htmlspecialchars(($p['first_name'] ?? '') . ' ' . ($p['last_name'] ?? '')) ?></td>
                <td><span class="badge badge-<?= htmlspecialchars($p['status']) ?>"><?= htmlspecialchars($p['status']) ?></span></td>
                <td><a class="btn btn-sm" href="<?= base_url('projects/' . $p['id']) ?>">Monitor</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

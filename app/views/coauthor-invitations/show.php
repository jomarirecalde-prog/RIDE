<?php
/** @var array<string, mixed> $invitation */
$invitation = $invitation ?? [];
$leadName = trim((string) ($invitation['lead_first_name'] ?? '') . ' ' . (string) ($invitation['lead_last_name'] ?? ''));
$title = (string) ($invitation['proposal_title'] ?? 'Untitled proposal');
$status = (string) ($invitation['status'] ?? 'pending');
$invitationId = (int) ($invitation['id'] ?? 0);
?>

<section class="card" style="max-width: 640px;">
    <h2 style="margin-top: 0;">Co-author invitation</h2>
    <p>
        <strong><?= htmlspecialchars($leadName !== '' ? $leadName : 'A researcher') ?></strong>
        invited you to be a co-author on:
    </p>
    <p style="font-size: 1.05rem; font-weight: 600; color: #1E3A6F;">
        <?= htmlspecialchars($title) ?>
    </p>
    <p class="muted">
        If you accept, this proposal will appear on your dashboard and proposals list. If you decline,
        the lead author will be notified automatically.
    </p>

    <?php if ($status === 'pending'): ?>
        <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 24px;">
            <form method="post" action="<?= base_url('coauthor-invitations/' . $invitationId . '/accept') ?>">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-primary">Accept</button>
            </form>
            <form method="post" action="<?= base_url('coauthor-invitations/' . $invitationId . '/reject') ?>">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-outline">Decline</button>
            </form>
        </div>
    <?php elseif ($status === 'accepted'): ?>
        <p class="alert alert-success" style="margin-top: 20px;">You already accepted this invitation.</p>
        <a href="<?= base_url('proposals/' . (int) ($invitation['proposal_id'] ?? 0)) ?>" class="btn btn-outline">View proposal</a>
    <?php else: ?>
        <p class="alert alert-error" style="margin-top: 20px;">You declined this invitation.</p>
        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline">Back to dashboard</a>
    <?php endif; ?>
</section>

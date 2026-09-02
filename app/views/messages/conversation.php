<?php
$user = $user ?? [];
$partner = $partner ?? [];
$partnerId = (int) ($partnerId ?? 0);
$partnerName = (string) ($partnerName ?? '');
$partnerRoleLabel = (string) ($partnerRoleLabel ?? '');
$messages = isset($messages) && is_array($messages) ? $messages : [];
$userId = (int) ($user['id'] ?? 0);
?>

<style>
  .dm-thread-wrap { max-width: 900px; }
  .dm-thread-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #2b5a8c;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 14px;
  }
  .dm-thread-back:hover { text-decoration: underline; }
  .dm-thread-panel {
    background: #fff;
    border: 1px solid #e6edf5;
    border-radius: 16px;
    box-shadow: 0 10px 24px rgba(0, 32, 64, 0.08);
    overflow: hidden;
  }
  .dm-thread-head {
    padding: 14px 16px;
    border-bottom: 1px solid #e8eef5;
    background: #f8fbff;
  }
  .dm-thread-head h2 {
    margin: 0;
    font-size: 1.05rem;
    color: #1f3854;
  }
  .dm-thread-head .role {
    font-size: 0.82rem;
    color: #5a7088;
    margin-top: 2px;
  }
  .dm-thread-messages {
    min-height: 280px;
    max-height: 480px;
    overflow-y: auto;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    background: #fbfdff;
  }
  .dm-bubble {
    max-width: 78%;
    padding: 10px 12px;
    border-radius: 12px;
    line-height: 1.5;
    font-size: 0.92rem;
  }
  .dm-bubble.mine {
    align-self: flex-end;
    background: #2b5a8c;
    color: #fff;
    border-bottom-right-radius: 4px;
  }
  .dm-bubble.theirs {
    align-self: flex-start;
    background: #fff;
    border: 1px solid #e4edf7;
    color: #334e68;
    border-bottom-left-radius: 4px;
  }
  .dm-bubble .meta {
    font-size: 0.72rem;
    opacity: 0.85;
    margin-top: 6px;
  }
  .dm-compose {
    border-top: 1px solid #e8eef5;
    padding: 14px 16px;
    background: #fff;
  }
  .dm-compose textarea {
    width: 100%;
    min-height: 90px;
    border: 1px solid #d3deea;
    border-radius: 10px;
    padding: 10px;
    font-family: inherit;
    resize: vertical;
  }
  .dm-compose-actions {
    margin-top: 10px;
    display: flex;
    justify-content: flex-end;
    gap: 8px;
  }
</style>

<div class="dm-thread-wrap">
  <a href="<?= base_url('messages') ?>" class="dm-thread-back">
    <i class="fas fa-arrow-left" aria-hidden="true"></i> Back to messages
  </a>

  <div class="dm-thread-panel">
    <div class="dm-thread-head">
      <h2><?= htmlspecialchars($partnerName !== '' ? $partnerName : 'Conversation') ?></h2>
      <div class="role"><?= htmlspecialchars($partnerRoleLabel) ?></div>
    </div>

    <div class="dm-thread-messages">
      <?php if ($messages === []): ?>
        <p class="muted" style="margin: auto;">No messages yet. Send the first message below.</p>
      <?php else: ?>
        <?php foreach ($messages as $msg): ?>
          <?php
          $isMine = (int) ($msg['sender_id'] ?? 0) === $userId;
          $createdAt = (string) ($msg['created_at'] ?? '');
          $dateLabel = $createdAt !== '' ? date('M j, Y g:i A', strtotime($createdAt)) : '';
          ?>
          <div class="dm-bubble <?= $isMine ? 'mine' : 'theirs' ?>">
            <?= nl2br(htmlspecialchars((string) ($msg['body'] ?? ''))) ?>
            <?php if ($dateLabel !== ''): ?>
              <div class="meta"><?= htmlspecialchars($dateLabel) ?></div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div class="dm-compose">
      <form method="post" action="<?= base_url('messages/send') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="recipient_id" value="<?= (int) $partnerId ?>">
        <label for="dm_body" class="sr-only">Your message</label>
        <textarea id="dm_body" name="body" maxlength="2000" required placeholder="Type your message…"></textarea>
        <div class="dm-compose-actions">
          <button type="submit" class="btn btn-accent">Send</button>
        </div>
      </form>
    </div>
  </div>
</div>

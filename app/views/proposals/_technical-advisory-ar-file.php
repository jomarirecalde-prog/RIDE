<?php
/** @var array|null $proposal */
/** @var array<string, string> $requiredFileList */
/** @var array<string, list<array<string, mixed>>> $requiredDocuments */
/** @var bool $allowUpload */
/** @var string $fileKey */
/** @var string $emptyLabel */

$allowUpload = $allowUpload ?? false;
$proposalId = is_array($proposal) ? (int) ($proposal['id'] ?? 0) : 0;
$documents = is_array($requiredDocuments[$fileKey] ?? null) ? $requiredDocuments[$fileKey] : [];
$emptyLabel = $emptyLabel ?? 'No file uploaded yet.';
?>
<?php if ($allowUpload): ?>
    <input type="file" name="required_files[<?= htmlspecialchars($fileKey) ?>]" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip">
    <?php if ($proposalId === 0): ?>
        <p class="proposal-section-note">The file will be uploaded when you save the draft.</p>
    <?php endif; ?>
<?php endif; ?>
<?php if ($documents === []): ?>
    <p class="proposal-section-note-inline"><?= htmlspecialchars($emptyLabel) ?></p>
<?php else: ?>
    <?php foreach ($documents as $document): ?>
        <?php
        $docUrl = $proposalId > 0 ? base_url('projects/' . $proposalId . '/documents/' . (int) $document['id']) : '';
        $mime = strtolower((string) ($document['mime_type'] ?? ''));
        $isImage = str_starts_with($mime, 'image/') || preg_match('/\.(jpe?g|png|gif|webp)$/i', (string) ($document['original_name'] ?? '')) === 1;
        ?>
        <div class="trainings-conducted-uploaded-file">
            <?php if ($docUrl !== ''): ?>
                <a href="<?= htmlspecialchars($docUrl) ?>"><?= htmlspecialchars((string) $document['original_name']) ?></a>
                <?php if ($isImage): ?>
                    <img class="eso-ar-table-preview" src="<?= htmlspecialchars($docUrl) ?>" alt="<?= htmlspecialchars((string) $document['original_name']) ?>">
                <?php endif; ?>
            <?php else: ?>
                <?= htmlspecialchars((string) $document['original_name']) ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

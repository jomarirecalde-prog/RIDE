<?php
/** @var array|null $proposal */
/** @var array<string, string> $requiredFileList */
/** @var array<string, list<array<string, mixed>>> $requiredDocuments */
/** @var bool $allowUpload */

$allowUpload = $allowUpload ?? false;
$proposalId = is_array($proposal) ? (int) ($proposal['id'] ?? 0) : 0;
?>
<section class="proposal-section trainings-conducted-documents">
    <h3 class="proposal-subtitle">Supporting Documents</h3>
    <p class="proposal-section-note">
        Required: Accomplishment Report with photos.
        <?php if ($allowUpload && $proposalId === 0): ?>
            Select files below; they will be uploaded when you save the draft.
        <?php elseif (!$allowUpload): ?>
            Not available in view-only mode.
        <?php endif; ?>
    </p>

    <table class="proposal-table trainings-conducted-documents-table">
        <thead>
            <tr>
                <th style="width: <?= $allowUpload ? '42%' : '50%' ?>;">Document</th>
                <?php if ($allowUpload): ?>
                    <th style="width: 28%;">Upload File</th>
                <?php endif; ?>
                <th style="width: <?= $allowUpload ? '30%' : '50%' ?>;">Uploaded File</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($requiredFileList as $fileKey => $fileName): ?>
                <?php $documents = is_array($requiredDocuments[$fileKey] ?? null) ? $requiredDocuments[$fileKey] : []; ?>
                <tr>
                    <td><?= htmlspecialchars($fileName) ?></td>
                    <?php if ($allowUpload): ?>
                        <td>
                            <input type="file" name="required_files[<?= htmlspecialchars($fileKey) ?>]" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip">
                        </td>
                    <?php endif; ?>
                    <td>
                        <?php if ($documents === []): ?>
                            <span class="proposal-section-note-inline">No file uploaded yet.</span>
                        <?php else: ?>
                            <?php foreach ($documents as $document): ?>
                                <div class="trainings-conducted-uploaded-file">
                                    <a href="<?= base_url('projects/' . $proposalId . '/documents/' . (int) $document['id']) ?>">
                                        <?= htmlspecialchars((string) $document['original_name']) ?>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if ($allowUpload): ?>
        <p class="proposal-section-note">Supported formats: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG, ZIP (max 10MB each). Uploading a new file adds to this report; save the form to apply.</p>
    <?php endif; ?>
</section>

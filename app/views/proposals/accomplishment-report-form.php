<?php
/** @var array|null $proposal */
/** @var list<array> $colleges */
/** @var string $collegeName */
/** @var array<string, string> $requiredFileList */
/** @var array<string, list<array<string, mixed>>> $requiredDocuments */

$isEdit = $proposal !== null;
$requiredFileList = $requiredFileList ?? [];
$requiredDocuments = $requiredDocuments ?? [];
$allowDocumentUpload = true;
$pageTitle = ($isEdit ? 'Edit' : 'New') . ' Accomplishment Report — RIDE IMS';
$pageHeading = 'Accomplishment Report';
$pageSubtitle = $isEdit
    ? 'Update your Office of Extension Services accomplishment report before saving or resubmitting.'
    : 'Complete the accomplishment report template (WPU-QSF-RIDE-ESO-13).';
$user = \App\Core\Auth::user() ?? [];

$summaryData = [];
if (!empty($proposal['summary'])) {
    $decodedSummary = json_decode((string) $proposal['summary'], true);
    if (is_array($decodedSummary)) {
        $summaryData = $decodedSummary;
    }
}

$field = static function (string $key, string $fallback = '') use ($summaryData): string {
    if (isset($summaryData[$key]) && is_string($summaryData[$key])) {
        return $summaryData[$key];
    }

    return $fallback;
};

$userName = trim((string) ($user['first_name'] ?? '') . ' ' . (string) ($user['last_name'] ?? ''));
$titleValue = $isEdit ? (string) ($proposal['title'] ?? $field('report_title')) : $field('report_title');
$authorsValue = $field('authors', $userName);
$keywordsValue = $field('keywords');
$introductionValue = $field('introduction');
$methodologyValue = $field('methodology');
$resultsValue = $field('results');
$tableCaptionValue = $field('table_caption');
$figureCaptionValue = $field('figure_caption');
$referencesValue = $field('references');
$acknowledgementValue = $field('acknowledgement');
$collegeDisplay = is_string($summaryData['college_name'] ?? null) && $summaryData['college_name'] !== ''
    ? $summaryData['college_name']
    : $collegeName;
?>

<form class="proposal-paper completed-researches-paper trainings-conducted-paper eso-extension-paper" method="post" enctype="multipart/form-data" action="<?= $isEdit ? base_url('proposals/' . $proposal['id'] . '/accomplishment-report') : base_url('proposals/accomplishment-report') ?>">
    <?= csrf_field() ?>
    <?php if (proposal_nav_scope() !== null): ?>
        <input type="hidden" name="nav_scope" value="extension">
    <?php endif; ?>

    <header class="completed-researches-header">
        <p class="completed-researches-header-line">Republic of the Philippines</p>
        <p class="completed-researches-header-line completed-researches-header-line--strong">WESTERN PHILIPPINES UNIVERSITY</p>
        <p class="completed-researches-header-line">Office of Extension Services</p>
        <h2 class="completed-researches-title">ACCOMPLISHMENT REPORT</h2>
        <p class="completed-researches-form-id">WPU-QSF-RIDE-ESO-13 Rev.00 (08.15.25)</p>
    </header>

    <section class="proposal-section">
        <table class="proposal-table completed-researches-meta-table">
            <tr>
                <th>College</th>
                <td>
                    <?php if ($collegeDisplay !== ''): ?>
                        <input type="hidden" name="college_name" value="<?= htmlspecialchars($collegeDisplay) ?>">
                        <span><?= htmlspecialchars($collegeDisplay) ?></span>
                    <?php else: ?>
                        <select name="college_id" required>
                            <option value="">Select college</option>
                            <?php foreach ($colleges as $college): ?>
                                <option value="<?= (int) $college['id'] ?>" data-name="<?= htmlspecialchars((string) $college['name']) ?>">
                                    <?= htmlspecialchars((string) $college['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="college_name" id="accomplishment-report-college-name" value="">
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Title</th>
                <td>
                    <input name="title" value="<?= htmlspecialchars($titleValue) ?>" placeholder="Enter the title of the report" required>
                </td>
            </tr>
            <tr>
                <th>Authors</th>
                <td>
                    <input name="authors" value="<?= htmlspecialchars($authorsValue) ?>" placeholder="Enter the author(s)">
                </td>
            </tr>
            <tr>
                <th>Keywords</th>
                <td>
                    <input name="keywords" value="<?= htmlspecialchars($keywordsValue) ?>" placeholder="Enter keywords">
                </td>
            </tr>
        </table>
    </section>

    <section class="proposal-section">
        <h3 class="proposal-subtitle">Introduction</h3>
        <p class="proposal-section-note">This section provides the literature review to provide background of the report. It includes the reasons for writing the report, the objectives of the activity, and the definition of terms.</p>
        <textarea name="introduction" class="proposal-textarea" rows="8" placeholder="Write the introduction."><?= htmlspecialchars($introductionValue) ?></textarea>
    </section>

    <section class="proposal-section">
        <h3 class="proposal-subtitle">Methodology</h3>
        <p class="proposal-section-note">This describes the methods of answering why this activity was conducted. Methods may be characterized by using a framework, etc. When data were used to answer questions in the activity, indicate why this type of data is appropriate, relevant, and necessary. It also explains the process of data collection.</p>
        <textarea name="methodology" class="proposal-textarea" rows="8" placeholder="Describe the methodology."><?= htmlspecialchars($methodologyValue) ?></textarea>
    </section>

    <section class="proposal-section">
        <h3 class="proposal-subtitle">Results</h3>
        <p class="proposal-section-note">This summarizes and presents the accomplished activity/ies and may present the findings. Results and conclusions may be presented in written text, tables, graphs, and other illustrations.</p>
        <textarea name="results" class="proposal-textarea" rows="8" placeholder="Present the results."><?= htmlspecialchars($resultsValue) ?></textarea>
        <p class="proposal-section-note">Table numbering is based on the chapter number, followed by the table number within the chapter. Place the caption left-aligned above the table.</p>
        <input name="table_caption" value="<?= htmlspecialchars($tableCaptionValue) ?>" placeholder="Table 3.1. Caption">
        <?php
        $tableDocuments = is_array($requiredDocuments['ar_results_table'] ?? null) ? $requiredDocuments['ar_results_table'] : [];
        $proposalId = is_array($proposal) ? (int) ($proposal['id'] ?? 0) : 0;
        ?>
        <div class="eso-ar-caption-upload">
            <label class="proposal-section-note" for="ar-results-table-file">Upload table</label>
            <input id="ar-results-table-file" type="file" name="required_files[ar_results_table]" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip">
            <?php if ($proposalId === 0): ?>
                <p class="proposal-section-note">The file will be uploaded when you save the draft.</p>
            <?php endif; ?>
            <?php if ($tableDocuments === []): ?>
                <p class="proposal-section-note-inline">No table file uploaded yet.</p>
            <?php else: ?>
                <?php foreach ($tableDocuments as $document): ?>
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
        </div>
        <p class="proposal-section-note">Figure numbering is based on the chapter number, followed by the figure number within the chapter. Place the caption below and at the center of the figure.</p>
        <input name="figure_caption" value="<?= htmlspecialchars($figureCaptionValue) ?>" placeholder="Fig. 3.1. Caption">
        <?php $figureDocuments = is_array($requiredDocuments['ar_results_figure'] ?? null) ? $requiredDocuments['ar_results_figure'] : []; ?>
        <div class="eso-ar-caption-upload">
            <label class="proposal-section-note" for="ar-results-figure-file">Upload figure</label>
            <input id="ar-results-figure-file" type="file" name="required_files[ar_results_figure]" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip">
            <?php if ($proposalId === 0): ?>
                <p class="proposal-section-note">The file will be uploaded when you save the draft.</p>
            <?php endif; ?>
            <?php if ($figureDocuments === []): ?>
                <p class="proposal-section-note-inline">No figure file uploaded yet.</p>
            <?php else: ?>
                <?php foreach ($figureDocuments as $document): ?>
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
        </div>
    </section>

    <?php
    $allowUpload = $allowDocumentUpload;
    require APP_PATH . '/views/proposals/_accomplishment-report-supporting-documents.php';
    ?>

    <section class="proposal-section">
        <h3 class="proposal-subtitle">References</h3>
        <textarea name="references" class="proposal-textarea" rows="6" placeholder="List references."><?= htmlspecialchars($referencesValue) ?></textarea>
    </section>

    <section class="proposal-section">
        <h3 class="proposal-subtitle">Acknowledgement</h3>
        <textarea name="acknowledgement" class="proposal-textarea" rows="5" placeholder="Write the acknowledgement."><?= htmlspecialchars($acknowledgementValue) ?></textarea>
    </section>

    <?php
    $workflowProposal = $proposal ?? [
        'status' => 'draft',
        'project_type' => 'extension',
        'summary' => json_encode(['form_type' => 'accomplishment_report']),
        'first_name' => (string) ($user['first_name'] ?? ''),
        'last_name' => (string) ($user['last_name'] ?? ''),
    ];
    $workflowSteps = proposal_workflow_steps($workflowProposal);
    require APP_PATH . '/views/proposals/_approval-workflow.php';
    ?>

    <div class="actions proposal-form-actions">
        <button type="submit" class="btn"><?= $isEdit ? 'Save Changes' : 'Save Draft' ?></button>
        <?php if ($isEdit): ?>
            <a class="btn btn-outline" href="<?= base_url('proposals/' . $proposal['id']) ?>">Cancel</a>
        <?php endif; ?>
    </div>
</form>

<script>
(() => {
    const collegeSelect = document.querySelector('select[name="college_id"]');
    const collegeNameInput = document.getElementById('accomplishment-report-college-name');
    if (collegeSelect && collegeNameInput) {
        const syncCollegeName = () => {
            const option = collegeSelect.options[collegeSelect.selectedIndex];
            collegeNameInput.value = option?.dataset?.name ?? '';
        };
        collegeSelect.addEventListener('change', syncCollegeName);
        syncCollegeName();
    }

    document.querySelectorAll('textarea.proposal-textarea').forEach((textarea) => {
        const resize = () => {
            textarea.style.height = 'auto';
            textarea.style.height = `${textarea.scrollHeight}px`;
        };
        resize();
        textarea.addEventListener('input', resize);
    });
})();
</script>

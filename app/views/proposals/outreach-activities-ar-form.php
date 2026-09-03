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
$pageTitle = ($isEdit ? 'Edit' : 'New') . ' Outreach Activities AR — RIDE IMS';
$pageHeading = 'Outreach Activities AR';
$pageSubtitle = $isEdit
    ? 'Update your Outreach Activities accomplishment report before saving or resubmitting.'
    : 'Complete the Outreach Activities accomplishment report (WPU-QSF-RIDE-ESO-15).';
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
$activityTitleValue = $field('outreach_activity_title');
$beneficiariesValue = $field('beneficiaries_count');
$venueValue = $field('venue');
$dateValue = $field('activity_date');
$reportValue = $field('narrative_report');
$resourcePersonValue = $field('resource_person_name', $userName);
$collegeDisplay = is_string($summaryData['college_name'] ?? null) && $summaryData['college_name'] !== ''
    ? $summaryData['college_name']
    : $collegeName;
?>

<form class="proposal-paper completed-researches-paper trainings-conducted-paper eso-extension-paper" method="post" enctype="multipart/form-data" action="<?= $isEdit ? base_url('proposals/' . $proposal['id'] . '/outreach-activities-ar') : base_url('proposals/outreach-activities-ar') ?>">
    <?= csrf_field() ?>
    <?php if (proposal_nav_scope() !== null): ?>
        <input type="hidden" name="nav_scope" value="extension">
    <?php endif; ?>

    <header class="completed-researches-header">
        <p class="completed-researches-header-line">Republic of the Philippines</p>
        <p class="completed-researches-header-line completed-researches-header-line--strong">WESTERN PHILIPPINES UNIVERSITY</p>
        <p class="completed-researches-header-line">Office of Extension Services</p>
        <h2 class="completed-researches-title">OUTREACH ACTIVITIES REPORT</h2>
        <p class="completed-researches-form-id">WPU-QSF-RIDE-ESO-15 Rev.00 (08.15.25)</p>
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
                        <input type="hidden" name="college_name" id="outreach-activities-ar-college-name" value="">
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Title of Outreach Activity</th>
                <td>
                    <textarea name="outreach_activity_title" class="proposal-textarea" rows="3" placeholder="Enter the title of the outreach activity." required><?= htmlspecialchars($activityTitleValue) ?></textarea>
                </td>
            </tr>
            <tr>
                <th>No. of Beneficiaries</th>
                <td>
                    <input type="number" name="beneficiaries_count" min="0" step="1" value="<?= htmlspecialchars($beneficiariesValue) ?>" placeholder="Enter the number of beneficiaries">
                </td>
            </tr>
            <tr>
                <th>Venue</th>
                <td>
                    <input name="venue" value="<?= htmlspecialchars($venueValue) ?>" placeholder="Enter the venue">
                </td>
            </tr>
            <tr>
                <th>Date</th>
                <td>
                    <input type="date" name="activity_date" value="<?= htmlspecialchars($dateValue) ?>">
                </td>
            </tr>
            <tr>
                <th>Narrative Report</th>
                <td>
                    <textarea name="narrative_report" class="proposal-textarea" rows="8" placeholder="Write the narrative accomplishment report."><?= htmlspecialchars($reportValue) ?></textarea>
                </td>
            </tr>
            <tr>
                <th>Documentation</th>
                <td>
                    <?php
                    $allowUpload = $allowDocumentUpload;
                    $fileKey = 'oa_ar_documentation';
                    $emptyLabel = 'No documentation uploaded yet.';
                    require APP_PATH . '/views/proposals/_outreach-activities-ar-file.php';
                    ?>
                </td>
            </tr>
            <tr>
                <th>Name &amp; Signature of Resource Person</th>
                <td>
                    <input name="resource_person_name" value="<?= htmlspecialchars($resourcePersonValue) ?>" placeholder="Enter the name of the resource person">
                    <p class="proposal-section-note">The resource person&apos;s signature is captured through the approval workflow after submission.</p>
                </td>
            </tr>
            <tr>
                <th>Attached MOA (for active linkages)</th>
                <td>
                    <?php
                    $allowUpload = $allowDocumentUpload;
                    $fileKey = 'oa_ar_moa';
                    $emptyLabel = 'Optional — no MOA uploaded yet.';
                    require APP_PATH . '/views/proposals/_outreach-activities-ar-file.php';
                    ?>
                    <p class="proposal-section-note">Optional. Attach the MOA when this activity is counted as an active linkage.</p>
                </td>
            </tr>
            <tr>
                <th>Attached attendance sheet</th>
                <td>
                    <?php
                    $allowUpload = $allowDocumentUpload;
                    $fileKey = 'oa_ar_attendance';
                    $emptyLabel = 'No attendance sheet uploaded yet.';
                    require APP_PATH . '/views/proposals/_outreach-activities-ar-file.php';
                    ?>
                </td>
            </tr>
        </table>
    </section>

    <?php
    $workflowProposal = $proposal ?? [
        'status' => 'draft',
        'project_type' => 'extension',
        'summary' => json_encode(['form_type' => 'outreach_activities_ar']),
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
    const collegeNameInput = document.getElementById('outreach-activities-ar-college-name');
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

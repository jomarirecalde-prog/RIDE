<?php
/** @var array $proposal */
/** @var list<array> $comments */
/** @var bool $canEdit */
/** @var bool $canApprove */
/** @var string $approveLabel */
/** @var string $stepLabel */

$pageTitle = htmlspecialchars($proposal['title']) . ' — RIDE IMS';
$pageHeading = htmlspecialchars($proposal['title']);
$pageSubtitle = 'Review the submitted WPU Funded Extension Program application.';

$summaryData = [];
if (!empty($proposal['summary'])) {
    $decodedSummary = json_decode((string) $proposal['summary'], true);
    if (is_array($decodedSummary)) {
        $summaryData = $decodedSummary;
    }
}

$summaryField = static function (string $key, string $legacyKey = '') use ($summaryData): string {
    if (!is_array($summaryData)) {
        return '';
    }

    $value = $summaryData[$key] ?? '';
    if (is_string($value) && trim($value) !== '') {
        return $value;
    }

    if ($legacyKey !== '') {
        $legacy = $summaryData[$legacyKey] ?? '';
        return is_string($legacy) ? $legacy : '';
    }

    return '';
};

$programSummary = is_array($summaryData['program_summary'] ?? null) ? $summaryData['program_summary'] : [];
?>

<div class="page-actions-bar">
    <?php if (in_array($proposal['status'], ['ongoing', 'approved', 'completed'], true)): ?>
        <a class="btn btn-accent" href="<?= base_url('projects/' . $proposal['id']) ?>">Monitor Project</a>
    <?php endif; ?>
    <?php if ($canEdit): ?>
        <a class="btn btn-outline" href="<?= base_url('proposals/' . $proposal['id'] . '/edit') ?>">Edit</a>
    <?php endif; ?>
</div>

<div class="proposal-paper completed-researches-paper wpu-funded-extension-paper">
    <header class="completed-researches-header wpu-funded-extension-header">
        <h2 class="completed-researches-title">APPLICATION FORM</h2>
        <p class="completed-researches-header-line completed-researches-header-line--strong">WPU FUNDED EXTENSION PROGRAM</p>
        <?php if ($summaryField('reference_number') !== ''): ?>
            <p class="wpu-funded-extension-reference-display"><strong>No.</strong> <?= htmlspecialchars($summaryField('reference_number')) ?></p>
        <?php endif; ?>
        <p class="completed-researches-form-id">WPU-QSF-RDE-VPRDE-29 Rev.00 (09.20.24)</p>
    </header>

    <section class="proposal-section">
        <table class="proposal-table">
            <tr>
                <th>Project Code</th>
                <td><?= htmlspecialchars($proposal['project_code'] ?: 'Draft') ?></td>
                <th>Status</th>
                <td>
                    <span class="badge badge-<?= htmlspecialchars($proposal['status']) ?>"><?= htmlspecialchars($proposal['status']) ?></span>
                    <?php if ($proposal['current_step']): ?>
                        <div class="proposal-table-meta">Step: <?= htmlspecialchars($stepLabel) ?></div>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </section>

    <section class="proposal-section">
        <h2 class="proposal-section-title">Applicant Information</h2>
        <table class="proposal-table">
            <tr>
                <th>Program Leader</th>
                <td colspan="3">
                    <?= htmlspecialchars(trim(implode(' ', array_filter([
                        $summaryField('program_leader_first_name', 'applicant_first_name'),
                        $summaryField('program_leader_middle_name', 'applicant_middle_name'),
                        $summaryField('program_leader_last_name', 'applicant_last_name'),
                    ])))) ?: '—' ?>
                </td>
            </tr>
            <tr>
                <th>Title/Prefix</th>
                <td><?= htmlspecialchars($summaryField('program_leader_title_prefix', 'applicant_title_prefix') ?: '—') ?></td>
                <th>Gender</th>
                <td><?= htmlspecialchars($summaryField('program_leader_gender', 'applicant_sex') ?: '—') ?></td>
            </tr>
            <tr>
                <th>Academic Rank</th>
                <td><?= htmlspecialchars($summaryField('program_leader_academic_rank', 'applicant_position') ?: '—') ?></td>
                <th>Date</th>
                <td><?= htmlspecialchars($summaryField('program_leader_date') ?: '—') ?></td>
            </tr>
            <tr>
                <th>E-mail</th>
                <td><?= htmlspecialchars($summaryField('program_leader_email', 'applicant_email') ?: '—') ?></td>
                <th>Contact number</th>
                <td><?= htmlspecialchars($summaryField('program_leader_contact_number', 'applicant_contact_number') ?: '—') ?></td>
            </tr>
            <tr>
                <th>College</th>
                <td colspan="3"><?= htmlspecialchars($summaryField('program_leader_college', 'applicant_college_department') ?: htmlspecialchars($proposal['college_name'] ?? '—')) ?></td>
            </tr>
            <tr>
                <th>Department</th>
                <td colspan="3"><?= htmlspecialchars($summaryField('program_leader_department') ?: '—') ?></td>
            </tr>
        </table>
    </section>

    <section class="proposal-section">
        <h2 class="proposal-section-title">Program Information</h2>
        <h3 class="proposal-subtitle">Title of program</h3>
        <p><?= htmlspecialchars($proposal['title']) ?></p>

        <?php if ($summaryField('introduction') !== ''): ?>
            <h3 class="proposal-subtitle">Introduction</h3>
            <p><?= nl2br(htmlspecialchars($summaryField('introduction'))) ?></p>
        <?php endif; ?>

        <?php if ($summaryField('objectives') !== ''): ?>
            <h3 class="proposal-subtitle">Objectives</h3>
            <p><?= nl2br(htmlspecialchars($summaryField('objectives'))) ?></p>
        <?php endif; ?>

        <?php
        $expectedOutputs = $summaryField('expected_outputs');
        if ($expectedOutputs === '') {
            $expectedOutputs = $summaryField('expected_outcomes');
        }
        ?>
        <?php if ($expectedOutputs !== ''): ?>
            <h3 class="proposal-subtitle">Expected outputs</h3>
            <p><?= nl2br(htmlspecialchars($expectedOutputs)) ?></p>
        <?php endif; ?>
    </section>

    <?php if ($programSummary !== []): ?>
        <section class="proposal-section">
            <h3 class="proposal-subtitle">Program Summary</h3>
            <div class="proposal-table-wrap">
                <table class="proposal-table wpu-funded-extension-summary-table">
                    <thead>
                        <tr>
                            <th>Project Title</th>
                            <th>Trainings/Activities</th>
                            <th>Target Date</th>
                            <th>Amount</th>
                            <th>Source of Fund</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($programSummary as $project): ?>
                            <?php
                            if (!is_array($project)) {
                                continue;
                            }
                            $projectTitle = trim((string) ($project['project_title'] ?? ''));
                            $activities = [];
                            foreach (is_array($project['activities'] ?? null) ? $project['activities'] : [] as $activity) {
                                if (!is_array($activity)) {
                                    continue;
                                }
                                if ($projectTitle !== ''
                                    || trim((string) ($activity['training_activity'] ?? '')) !== ''
                                    || trim((string) ($activity['target_date'] ?? '')) !== ''
                                    || trim((string) ($activity['amount'] ?? '')) !== ''
                                    || trim((string) ($activity['source_of_fund'] ?? '')) !== '') {
                                    $activities[] = $activity;
                                }
                            }
                            if ($activities === []) {
                                continue;
                            }
                            ?>
                            <?php foreach ($activities as $activityIndex => $activity): ?>
                                <tr>
                                    <?php if ($activityIndex === 0): ?>
                                        <td rowspan="<?= count($activities) ?>"><?= nl2br(htmlspecialchars($projectTitle ?: '—')) ?></td>
                                    <?php endif; ?>
                                    <td><?= nl2br(htmlspecialchars((string) ($activity['training_activity'] ?? '—'))) ?></td>
                                    <td><?= htmlspecialchars((string) ($activity['target_date'] ?? '—')) ?></td>
                                    <td><?= htmlspecialchars((string) ($activity['amount'] ?? '—')) ?></td>
                                    <td><?= htmlspecialchars((string) ($activity['source_of_fund'] ?? '—')) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                        <tr>
                            <td></td>
                            <th>TOTAL</th>
                            <td></td>
                            <td><?= htmlspecialchars($summaryField('program_summary_total_amount') ?: '—') ?></td>
                            <td><?= htmlspecialchars($summaryField('program_summary_total_source') ?: '—') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    <?php endif; ?>

    <?php
    $workflowSteps = proposal_workflow_steps($proposal);
    require APP_PATH . '/views/proposals/_approval-workflow.php';
    ?>

    <section class="proposal-section">
        <h3 class="proposal-subtitle">Workflow Actions</h3>
        <div class="actions proposal-form-actions">
<?php
            $submitConfirmMessage = 'Submit this application for review?';
            require APP_PATH . '/views/proposals/_submit-for-review-button.php';
            ?>

            <?php if ($canApprove): ?>
                <form method="post" action="<?= base_url('proposals/' . $proposal['id'] . '/approve') ?>" style="display:inline;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-accent"><?= htmlspecialchars($approveLabel) ?></button>
                </form>
                <form method="post" action="<?= base_url('proposals/' . $proposal['id'] . '/return') ?>" class="proposal-review-form">
                    <?= csrf_field() ?>
                    <input name="comment" placeholder="Revision comments (required)" required>
                    <button type="submit" class="btn btn-outline">Return for Revision</button>
                </form>
            <?php endif; ?>
        </div>
    </section>
</div>

<div class="proposal-paper proposal-history-paper">
    <section class="proposal-section">
        <h2 class="proposal-section-title">Comments &amp; History</h2>
        <?php if (empty($comments)): ?>
            <p>No comments yet.</p>
        <?php else: ?>
            <ul class="proposal-history-list">
                <?php foreach ($comments as $c): ?>
                    <li>
                        <div class="proposal-history-meta">
                            <?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?>
                            • <?= htmlspecialchars($c['action']) ?>
                            <?php if ($c['step']): ?> (<?= htmlspecialchars($c['step']) ?>)<?php endif; ?>
                            • <?= htmlspecialchars($c['created_at']) ?>
                        </div>
                        <div><?= nl2br(htmlspecialchars($c['comment'])) ?></div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
</div>

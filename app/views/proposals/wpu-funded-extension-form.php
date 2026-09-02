<?php
/** @var array|null $proposal */
/** @var string|null $lockedProjectType */

$isEdit = $proposal !== null;
$lockedProjectType = $isEdit ? 'extension' : ($lockedProjectType ?? proposal_nav_scope());
$pageTitle = ($isEdit ? 'Edit' : 'New') . ' WPU Funded Extension Program — RIDE IMS';
$pageHeading = 'Research Extension';
$pageSubtitle = $isEdit
    ? 'Update your WPU Funded Extension Program application before saving or resubmitting.'
    : 'Complete all sections of the WPU Funded Extension Program application form before saving or submitting.';

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

$leaderField = static function (string $key, string $legacyKey, string $fallback = '') use ($field): string {
    $value = $field($key);
    if ($value !== '') {
        return $value;
    }

    $legacy = $field($legacyKey);
    if ($legacy !== '') {
        return $legacy;
    }

    return $fallback;
};

$titlePrefixOptions = ['Prof', 'Dr', 'Mr', 'Mrs', 'Ms', 'Miss'];
$genderOptions = ['Male', 'Female'];

$referenceNumberValue = $field('reference_number');
$leaderFirstNameValue = $leaderField('program_leader_first_name', 'applicant_first_name');
$leaderMiddleNameValue = $leaderField('program_leader_middle_name', 'applicant_middle_name');
$leaderLastNameValue = $leaderField('program_leader_last_name', 'applicant_last_name');
$leaderTitlePrefixValue = $leaderField('program_leader_title_prefix', 'applicant_title_prefix');
$leaderGenderValue = $leaderField('program_leader_gender', 'applicant_sex');
$leaderAcademicRankValue = $leaderField('program_leader_academic_rank', 'applicant_position');
$leaderDateValue = $field('program_leader_date');
$leaderEmailValue = $leaderField('program_leader_email', 'applicant_email');
$leaderContactValue = $leaderField('program_leader_contact_number', 'applicant_contact_number');
$leaderCollegeValue = $leaderField('program_leader_college', 'applicant_college_department');
$leaderDepartmentValue = $field('program_leader_department');

$titleValue = (string) ($proposal['title'] ?? '');
$introductionValue = $field('introduction');
$objectivesValue = $field('objectives');
$expectedOutputsValue = $field('expected_outputs', $field('expected_outcomes'));

$programSummary = $summaryData['program_summary'] ?? [];
if (!is_array($programSummary) || $programSummary === []) {
    $programSummary = [
        [
            'project_title' => '',
            'activities' => [
                ['training_activity' => '', 'target_date' => '', 'amount' => '', 'source_of_fund' => ''],
                ['training_activity' => '', 'target_date' => '', 'amount' => '', 'source_of_fund' => ''],
                ['training_activity' => '', 'target_date' => '', 'amount' => '', 'source_of_fund' => ''],
            ],
        ],
    ];
}

$summaryTotalAmount = $field('program_summary_total_amount');
$summaryTotalSource = $field('program_summary_total_source');
?>

<form class="proposal-paper completed-researches-paper wpu-funded-extension-paper" method="post" action="<?= $isEdit ? base_url('proposals/' . $proposal['id'] . '/update') : base_url('proposals') ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="nav_scope" value="extension">
    <input type="hidden" name="project_type" value="extension">

    <header class="completed-researches-header wpu-funded-extension-header">
        <h2 class="completed-researches-title">APPLICATION FORM</h2>
        <p class="completed-researches-header-line completed-researches-header-line--strong">WPU FUNDED EXTENSION PROGRAM</p>
        <div class="wpu-funded-extension-reference">
            <label for="reference_number">No.</label>
            <input id="reference_number" name="reference_number" value="<?= htmlspecialchars($referenceNumberValue) ?>" placeholder="">
        </div>
        <p class="completed-researches-form-id">WPU-QSF-RDE-VPRDE-29 Rev.00 (09.20.24)</p>
    </header>

    <section class="proposal-section">
        <h2 class="proposal-section-title">Applicant Information</h2>
        <table class="proposal-table">
            <tr>
                <th>Program Leader</th>
                <td>
                    <span class="proposal-inline-label">First/Given name</span>
                    <input name="program_leader_first_name" value="<?= htmlspecialchars($leaderFirstNameValue) ?>" placeholder="First name">
                </td>
                <td>
                    <span class="proposal-inline-label">Middle name</span>
                    <input name="program_leader_middle_name" value="<?= htmlspecialchars($leaderMiddleNameValue) ?>" placeholder="Middle name">
                </td>
                <td>
                    <span class="proposal-inline-label">Last/Family name</span>
                    <input name="program_leader_last_name" value="<?= htmlspecialchars($leaderLastNameValue) ?>" placeholder="Last name">
                </td>
            </tr>
            <tr>
                <th>Title/Prefix</th>
                <td colspan="3">
                    <select name="program_leader_title_prefix">
                        <option value="">Select title/prefix</option>
                        <?php foreach ($titlePrefixOptions as $prefix): ?>
                            <option value="<?= htmlspecialchars($prefix) ?>" <?= $leaderTitlePrefixValue === $prefix ? 'selected' : '' ?>><?= htmlspecialchars($prefix) ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Gender</th>
                <td>
                    <select name="program_leader_gender">
                        <option value="">Select gender</option>
                        <?php foreach ($genderOptions as $gender): ?>
                            <option value="<?= htmlspecialchars($gender) ?>" <?= $leaderGenderValue === $gender ? 'selected' : '' ?>><?= htmlspecialchars($gender) ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <th>Academic Rank</th>
                <td><input name="program_leader_academic_rank" value="<?= htmlspecialchars($leaderAcademicRankValue) ?>" placeholder="Academic rank"></td>
            </tr>
            <tr>
                <th>Date</th>
                <td><input type="date" name="program_leader_date" value="<?= htmlspecialchars($leaderDateValue) ?>"></td>
                <th>E-mail</th>
                <td><input type="email" name="program_leader_email" value="<?= htmlspecialchars($leaderEmailValue) ?>" placeholder="E-mail"></td>
            </tr>
            <tr>
                <th>Contact number</th>
                <td colspan="3"><input name="program_leader_contact_number" value="<?= htmlspecialchars($leaderContactValue) ?>" placeholder="Contact number"></td>
            </tr>
            <tr>
                <th>College</th>
                <td colspan="3"><input name="program_leader_college" value="<?= htmlspecialchars($leaderCollegeValue) ?>" placeholder="College"></td>
            </tr>
            <tr>
                <th>Department</th>
                <td colspan="3"><input name="program_leader_department" value="<?= htmlspecialchars($leaderDepartmentValue) ?>" placeholder="Department"></td>
            </tr>
        </table>
    </section>

    <section class="proposal-section">
        <h2 class="proposal-section-title">Program Information</h2>

        <h3 class="proposal-subtitle">Title of program</h3>
        <input name="title" class="wpu-funded-extension-title-input" value="<?= htmlspecialchars($titleValue) ?>" placeholder="Enter title of program" required>

        <h3 class="proposal-subtitle">Introduction</h3>
        <textarea name="introduction" class="proposal-textarea" placeholder="Provide the program introduction."><?= htmlspecialchars($introductionValue) ?></textarea>

        <h3 class="proposal-subtitle">Objectives</h3>
        <textarea name="objectives" class="proposal-textarea" placeholder="State the program objectives."><?= htmlspecialchars($objectivesValue) ?></textarea>

        <h3 class="proposal-subtitle">Expected outputs</h3>
        <textarea name="expected_outputs" class="proposal-textarea" placeholder="Describe the expected outputs of the program."><?= htmlspecialchars($expectedOutputsValue) ?></textarea>
    </section>

    <section class="proposal-section">
        <h3 class="proposal-subtitle">Program Summary</h3>
        <div class="proposal-table-wrap">
            <table class="proposal-table wpu-funded-extension-summary-table" id="wpu-extension-summary-table">
                <thead>
                    <tr>
                        <th>Project Title</th>
                        <th>Trainings/Activities</th>
                        <th>Target Date</th>
                        <th>Amount</th>
                        <th>Source of Fund</th>
                        <th class="wpu-funded-extension-summary-actions-col">Action</th>
                    </tr>
                </thead>
                <tbody id="wpu-extension-summary-body">
                    <?php foreach ($programSummary as $projectIndex => $project): ?>
                        <?php
                        if (!is_array($project)) {
                            continue;
                        }
                        $activities = is_array($project['activities'] ?? null) ? $project['activities'] : [];
                        if ($activities === []) {
                            $activities = [['training_activity' => '', 'target_date' => '', 'amount' => '', 'source_of_fund' => '']];
                        }
                        $projectTitle = trim((string) ($project['project_title'] ?? ''));
                        ?>
                        <?php foreach ($activities as $activityIndex => $activity): ?>
                            <?php
                            if (!is_array($activity)) {
                                continue;
                            }
                            ?>
                            <tr class="wpu-extension-summary-row" data-project-index="<?= (int) $projectIndex ?>">
                                <?php if ($activityIndex === 0): ?>
                                    <td class="wpu-extension-project-title-cell" rowspan="<?= count($activities) ?>">
                                        <textarea
                                            name="program_summary[<?= (int) $projectIndex ?>][project_title]"
                                            class="proposal-textarea proposal-textarea-compact"
                                            rows="3"
                                            data-autoresize
                                            placeholder="Project title"
                                        ><?= htmlspecialchars($projectTitle) ?></textarea>
                                    </td>
                                <?php endif; ?>
                                <td>
                                    <textarea
                                        name="program_summary[<?= (int) $projectIndex ?>][activities][<?= (int) $activityIndex ?>][training_activity]"
                                        class="proposal-textarea proposal-textarea-compact"
                                        rows="2"
                                        data-autoresize
                                        placeholder="Training or activity"
                                    ><?= htmlspecialchars((string) ($activity['training_activity'] ?? '')) ?></textarea>
                                </td>
                                <td><input type="date" name="program_summary[<?= (int) $projectIndex ?>][activities][<?= (int) $activityIndex ?>][target_date]" value="<?= htmlspecialchars((string) ($activity['target_date'] ?? '')) ?>"></td>
                                <td><input type="text" inputmode="decimal" class="wpu-extension-amount-input" name="program_summary[<?= (int) $projectIndex ?>][activities][<?= (int) $activityIndex ?>][amount]" value="<?= htmlspecialchars((string) ($activity['amount'] ?? '')) ?>" placeholder="Amount"></td>
                                <td><input name="program_summary[<?= (int) $projectIndex ?>][activities][<?= (int) $activityIndex ?>][source_of_fund]" value="<?= htmlspecialchars((string) ($activity['source_of_fund'] ?? '')) ?>" placeholder="Source of fund"></td>
                                <td class="wpu-funded-extension-summary-actions-col">
                                    <?php if ($activityIndex === 0): ?>
                                        <button type="button" class="btn btn-outline btn-sm wpu-extension-add-activity" data-project-index="<?= (int) $projectIndex ?>">Add activity</button>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-outline btn-sm wpu-extension-remove-activity">Remove</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                    <tr class="wpu-extension-summary-total-row">
                        <td></td>
                        <th>TOTAL</th>
                        <td></td>
                        <td>
                            <input
                                id="program_summary_total_amount"
                                name="program_summary_total_amount"
                                class="wpu-extension-total-amount"
                                value="<?= htmlspecialchars($summaryTotalAmount) ?>"
                                placeholder="0.00"
                                readonly
                                tabindex="-1"
                                aria-readonly="true"
                            >
                        </td>
                        <td><input name="program_summary_total_source" value="<?= htmlspecialchars($summaryTotalSource) ?>" placeholder="Total source of fund"></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="proposal-coauthors-actions">
            <button type="button" id="wpu-extension-add-project" class="btn btn-outline btn-sm">Add project</button>
        </div>
    </section>

    <?php
    $workflowSteps = proposal_workflow_steps($proposal ?? ['status' => 'draft', 'project_type' => 'extension']);
    require APP_PATH . '/views/proposals/_approval-workflow.php';
    ?>

    <div class="actions proposal-form-actions">
        <button type="submit" class="btn"><?= $isEdit ? 'Save Changes' : 'Save Draft' ?></button>
        <?php if ($isEdit): ?>
            <a class="btn btn-outline" href="<?= base_url('proposals/' . $proposal['id']) ?>">Cancel</a>
        <?php endif; ?>
    </div>
</form>

<template id="wpu-extension-activity-row-template">
    <tr class="wpu-extension-summary-row">
        <td>
            <textarea class="proposal-textarea proposal-textarea-compact" rows="2" data-field="training_activity" data-autoresize placeholder="Training or activity"></textarea>
        </td>
        <td><input type="date" data-field="target_date"></td>
        <td><input type="text" inputmode="decimal" class="wpu-extension-amount-input" data-field="amount" placeholder="Amount"></td>
        <td><input data-field="source_of_fund" placeholder="Source of fund"></td>
        <td class="wpu-funded-extension-summary-actions-col">
            <button type="button" class="btn btn-outline btn-sm wpu-extension-remove-activity">Remove</button>
        </td>
    </tr>
</template>

<template id="wpu-extension-project-template">
    <tr class="wpu-extension-summary-row" data-project-index="">
        <td class="wpu-extension-project-title-cell" rowspan="1">
            <textarea class="proposal-textarea proposal-textarea-compact" rows="3" data-field="project_title" data-autoresize placeholder="Project title"></textarea>
        </td>
        <td>
            <textarea class="proposal-textarea proposal-textarea-compact" rows="2" data-field="training_activity" data-autoresize placeholder="Training or activity"></textarea>
        </td>
        <td><input type="date" data-field="target_date"></td>
        <td><input type="text" inputmode="decimal" class="wpu-extension-amount-input" data-field="amount" placeholder="Amount"></td>
        <td><input data-field="source_of_fund" placeholder="Source of fund"></td>
        <td class="wpu-funded-extension-summary-actions-col">
            <button type="button" class="btn btn-outline btn-sm wpu-extension-add-activity">Add activity</button>
            <button type="button" class="btn btn-outline btn-sm wpu-extension-remove-activity">Remove</button>
        </td>
    </tr>
</template>

<script>
(() => {
    const tbody = document.getElementById('wpu-extension-summary-body');
    const totalRow = tbody?.querySelector('.wpu-extension-summary-total-row');
    const totalAmountInput = document.getElementById('program_summary_total_amount');
    const addProjectButton = document.getElementById('wpu-extension-add-project');
    const activityTemplate = document.getElementById('wpu-extension-activity-row-template');
    const projectTemplate = document.getElementById('wpu-extension-project-template');
    const autoResizeTextareas = () => Array.from(document.querySelectorAll('textarea[data-autoresize]'));

    const resizeTextarea = (textarea) => {
        textarea.style.height = 'auto';
        textarea.style.height = `${textarea.scrollHeight}px`;
    };

    autoResizeTextareas().forEach((textarea) => {
        resizeTextarea(textarea);
        textarea.addEventListener('input', () => resizeTextarea(textarea));
    });

    const parseAmount = (value) => {
        const cleaned = String(value ?? '').replace(/,/g, '').replace(/[^\d.-]/g, '');
        if (cleaned === '' || cleaned === '-' || cleaned === '.') {
            return 0;
        }
        const num = Number.parseFloat(cleaned);
        return Number.isFinite(num) ? num : 0;
    };

    const formatAmount = (total) => total.toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });

    const updateTotalAmount = () => {
        if (!totalAmountInput || !tbody) {
            return;
        }
        let total = 0;
        tbody.querySelectorAll('.wpu-extension-amount-input').forEach((input) => {
            total += parseAmount(input.value);
        });
        totalAmountInput.value = formatAmount(total);
    };

    const bindAmountInput = (input) => {
        if (!(input instanceof HTMLInputElement) || input.dataset.amountBound === '1') {
            return;
        }
        input.dataset.amountBound = '1';
        input.addEventListener('input', updateTotalAmount);
        input.addEventListener('change', updateTotalAmount);
    };

    const projectGroups = () => {
        const groups = new Map();
        tbody?.querySelectorAll('.wpu-extension-summary-row:not(.wpu-extension-summary-total-row)').forEach((row) => {
            const index = row.dataset.projectIndex ?? '0';
            if (!groups.has(index)) {
                groups.set(index, []);
            }
            groups.get(index).push(row);
        });
        return groups;
    };

    const syncProjectRowspans = () => {
        projectGroups().forEach((rows) => {
            const titleCell = rows[0]?.querySelector('.wpu-extension-project-title-cell');
            if (!titleCell) {
                return;
            }
            titleCell.rowSpan = rows.length;
            rows.slice(1).forEach((row) => row.querySelector('.wpu-extension-project-title-cell')?.remove());
        });
    };

    const reindexSummary = () => {
        let projectIndex = 0;
        projectGroups().forEach((rows) => {
            rows.forEach((row, activityIndex) => {
                row.dataset.projectIndex = String(projectIndex);
                const titleCell = row.querySelector('.wpu-extension-project-title-cell');
                const titleInput = titleCell?.querySelector('textarea, [data-field="project_title"]');
                if (titleInput) {
                    titleInput.name = `program_summary[${projectIndex}][project_title]`;
                }

                row.querySelectorAll('[data-field]').forEach((input) => {
                    const field = input.dataset.field;
                    if (field === 'project_title') {
                        return;
                    }
                    input.name = `program_summary[${projectIndex}][activities][${activityIndex}][${field}]`;
                    input.removeAttribute('data-field');
                });

                row.querySelectorAll('textarea:not([name]), input:not([name])').forEach((input) => {
                    if (input.name) {
                        return;
                    }
                    const field = input.dataset.field;
                    if (!field || field === 'project_title') {
                        return;
                    }
                    input.name = `program_summary[${projectIndex}][activities][${activityIndex}][${field}]`;
                });

                row.querySelectorAll('textarea[name], input[name]').forEach((input) => {
                    const match = input.name.match(/^program_summary\[\d+]\[activities]\[\d+]\[(\w+)]$/);
                    if (match) {
                        input.name = `program_summary[${projectIndex}][activities][${activityIndex}][${match[1]}]`;
                    }
                });

                const addActivityBtn = row.querySelector('.wpu-extension-add-activity');
                if (addActivityBtn) {
                    addActivityBtn.dataset.projectIndex = String(projectIndex);
                    addActivityBtn.style.display = activityIndex === 0 ? '' : 'none';
                }
            });
            projectIndex += 1;
        });
        syncProjectRowspans();
        updateTotalAmount();
    };

    const bindRow = (row) => {
        row.querySelectorAll('.wpu-extension-amount-input').forEach(bindAmountInput);
        row.querySelector('.wpu-extension-remove-activity')?.addEventListener('click', () => {
            const index = row.dataset.projectIndex ?? '0';
            const rows = projectGroups().get(index) ?? [];
            if (rows.length <= 1 && projectGroups().size <= 1) {
                row.querySelectorAll('textarea, input').forEach((input) => {
                    if (input instanceof HTMLInputElement || input instanceof HTMLTextAreaElement) {
                        input.value = '';
                    }
                });
                updateTotalAmount();
                return;
            }
            if (rows.length <= 1) {
                rows.forEach((projectRow) => projectRow.remove());
            } else {
                row.remove();
            }
            reindexSummary();
            updateTotalAmount();
        });

        row.querySelector('.wpu-extension-add-activity')?.addEventListener('click', () => {
            if (!activityTemplate || !tbody || !totalRow) {
                return;
            }
            const fragment = activityTemplate.content.cloneNode(true);
            const newRow = fragment.querySelector('tr');
            if (!newRow) {
                return;
            }
            newRow.dataset.projectIndex = row.dataset.projectIndex ?? '0';
            const groupRows = projectGroups().get(newRow.dataset.projectIndex) ?? [];
            const lastRow = groupRows[groupRows.length - 1] ?? row;
            lastRow.after(newRow);
            newRow.querySelectorAll('textarea[data-autoresize]').forEach((textarea) => {
                resizeTextarea(textarea);
                textarea.addEventListener('input', () => resizeTextarea(textarea));
            });
            reindexSummary();
            bindRow(newRow);
        });
    };

    tbody?.querySelectorAll('.wpu-extension-summary-row:not(.wpu-extension-summary-total-row)').forEach(bindRow);

    addProjectButton?.addEventListener('click', () => {
        if (!projectTemplate || !tbody || !totalRow) {
            return;
        }
        const fragment = projectTemplate.content.cloneNode(true);
        const newRow = fragment.querySelector('tr');
        if (!newRow) {
            return;
        }
        totalRow.before(newRow);
        newRow.querySelectorAll('textarea[data-autoresize]').forEach((textarea) => {
            resizeTextarea(textarea);
            textarea.addEventListener('input', () => resizeTextarea(textarea));
        });
        reindexSummary();
        bindRow(newRow);
    });

    tbody?.querySelectorAll('.wpu-extension-amount-input').forEach(bindAmountInput);
    reindexSummary();
    updateTotalAmount();
})();
</script>

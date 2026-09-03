<?php
/** @var array|null $proposal */
/** @var list<array> $colleges */
/** @var string $collegeName */

$isEdit = $proposal !== null;
$pageTitle = ($isEdit ? 'Edit' : 'New') . ' Attendance Sheet — RIDE IMS';
$pageHeading = 'Attendance Sheet';
$pageSubtitle = $isEdit
    ? 'Update the attendance form before saving or resubmitting.'
    : 'Complete the attendance form (WPU-QSF-RIDE-ESO-17).';
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

$activityTitleValue = $field('activity_title');
$venueValue = $field('venue');
$dateValue = $field('activity_date');
$timeAmValue = $field('time_am');
$timePmValue = $field('time_pm');
$attendees = is_array($summaryData['attendees'] ?? null) ? $summaryData['attendees'] : [];
$collegeDisplay = is_string($summaryData['college_name'] ?? null) && $summaryData['college_name'] !== ''
    ? $summaryData['college_name']
    : $collegeName;
?>

<form class="proposal-paper completed-researches-paper trainings-conducted-paper eso-extension-paper attendance-sheet-paper" method="post" action="<?= $isEdit ? base_url('proposals/' . $proposal['id'] . '/attendance-sheet') : base_url('proposals/attendance-sheet') ?>">
    <?= csrf_field() ?>
    <?php if (proposal_nav_scope() !== null): ?>
        <input type="hidden" name="nav_scope" value="extension">
    <?php endif; ?>

    <header class="completed-researches-header">
        <p class="completed-researches-header-line">Republic of the Philippines</p>
        <p class="completed-researches-header-line completed-researches-header-line--strong">WESTERN PHILIPPINES UNIVERSITY</p>
        <p class="completed-researches-header-line">Office of Extension Services</p>
        <h2 class="completed-researches-title">ATTENDANCE FORM</h2>
        <p class="completed-researches-form-id">WPU-QSF-RIDE-ESO-17 Rev.00 (08.15.25)</p>
    </header>

    <section class="proposal-section">
        <table class="proposal-table completed-researches-meta-table">
            <tr>
                <th>College</th>
                <td colspan="3">
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
                        <input type="hidden" name="college_name" id="attendance-sheet-college-name" value="">
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Title of Activity</th>
                <td colspan="3">
                    <textarea name="activity_title" class="proposal-textarea" rows="2" placeholder="Enter the title of the activity." required><?= htmlspecialchars($activityTitleValue) ?></textarea>
                </td>
            </tr>
            <tr>
                <th>Venue</th>
                <td>
                    <input name="venue" value="<?= htmlspecialchars($venueValue) ?>" placeholder="Enter the venue">
                </td>
                <th>Date</th>
                <td>
                    <input type="date" name="activity_date" value="<?= htmlspecialchars($dateValue) ?>">
                </td>
            </tr>
            <tr>
                <th>Time</th>
                <td colspan="3">
                    <div class="attendance-time-fields">
                        <label>
                            AM
                            <input type="time" name="time_am" value="<?= htmlspecialchars($timeAmValue) ?>">
                        </label>
                        <label>
                            PM
                            <input type="time" name="time_pm" value="<?= htmlspecialchars($timePmValue) ?>">
                        </label>
                    </div>
                </td>
            </tr>
        </table>
    </section>

    <aside class="attendance-privacy-notice" aria-label="Privacy notice">
        <h3>Privacy Notice</h3>
        <p>
            For this activity, we collect your names, sex, office/agency affiliation, position/designation, and email address or mobile number when you register for purposes of coordination, printing of certificates, and in compliance to GAD requirements. Through this attendance sheet, we also collect your signature as proof of attendance. To the extent permitted or required by law, we may also share photos and videos of this activity/meeting/event to promote WPU through brochures, website posts, and social media.
        </p>
        <p>
            All personal information collected will be stored in a secure location and only authorized staff will have access to them.
        </p>
    </aside>

    <?php $readOnly = false; require APP_PATH . '/views/proposals/_attendance-sheet-table.php'; ?>

    <?php
    $workflowProposal = $proposal ?? [
        'status' => 'draft',
        'project_type' => 'extension',
        'summary' => json_encode(['form_type' => 'attendance_sheet']),
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

<template id="attendance-sheet-row-template">
    <tr class="attendance-sheet-row">
        <td class="attendance-no-col attendance-row-number"></td>
        <td><input type="text" data-field="name" placeholder="Full name"></td>
        <td><input type="text" data-field="position" placeholder="Position / designation"></td>
        <td>
            <select data-field="sex" aria-label="Sex">
                <option value="">Select</option>
                <?php foreach (attendance_sheet_sex_options() as $value => $label): ?>
                    <option value="<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($label) ?></option>
                <?php endforeach; ?>
            </select>
        </td>
        <td><input type="text" data-field="contact_number" placeholder="Mobile number"></td>
        <td><input type="text" data-field="office" placeholder="Office / unit / agency"></td>
        <td><input type="email" data-field="email" placeholder="Email address"></td>
        <td><input type="text" data-field="signature" placeholder="Typed name"></td>
        <td class="completed-researches-actions-col">
            <button type="button" class="btn btn-sm btn-outline attendance-sheet-remove-row" title="Remove row">Remove</button>
        </td>
    </tr>
</template>

<script>
(() => {
    const collegeSelect = document.querySelector('select[name="college_id"]');
    const collegeNameInput = document.getElementById('attendance-sheet-college-name');
    if (collegeSelect && collegeNameInput) {
        const syncCollegeName = () => {
            const option = collegeSelect.options[collegeSelect.selectedIndex];
            collegeNameInput.value = option?.dataset?.name ?? '';
        };
        collegeSelect.addEventListener('change', syncCollegeName);
        syncCollegeName();
    }

    const table = document.querySelector('.attendance-sheet-table');
    const rowTemplate = document.getElementById('attendance-sheet-row-template');
    const addButton = document.querySelector('.attendance-sheet-add-row');
    const fieldNames = ['name', 'position', 'sex', 'contact_number', 'office', 'email', 'signature'];

    const assignNames = (row, index) => {
        fieldNames.forEach((field) => {
            const input = row.querySelector(`[name="attendees[${index}][${field}]"], [data-field="${field}"]`);
            if (!input) {
                return;
            }
            input.name = `attendees[${index}][${field}]`;
            input.removeAttribute('data-field');
        });
        const numberCell = row.querySelector('.attendance-row-number');
        if (numberCell) {
            numberCell.textContent = String(index + 1);
        }
    };

    const reindexRows = () => {
        table?.querySelectorAll('.attendance-sheet-row').forEach((row, index) => {
            assignNames(row, index);
        });
    };

    const bindRow = (row) => {
        row.querySelector('.attendance-sheet-remove-row')?.addEventListener('click', () => {
            const rows = table?.querySelectorAll('.attendance-sheet-row') ?? [];
            if (rows.length <= 1) {
                row.querySelectorAll('input, select').forEach((input) => {
                    if (input instanceof HTMLInputElement || input instanceof HTMLSelectElement) {
                        input.value = '';
                    }
                });
                return;
            }
            row.remove();
            reindexRows();
        });
    };

    table?.querySelectorAll('.attendance-sheet-row').forEach((row) => bindRow(row));
    reindexRows();

    addButton?.addEventListener('click', () => {
        const tbody = table?.querySelector('tbody');
        const row = rowTemplate?.content.firstElementChild?.cloneNode(true);
        if (!(row instanceof HTMLTableRowElement) || !tbody) {
            return;
        }
        tbody.appendChild(row);
        bindRow(row);
        reindexRows();
    });

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

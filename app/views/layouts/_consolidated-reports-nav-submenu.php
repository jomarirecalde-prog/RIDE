<?php

/** @var string $menuScope 'research'|'extension' */

$scopeQuery = proposal_nav_scope_query($menuScope);

$navActive = static fn (bool $active): bool => proposal_nav_scoped_active($active, $menuScope);

$navLabel = static fn (string $label): string => htmlspecialchars(proposal_nav_submenu_label($label));

?>

<?php if ($showConsolidatedOngoingResearchesNav): ?>

    <a href="<?= base_url('proposals/create/consolidated-ongoing-researches' . $scopeQuery) ?>" class="nav-subitem<?= $navActive($consolidatedOngoingResearchesNavActive) ? ' active' : '' ?>">

        <span>Consolidated Ongoing Researches</span>

    </a>

<?php endif; ?>

<?php if ($showConsolidatedResearchOutputPublishedNav): ?>

    <a href="<?= base_url('proposals/create/consolidated-research-output-published' . $scopeQuery) ?>" class="nav-subitem<?= $navActive($consolidatedResearchOutputPublishedNavActive) ? ' active' : '' ?>">

        <span><?= $navLabel('Consolidated Research Output Published') ?></span>

    </a>

<?php endif; ?>

<?php if ($showConsolidatedResearchOutputPresentedNav): ?>

    <a href="<?= base_url('proposals/create/consolidated-research-output-presented' . $scopeQuery) ?>" class="nav-subitem<?= $navActive($consolidatedResearchOutputPresentedNavActive) ? ' active' : '' ?>">

        <span>Consolidated Research Output Presented</span>

    </a>

<?php endif; ?>

<?php if ($showConsolidatedCommercializedNav): ?>

    <a href="<?= base_url('proposals/create/consolidated-commercialized' . $scopeQuery) ?>" class="nav-subitem<?= $navActive($consolidatedCommercializedNavActive) ? ' active' : '' ?>">

        <span>Consolidated Commercialized</span>

    </a>

<?php endif; ?>

<?php if ($showConsolidatedResultedInExtensionNav): ?>

    <a href="<?= base_url('proposals/create/consolidated-resulted-in-extension' . $scopeQuery) ?>" class="nav-subitem<?= $navActive($consolidatedResultedInExtensionNavActive) ? ' active' : '' ?>">

        <span>Consolidated Resulted in Extension</span>

    </a>

<?php endif; ?>

<?php if ($showConsolidatedJournalCitationNav): ?>

    <a href="<?= base_url('proposals/create/consolidated-journal-citation' . $scopeQuery) ?>" class="nav-subitem<?= $navActive($consolidatedJournalCitationNavActive) ? ' active' : '' ?>">

        <span>Consolidated Journal Citation</span>

    </a>

<?php endif; ?>

<?php if ($showConsolidatedBookCitationNav): ?>

    <a href="<?= base_url('proposals/create/consolidated-book-citation' . $scopeQuery) ?>" class="nav-subitem<?= $navActive($consolidatedBookCitationNavActive) ? ' active' : '' ?>">

        <span>Consolidated Book Citation</span>

    </a>

<?php endif; ?>

<?php if ($showConsolidatedInventionsUmCopyrightsNav): ?>

    <a href="<?= base_url('proposals/create/consolidated-inventions-um-copyrights' . $scopeQuery) ?>" class="nav-subitem<?= $navActive($consolidatedInventionsUmCopyrightsNavActive) ? ' active' : '' ?>">

        <span>Consolidated Inventions, UM, Copyrights</span>

    </a>

<?php endif; ?>

<?php if ($showConsolidatedLinkagesNav): ?>

    <a href="<?= base_url('proposals/create/consolidated-linkages' . $scopeQuery) ?>" class="nav-subitem<?= $navActive($consolidatedLinkagesNavActive) ? ' active' : '' ?>">

        <span>Consolidated Linkages</span>

    </a>

<?php endif; ?>

<?php if ($showConsolidatedCompletedResearchesNav): ?>

    <a href="<?= base_url('proposals/create/consolidated-completed-researches' . $scopeQuery) ?>" class="nav-subitem<?= $navActive($consolidatedCompletedResearchesNavActive) ? ' active' : '' ?>">

        <span><?= $navLabel('Consolidated Completed Researches') ?></span>

    </a>

<?php endif; ?>

<?php if ($showProgressReportNav): ?>

    <a href="<?= base_url(\App\Support\MonitoringRoles::isCoordinatorResearch() ? 'proposals/terminal-report-registry?form_type=progress_report' : 'proposals/create/progress-report' . $scopeQuery) ?>" class="nav-subitem<?= $navActive($progressReportNavActive) ? ' active' : '' ?>">

        <span><?= $navLabel('Progress Report') ?></span>

    </a>

<?php endif; ?>

<?php if ($showObrMatrixNav): ?>

    <a href="<?= base_url('proposals/create/obr-matrix' . $scopeQuery) ?>" class="nav-subitem<?= $navActive($obrMatrixNavActive) ? ' active' : '' ?>">

        <span><?= $navLabel('OBR Matrix') ?></span>

    </a>

<?php endif; ?>

<?php

/** @var list<array<string, mixed>> $papers */

/** @var list<array<string, mixed>> $presentations */

/** @var array<int, list<array<string, mixed>>> $paperAttachments */

/** @var array<int, list<array<string, mixed>>> $presentationAttachments */



$pageTitle = 'My Scholarly Output — RIDE IMS';

$pageHeading = 'Published Papers & Presentations';

$pageSubtitle = 'Record your published papers and conference paper presentations for university monitoring.';

?>



<div class="grid grid-2">

    <div class="card">

        <h2>Add Published Paper</h2>

        <form method="post" action="<?= base_url('scholarly/papers') ?>" enctype="multipart/form-data">

            <?= csrf_field() ?>



            <label for="paper_title">Paper Title</label>

            <input id="paper_title" name="title" required maxlength="500">



            <label for="paper_authors">Co-authors</label>

            <input id="paper_authors" name="authors" maxlength="500" placeholder="Optional co-author list">



            <label for="paper_journal">Journal Name</label>

            <input id="paper_journal" name="journal_name" required maxlength="255">



            <label for="paper_date">Publication Date</label>

            <input id="paper_date" type="date" name="publication_date">



            <label for="paper_year">Publication Year</label>

            <input id="paper_year" type="number" name="publication_year" min="1900" max="2100" value="<?= (int) date('Y') ?>">



            <label for="paper_status">Status</label>

            <select id="paper_status" name="status">

                <option value="published">Published</option>

                <option value="accepted">Accepted</option>

                <option value="in_press">In Press</option>

            </select>



            <label for="paper_indexing">Indexing</label>

            <input id="paper_indexing" name="indexing" maxlength="120" placeholder="e.g. Scopus, Web of Science">



            <label for="paper_doi">DOI</label>

            <input id="paper_doi" name="doi" maxlength="120">



            <label for="paper_link">Link</label>

            <input id="paper_link" type="url" name="link" maxlength="500" placeholder="https://">



            <label for="paper_supporting_documents">Supporting Documents</label>

            <input id="paper_supporting_documents" type="file" name="supporting_documents[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip">

            <p class="muted" style="margin-top: 0.25rem;">Optional. Upload copies, acceptance letters, or other proof (PDF, Word, Excel, images, or ZIP; max 10 MB each).</p>



            <div class="actions">

                <button type="submit" class="btn btn-accent">Save Paper</button>

            </div>

        </form>

    </div>



    <div class="card">

        <h2>Add Paper Presentation</h2>

        <form method="post" action="<?= base_url('scholarly/presentations') ?>" enctype="multipart/form-data">

            <?= csrf_field() ?>



            <label for="presentation_title">Presentation Title</label>

            <input id="presentation_title" name="title" required maxlength="500">



            <label for="presentation_conference">Conference / Event</label>

            <input id="presentation_conference" name="conference_name" required maxlength="255">



            <label for="presentation_type">Type</label>

            <select id="presentation_type" name="presentation_type">

                <option value="oral">Oral</option>

                <option value="poster">Poster</option>

                <option value="virtual">Virtual</option>

                <option value="other">Other</option>

            </select>



            <label for="presentation_date">Presentation Date</label>

            <input id="presentation_date" type="date" name="presentation_date">



            <label for="presentation_location">Location</label>

            <input id="presentation_location" name="location" maxlength="255" placeholder="City, country, or venue">



            <label class="proposal-checkbox-row">

                <input type="checkbox" name="is_international" value="1">

                International presentation

            </label>



            <label for="presentation_supporting_documents">Supporting Documents</label>

            <input id="presentation_supporting_documents" type="file" name="supporting_documents[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip">

            <p class="muted" style="margin-top: 0.25rem;">Optional. Upload certificates, programs, or other proof (PDF, Word, Excel, images, or ZIP; max 10 MB each).</p>



            <div class="actions">

                <button type="submit" class="btn btn-accent">Save Presentation</button>

            </div>

        </form>

    </div>

</div>



<div class="card" style="margin-top: 1.5rem;">

    <h2>My Published Papers</h2>

    <?php if (empty($papers)): ?>

        <p class="muted">You have not recorded any published papers yet.</p>

    <?php else: ?>

        <div class="proposal-table-wrap">

            <table class="proposal-table">

                <thead>

                    <tr>

                        <th>Title</th>

                        <th>Journal</th>

                        <th>Year</th>

                        <th>Status</th>

                        <th>Supporting Documents</th>

                        <th></th>

                    </tr>

                </thead>

                <tbody>

                <?php foreach ($papers as $paper): ?>
                    <?php
                    $attachments = $paperAttachments[(int) $paper['id']] ?? [];
                    $uploadAction = base_url('scholarly/papers/' . (int) $paper['id'] . '/attachments');
                    $downloadBase = 'scholarly/attachments';
                    $canManage = true;
                    ?>
                    <tr>

                        <td>

                            <strong><?= htmlspecialchars((string) $paper['title']) ?></strong>

                            <?php if (!empty($paper['indexing'])): ?>

                                <br><span class="muted"><?= htmlspecialchars((string) $paper['indexing']) ?></span>

                            <?php endif; ?>

                        </td>

                        <td><?= htmlspecialchars((string) $paper['journal_name']) ?></td>

                        <td><?= htmlspecialchars((string) ($paper['publication_year'] ?? ($paper['publication_date'] ? substr((string) $paper['publication_date'], 0, 4) : '—'))) ?></td>

                        <td><?= htmlspecialchars(ucwords(str_replace('_', ' ', (string) $paper['status']))) ?></td>

                        <td>

                            <?php if (empty($attachments)): ?>

                                <span class="muted">None</span>

                            <?php endif; ?>

                            <?php include __DIR__ . '/_attachments.php'; ?>

                        </td>

                        <td>

                            <form method="post" action="<?= base_url('scholarly/papers/' . (int) $paper['id'] . '/delete') ?>" onsubmit="return confirm('Remove this published paper?');">

                                <?= csrf_field() ?>

                                <button type="submit" class="btn btn-sm btn-danger">Remove</button>

                            </form>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

</div>



<div class="card" style="margin-top: 1.5rem;">

    <h2>My Paper Presentations</h2>

    <?php if (empty($presentations)): ?>

        <p class="muted">You have not recorded any paper presentations yet.</p>

    <?php else: ?>

        <div class="proposal-table-wrap">

            <table class="proposal-table">

                <thead>

                    <tr>

                        <th>Title</th>

                        <th>Conference</th>

                        <th>Date</th>

                        <th>Type</th>

                        <th>Supporting Documents</th>

                        <th></th>

                    </tr>

                </thead>

                <tbody>

                <?php foreach ($presentations as $presentation): ?>

                    <?php

                    $attachments = $presentationAttachments[(int) $presentation['id']] ?? [];

                    $uploadAction = base_url('scholarly/presentations/' . (int) $presentation['id'] . '/attachments');

                    $downloadBase = 'scholarly/attachments';

                    $canManage = true;

                    ?>

                    <tr>

                        <td>

                            <strong><?= htmlspecialchars((string) $presentation['title']) ?></strong>

                            <?php if ((int) $presentation['is_international'] === 1): ?>

                                <br><span class="muted">International</span>

                            <?php endif; ?>

                        </td>

                        <td><?= htmlspecialchars((string) $presentation['conference_name']) ?></td>

                        <td><?= htmlspecialchars((string) ($presentation['presentation_date'] ?? '—')) ?></td>

                        <td><?= htmlspecialchars(ucfirst((string) $presentation['presentation_type'])) ?></td>

                        <td>

                            <?php if (empty($attachments)): ?>

                                <span class="muted">None</span>

                            <?php endif; ?>

                            <?php include __DIR__ . '/_attachments.php'; ?>

                        </td>

                        <td>

                            <form method="post" action="<?= base_url('scholarly/presentations/' . (int) $presentation['id'] . '/delete') ?>" onsubmit="return confirm('Remove this presentation?');">

                                <?= csrf_field() ?>

                                <button type="submit" class="btn btn-sm btn-danger">Remove</button>

                            </form>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

</div>


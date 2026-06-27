<?php
/** @var array  $results */
/** @var bool   $submitted */
/** @var string $keyword */
/** @var string $tag */
?>
<style>
.tbr-explainer { display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:1.25rem; }
@media(max-width:560px){ .tbr-explainer { grid-template-columns:1fr; } }
.tbr-card { background:rgba(121,166,255,0.05); border:1px solid rgba(121,166,255,0.13); border-radius:10px; padding:0.85rem 1rem; display:flex; flex-direction:column; gap:0.4rem; }
.tbr-card--warn { border-color:rgba(255,179,92,0.28); background:rgba(255,179,92,0.05); }
.tbr-card__head { display:flex; justify-content:space-between; align-items:center; gap:0.5rem; }
.tbr-card__type { font-size:0.68rem; font-weight:800; letter-spacing:0.1em; text-transform:uppercase; }
.tbr-card--ok   .tbr-card__type { color:#71efc0; }
.tbr-card--warn .tbr-card__type { color:#ffb35c; }
.tbr-card__status { font-size:0.62rem; font-weight:700; padding:0.18rem 0.5rem; border-radius:99px; letter-spacing:0.05em; text-transform:uppercase; }
.tbr-card--ok   .tbr-card__status { background:rgba(113,239,192,0.12); color:#71efc0; border:1px solid rgba(113,239,192,0.25); }
.tbr-card--warn .tbr-card__status { background:rgba(255,179,92,0.12); color:#ffb35c; border:1px solid rgba(255,179,92,0.25); }
.tbr-card__sub { font-size:0.75rem; color:var(--color-muted); line-height:1.5; }
.tbr-card__sub strong { color:var(--color-text); }
.tbr-field-label { font-size:0.65rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--color-dim); margin-bottom:0.3rem; display:block; }
.tbr-warn-note { display:flex; align-items:flex-start; gap:0.5rem; font-size:0.75rem; color:#ffb35c; background:rgba(255,179,92,0.07); border:1px solid rgba(255,179,92,0.22); border-radius:8px; padding:0.55rem 0.75rem; margin-top:0.5rem; line-height:1.5; }
.tbr-warn-note svg { flex-shrink:0; margin-top:1px; }
</style>
<section class="search-panel">
    <h2>Text-Based Retrieval</h2>
    <div class="tbr-explainer">
        <div class="tbr-card tbr-card--ok">
            <div class="tbr-card__head">
                <span class="tbr-card__type">Tag Search</span>
                <span class="tbr-card__status">Working</span>
            </div>
            <p class="tbr-card__sub">Searches across <strong>all file types</strong> — photo, audio, PDF, and video. Type any tag that was added to a file, e.g. <strong>formal</strong>, <strong>long</strong>, <strong>neutral</strong>.</p>
        </div>
        <div class="tbr-card tbr-card--warn">
            <div class="tbr-card__head">
                <span class="tbr-card__type">Keyword Search</span>
                <span class="tbr-card__status">Limited</span>
            </div>
            <p class="tbr-card__sub">Searches extracted text using MySQL FULLTEXT. Currently has a technical issue — <strong>no results will be returned</strong>. Use tag search instead.</p>
        </div>
    </div>
    <form class="form-grid" method="get" action="<?= e(url('/search-text')) ?>">
        <input type="hidden" name="_search" value="1">
        <div class="form-group">
            <span class="tbr-field-label">Keyword &mdash; searches extracted text</span>
            <input id="keyword" name="keyword" type="text"
                   value="<?= e($keyword ?? '') ?>"
                   placeholder="e.g. resume, project, report...">
            <label for="keyword" style="display:none">Keyword</label>
            <p class="tbr-warn-note">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Keyword search is currently non-functional due to a technical error in the text extraction pipeline. Searching by keyword will return no results.
            </p>
        </div>
        <div class="form-group">
            <span class="tbr-field-label">Tag &mdash; searches all file types</span>
            <input id="tag" name="tag" type="text"
                   value="<?= e($tag ?? '') ?>"
                   placeholder="e.g. formal, audio, long, neutral...">
            <label for="tag" style="display:none">Tag</label>
        </div>
        <div class="form-actions">
            <button class="button" type="submit">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Search
            </button>
            <a class="button secondary" href="<?= e(url('/search-text')) ?>">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                Clear
            </a>
        </div>
    </form>
</section>

<section class="table-card">
    <h3>Results</h3>
    <?php if (!($submitted ?? false)): ?>
        <p class="muted">Enter a keyword or tag to search.</p>
    <?php elseif (($keyword ?? '') === '' && ($tag ?? '') === ''): ?>
        <p class="muted">Enter a keyword or tag to search.</p>
    <?php elseif (empty($results)): ?>
        <p class="muted">
            No results found
            <?php if (($keyword ?? '') !== ''): ?>for keyword <strong><?= e($keyword) ?></strong><?php endif; ?>
            <?php if (($tag ?? '') !== ''): ?>with tag <strong><?= e($tag) ?></strong><?php endif; ?>.
        </p>
    <?php else: ?>
        <table>
            <thead>
            <tr>
                <th>Student</th>
                <th>File</th>
                <th>Type</th>
                <th>Tags</th>
                <th>Upload Date</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($results as $row): ?>
            <tr data-student-row
                data-name="<?= e($row['full_name']) ?>"
                data-file-type="<?= e($row['file_type']) ?>"
                data-href="<?= e(url('/student-detail?id=' . $row['student_id'])) ?>">
                <td><?= e($row['full_name']) ?></td>
                <td><?= e($row['filename']) ?></td>
                <td><span class="fc-type-badge"><?= e(strtoupper($row['file_type'])) ?></span></td>
                <td>
                    <?php if (!empty($row['tag_list'])): ?>
                        <?php foreach (explode(',', $row['tag_list']) as $t): ?>
                            <span class="tag-pill"><?= e(trim($t)) ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>—<?php endif; ?>
                </td>
                <td><?= fmt_date($row['upload_date']) ?></td>
                <td>
                    <a class="button" href="<?= e(url('/student-detail?id=' . $row['student_id'])) ?>">View</a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

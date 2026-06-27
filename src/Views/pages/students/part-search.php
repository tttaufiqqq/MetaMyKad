<section class="search-panel">
    <form method="get" action="<?= e(url('/students' . (($embed ?? false) ? '?embed=1' : ''))) ?>" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end;">
        <div class="form-group" style="flex:1; min-width:200px;">
            <input type="text" id="name" name="name" value="<?= e($name) ?>" placeholder="Search by name">
            <label for="name">Name</label>
        </div>
        <div class="form-group" style="min-width:180px;">
            <select id="badge" name="badge">
                <option value="">All Badges</option>
                <?php foreach ($badges as $b): ?>
                <option value="<?= e($b) ?>" <?= $badge === $b ? 'selected' : '' ?>><?= e($b) ?></option>
                <?php endforeach; ?>
                <option value="unregistered" <?= $badge === 'unregistered' ? 'selected' : '' ?>>Profile Not Complete</option>
            </select>
            <label for="badge">Badge</label>
        </div>
        <button class="button" type="submit">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            Search
        </button>
        <?php if ($name !== '' || $badge !== ''): ?>
        <a class="button secondary" href="<?= e(url('/students' . (($embed ?? false) ? '?embed=1' : ''))) ?>">Clear</a>
        <?php endif; ?>
    </form>
</section>

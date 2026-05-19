<section class="hero">
    <p class="eyebrow">System Overview</p>
    <h2>MetaMyKad now runs with a custom registry console built for search-heavy coursework.</h2>
    <p>
        Requests now enter through <code>public/index.php</code>, resolve through
        <code>config/routes.php</code>, then render views from <code>src/Views</code> with the
        shared UI shell that ties registration, retrieval, and monitoring into one visual system.
    </p>
    <div class="hero-actions">
        <a class="button" href="<?= e(url('/register')) ?>">Open registration form</a>
        <a class="button secondary" href="<?= e(url('/dashboard')) ?>">Open dashboard</a>
    </div>
</section>

<section class="detail-grid">
    <article>
        <h3>Backend Contract</h3>
        <p>Use <code>database/</code>, <code>config/routes.php</code>, and the PHP models as the source of truth.</p>
    </article>
    <article>
        <h3>Frontend Shell</h3>
        <p>The sidebar, header, footer, cards, forms, and tables now follow the imported template style.</p>
    </article>
    <article>
        <h3>Group Flow</h3>
        <p>Taufiq completes backend contracts first, then the frontend team wires pages against stable data.</p>
    </article>
</section>

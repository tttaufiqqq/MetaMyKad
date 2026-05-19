<section class="search-panel">
    <h2>Text-Based Retrieval</h2>
    <form class="form-grid" method="get" action="<?= e(url('/search-text')) ?>">
        <div>
            <label for="keyword">Keyword Or Phrase</label>
            <input id="keyword" name="keyword" type="text">
        </div>
        <div>
            <label for="tag">Tag</label>
            <input id="tag" name="tag" type="text">
        </div>
        <div>
            <button class="button" type="submit">Search PDF Text</button>
        </div>
    </form>
</section>

<section class="table-card">
    <h3>Result Preview</h3>
    <table class="mt-4">
        <thead>
        <tr>
            <th>Student</th>
            <th>File</th>
            <th>Keyword Match</th>
            <th>Tag</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Sample Student</td>
            <td>algorithm_notes.pdf</td>
            <td>algorithm</td>
            <td>coursework</td>
        </tr>
        </tbody>
    </table>
</section>

<section class="search-panel">
    <h2>Content-Based Retrieval</h2>
    <form class="form-grid two-col" method="get" action="<?= e(url('/search-content')) ?>">
        <div>
            <label for="photo_category">Photo Category</label>
            <select id="photo_category" name="photo_category">
                <option value="">Any</option>
                <option value="formal">Formal</option>
                <option value="non_formal">Non-formal</option>
            </select>
        </div>
        <div>
            <label for="dominant_expression">Dominant Expression</label>
            <select id="dominant_expression" name="dominant_expression">
                <option value="">Any</option>
                <option value="happy">Happy</option>
                <option value="neutral">Neutral</option>
                <option value="sad">Sad</option>
            </select>
        </div>
        <div>
            <label for="audio_duration_tier">Audio Duration Tier</label>
            <select id="audio_duration_tier" name="audio_duration_tier">
                <option value="">Any</option>
                <option value="short">Short</option>
                <option value="medium">Medium</option>
                <option value="long">Long</option>
            </select>
        </div>
        <div>
            <label for="video_resolution_tier">Video Resolution Tier</label>
            <select id="video_resolution_tier" name="video_resolution_tier">
                <option value="">Any</option>
                <option value="SD">SD</option>
                <option value="HD">HD</option>
                <option value="FHD">FHD</option>
                <option value="UHD">UHD</option>
            </select>
        </div>
        <div class="full-span">
            <button class="button" type="submit">Search Content</button>
        </div>
    </form>
</section>

<section class="table-card">
    <h3>Result Preview</h3>
    <table class="mt-4">
        <thead>
        <tr>
            <th>Student</th>
            <th>File Type</th>
            <th>Expression</th>
            <th>Audio Tier</th>
            <th>Video Tier</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Sample Student</td>
            <td>photo</td>
            <td>happy</td>
            <td>-</td>
            <td>-</td>
        </tr>
        </tbody>
    </table>
</section>

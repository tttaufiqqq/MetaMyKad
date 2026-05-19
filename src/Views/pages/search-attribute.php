<section class="search-panel">
    <h2>Attribute-Based Retrieval</h2>
    <form class="form-grid two-col" method="get" action="<?= e(url('/search-attribute')) ?>">
        <div>
            <label for="gender">Gender</label>
            <select id="gender" name="gender">
                <option value="">Any</option>
                <option value="M">Male</option>
                <option value="F">Female</option>
            </select>
        </div>
        <div>
            <label for="state">State Of Birth</label>
            <input id="state" name="state" type="text">
        </div>
        <div>
            <label for="email_category">Email Category</label>
            <select id="email_category" name="email_category">
                <option value="">Any</option>
                <option value="personal">Personal</option>
                <option value="student">Student</option>
                <option value="work">Work</option>
            </select>
        </div>
        <div>
            <label for="badge">Badge</label>
            <select id="badge" name="badge">
                <option value="">Any</option>
                <option value="Pendaftar">Pendaftar</option>
                <option value="Pelajar">Pelajar</option>
                <option value="Aktif">Aktif</option>
                <option value="Dedikasi">Dedikasi</option>
                <option value="Cemerlang">Cemerlang</option>
            </select>
        </div>
        <div class="full-span">
            <button class="button" type="submit">Search</button>
        </div>
    </form>
</section>

<section class="table-card">
    <h3>Result Preview</h3>
    <table class="mt-4">
        <thead>
        <tr>
            <th>Student</th>
            <th>Gender</th>
            <th>State</th>
            <th>Badge</th>
            <th>Matched File Type</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Sample Student</td>
            <td>M</td>
            <td>Johor</td>
            <td>Pelajar</td>
            <td>photo</td>
        </tr>
        </tbody>
    </table>
</section>

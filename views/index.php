<div class="container pt-3">
    <h2>HOME PAGE</h2>
    <p>Welcome to the Home page @ <a href="<?= enroute('get:root') ?>"><?= APP_URL ?></a>.</p>
    <div class="rounded-1 mt-1 mb-1 p-3" style="background-color: #ededed;">
        <table>
            <tr>
                <th class="pe-3 text-end">route</th>
                <td> <code><?= enroute('get:root') ?></code></td>
            </tr>
            <tr>
                <th class="pe-3 text-end">defined route</th>
                <td> <code><?= REQUEST['route'] ?></code></td>
            </tr>
            <tr>
                <th class="pe-3 text-end">method</th>
                <td> <code><?= strtoupper(REQUEST['method']) ?></code></td>
            </tr>
            <tr>
                <th class="pe-3 text-end">query</th>
                <td> <code><?= json_encode(REQUEST['query'], JSON_PRETTY_PRINT) ?></code></td>
            </tr>
        </table>
    </div>
    <small class="text-secondary">Generated from <?= APP_NAME ?></small>
</div>
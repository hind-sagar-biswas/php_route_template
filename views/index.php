<div class="container pt-3">
    <h2>HOME PAGE</h1>
        <p>Welcome to the Home page, <b>Route:</b> <code><?= enroute('get:root') ?></code></p>
        <small class="text-secondary">Generated from <?= $_ENV['APP_NAME'] ?></small>

    <?php 
    $db = new DataBase();
    // $db->update('login', [
    //     'user' => 'somename',
    //     'email' => 'user@email.com'
    // ], 'id = "unknown" && date = "NULL" || die');
    ?>
</div>
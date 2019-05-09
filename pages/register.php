<?php if (loggedIn()) {
    header('Location: '.$config['siteUrl'].'/main');
    die();
} ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Opret bruger</title>
        <link type="text/css" rel="stylesheet" href="<?= $config['siteUrl'] ?>/pages/stylesheets/login.css" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8" />
    </head>
    <body>
        <div class="background-image"></div>
        <div class="wrapper">
            <div class="content">
                <a href="index"><div class="logo"></div></a>
                <div class="login-body">
                    <div class="title">Opret bruger</div>
                    <form class="login-form" name="register" action="" method="POST">
                        <input type="text" name="name" placeholder="Navn" />
                        <input type="email" name="email" placeholder="Email" />
                        <input type="password" name="password" placeholder="Adgangskode" />
                        <input type="submit" name="register" value="Opret bruger" />
                    </form>
                    <div class="error-message">
                        <?php User::register(); ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
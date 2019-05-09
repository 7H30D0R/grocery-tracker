<?php if (loggedIn()) {
    header('Location: '.$config['siteUrl'].'/main');
    die();
} ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Log ind</title>
        <link type="text/css" rel="stylesheet" href="<?= $config['siteUrl'] ?>/pages/stylesheets/login.css" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8" />
    </head>
    <body>
        <div class="background-image"></div>
        <div class="wrapper">
            <div class="content">
                <div class="logo"></div>
                <div class="login-body">
                    <div class="title">Log ind</div>
                    <form class="login-form" name="login" action="" method="POST">
                        <input type="email" name="email" placeholder="Email" />
                        <input type="password" name="password" placeholder="Adgangskode" />
                        <input type="submit" name="login" value="Log ind" />
                    </form>
                    <div class="error-message">
                        <?php User::login(); ?>
                    </div>
                    <div class="login-body-footer">
                        <a class="forgot-password" href="">Glemt adgangskode?</a>
                    </div>
                </div>
                <a class="register-button" href="register">Opret bruger</a>
            </div>
        </div>
    </body>
</html>
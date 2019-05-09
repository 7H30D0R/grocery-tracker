<?php

    if(!defined('LOCAL')) 
    { 
        die('Beklager, men du har ikke adgang til denne fil!'); 
    }

    class Head {
    
        public static function addHead($content = "") {
            global $config;
?>
    <head>
        <link type="text/css" rel="stylesheet" href="<?= $config['siteUrl'] ?>/pages/stylesheets/common.css?<?= time() ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8" />
        <script>const SITE_URL = "<?= $config['siteUrl'] ?>";</script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <?= $content ?>
    </head>
<?php
        }
    }

?>

<?php requiresLogin(); ?>
<!DOCTYPE html>
<html>
<?php 
        include_once 'includes/head.php';
        Head::addHead();
?>
    <body>
        <div class="wrapper">
            <div class="header">
                <div class="header-title">Menu</div>
                <div class="header-lowerbar">Konto</div>
            </div>
            <div class="content no-margin">
               <ul class="stats-menu">
                   <a href="logout"><li>Log ud</li></a>
                </ul>
            </div>
            <?php include_once 'includes/footer.php' ?>
        </div>
    </body>
</html>
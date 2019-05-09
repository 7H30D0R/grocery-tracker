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
                <div class="header-title">Statitistik</div>
                <div class="header-lowerbar">Vælg kategori</div>
            </div>
            <div class="content no-margin">
               <ul class="stats-menu">
                   <a href="stats_date"><li>Varer efter dato</li></a>
                   <a href="stats_type"><li>Varer efter varetype</li></a>
                </ul>
            </div>
            <?php include_once 'includes/footer.php' ?>
        </div>
    </body>
</html>
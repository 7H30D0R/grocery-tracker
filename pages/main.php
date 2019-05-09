<?php requiresLogin(); ?>
<!DOCTYPE html>
<html>
    <?php 
        include_once 'includes/head.php';
        Head::addHead();

        global $dbh;

        $oneMonthAgo = time() - 60 * 60 * 24 * 30;

        $getMonthlyInfo = $dbh->prepare('SELECT SUM(price * amount) as total, SUM(amount) as amount_bought FROM user_products WHERE user_id = :id AND date_added > :month_ago');
        $getMonthlyInfo->bindParam(':id', $_SESSION['id']);
        $getMonthlyInfo->bindParam(':month_ago', $oneMonthAgo);
        $getMonthlyInfo->execute();

        $monthlyInfo = $getMonthlyInfo->fetch();

        $totalMoneySpent = ($monthlyInfo["total"] == null) ? 0 : $monthlyInfo["total"];
        $productsBought = ($monthlyInfo["amount_bought"] == null) ? 0 : $monthlyInfo["amount_bought"];
    ?>
    <body>
        <div class="wrapper">
            <div class="header">
                <div class="header-title">Overblik</div>
                <div class="header-lowerbar">Denne måned</div>
            </div>
            <div class="content center">
                Velkommen tilbage <?= User::userData('name'); ?><br /><br />

                <p>Du har tilføjet <?= $productsBought ?> produkter denne måned.</p>
                <p>I alt har du købt for <?= $totalMoneySpent ?> kr</p>

                <br /><br />

                <?php if ($productsBought != 0) { ?>
                <p><b>Dine 3 mest købte varer er:</b><p><br />
                <?php 
                    $getThreeMostBought = $dbh->prepare('SELECT *,SUM(amount) as amount_bought FROM user_products WHERE user_id = :id AND date_added > :month_ago GROUP BY barcode ORDER BY COUNT(*) DESC LIMIT 3');
                    $getThreeMostBought->bindParam(':id', $_SESSION['id']);
                    $getThreeMostBought->bindParam(':month_ago', $oneMonthAgo);
                    $getThreeMostBought->execute();

                    $i = 0;
                    while ($row = $getThreeMostBought->fetch()) {
                    $i++;
                ?>
                <p><?= $row['name'] ?>: <?=  $row['amount_bought'] ?><p>
                <?php } } ?>
                
            </div>
            <?php include_once 'includes/footer.php' ?>
        </div>
    </body>
</html>
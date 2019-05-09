<?php requiresLogin(); ?>
<!DOCTYPE html>
<html>
<?php 
        $timeSpan = ( empty($_GET['timespan']) ) ? -1 : $_GET['timespan'];
        $minTime = ( $timeSpan == -1 ) ? 0 : time() - $timeSpan;
        
        $headContent = "<script>const TIME_SPAN = " . $timeSpan . ";</script>\n";
        $headContent .= "<script src='" . $config['siteUrl'] . "/pages/javascript/stats_type.js'></script>\n";
        $headContent .= "<script src='" . $config['siteUrl'] . "/pages/javascript/stats.js'></script>\n";

        include_once 'includes/head.php';
        Head::addHead($headContent);

        
?>
    <body>
        <div class="wrapper">
            <div class="header">
                <div class="header-title">Varer efter type</div>
            </div>
            <div class="content no-margin stats-type stats">
                <div class="loading-screen" style="display: none;">
                    <div class="loading-screen-header">Indlæser detaljer</div>
                    <div class="loading-icon"></div>
                </div>
                <div class="product-details" style="display: none;">
                    <div class="product-details-content">
                        <div class="product-details-header">Varedetaljer</div>
                        <div class="detail-group">
                            <label>Stregkode</label>
                            <div class="item-detail item-details-barcode">359193429123</div>
                        </div>
                        <div class="detail-group">
                            <label>Navn</label>
                            <div class="item-detail item-details-name">DENICE MINERALVAND</div>
                        </div>
                        <div class="detail-group">
                            <label>Detaljer</label>
                            <div class="item-detail item-details-details">50 CL</div>
                        </div>
                        <div class="detail-group">
                            <label>Pris</label>
                            <div class="item-detail item-details-price">4.50 kr</div>
                        </div>
                        <div class="detail-group">
                            <label>Mængde</label>
                            <div class="item-detail item-details-amount">1</div>
                        </div>
                        <div class="detail-group">
                            <label>Sidst købt</label>
                            <div class="item-detail item-details-date">01-01-1970</div>
                        </div>
                    </div>
                    <div class="product-details-footer">
                        <div class="product-details-back-button">
                            Tilbage
                        </div>
                        <div class="product-details-delete-button"></div>
                    </div>
                    
                </div>
                <div class="normal-content">
                    <div class="stats-table-wrapper">
                        <table class="stats-table stats-date-table sortable" cellspacing="0">
                            <tr>
                                <th class="name-header">Varenavn</th>
                                <th class="amount-header">Mængde</th>
                                <th class="price-header">Pris</th>
                                <th class="price-total-header">Total</th>
                            </tr>
                            <?php
                                global $dbh;

                                $getUserProducts = $dbh->prepare("SELECT *, SUM(amount) as total_amount, SUM(price * amount) as total_price FROM user_products WHERE user_id = :user_id AND active = '1' AND date_added > :min_time GROUP BY barcode ORDER BY total_amount DESC, total_price DESC LIMIT 100");
                                $getUserProducts->bindParam(':user_id', $_SESSION['id']);
                                $getUserProducts->bindParam(':min_time', $minTime);
                                $getUserProducts->execute();

                                while ($row = $getUserProducts->fetch()) {
                                ?>
                                <tr class="data-row" data-id="<?= $row["id"] ?>" data-barcode="<?= $row['barcode'] ?>">
                                    <td data-raw-value="<?= $row["name"] ?>"><?= $row["name"] ?> <?= $row["details"] ?></td>
                                    <td data-raw-value="<?= $row["total_amount"] ?>"><?= $row["total_amount"] ?></td>
                                    <td data-raw-value="<?= $row["price"] ?>"><?= $row["price"] ?> kr</td>
                                    <td data-raw-value="<?= $row["total_price"] ?>"><?= $row["total_price"] ?> kr</td>
                                </tr>
                                <?php
                                }
                            ?>
                        </table>
                    </div>
                    <div class="stats-footer">
                        <select class="timespan-selector" style="display: none;">
                            <option value="86400">De seneste 24 timer</option>
                            <option value="604800">De seneste 7 dage</option>
                            <option value="2592000">De seneste 30 dage</option>
                            <option value="31536000">De seneste 365 dage</option>
                            <option value="-1">Altid</option>
                        </select>
                    </div>
                </div>
            </div>
            <?php include_once 'includes/footer.php' ?>
        </div>
    </body>
</html>
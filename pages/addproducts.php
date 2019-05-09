<?php requiresLogin(); ?>
<!DOCTYPE html>
<html>
<?php 
        $headContent = "<script src='" . $config['siteUrl'] . "/pages/javascript/quagga/quagga.js'></script>\n";
        $headContent .= "<script src='" . $config['siteUrl'] . "/pages/javascript/addproducts.js'></script>\n";

        include_once 'includes/head.php';
        Head::addHead($headContent);
?>
    <body class="add-products-page">
        <div class="update-product-wrapper" style="display: none;">
            <div class="update-product-background"></div>
            <div class="update-product">
                <div class="title">DENICE MINERALVAND 150 CL</div>
                <div class="form-group">
                    <label>Pris (kr)</label>
                    <div class="form-group-field">
                        <div class="minus-button minus-price-button" data-target=".price-input">-</div>
                        <input type="number" name="price" class="price-input" value="10.95">
                        <div class="plus-button plus-price-button" data-target=".price-input">+</div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Mængde</label>
                    <div class="form-group-field">
                        <div class="minus-button minus-amount-button" data-target=".amount-input">-</div>
                        <input type="number" name="amount" class="amount-input" value="1">
                        <div class="plus-button plus-amount-button" data-target=".amount-input">+</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wrapper">
            <div class="header">
                <div class="header-title" >Tilføj varer</div>
                <div class="header-lowerbar scan-barcode-title" style="display: none;">Scan stregkode</div>
                <div class="header-lowerbar product-not-found-title" style="display: none;">Varen blev ikke fundet</div>
                <div class="header-lowerbar add-new-product-title"  style="display:none;">Tilføj ny vare</div>
            </div>
            <div class="scanner" style="display: none;">
                <div id="webcam" class="webcam"></div>
                <div class="scanner-footer">
                    <div class="cancel-scan-button"></div>
                </div>
            </div>
            <div class="content no-margin product-not-found" style="display: none;">
                <div class="form-group">
                    <label>Varenummer</label>
                    <div class="item-detail item-details-barcode">359193429123</div>
                </div>
                <div class="add-new-product-button-wrapper">
                    <label>Tilføj vare</label>
                    <div class="add-new-product-button"></div>
                </div>
                <div class="product-not-found-footer">
                    <div class="product-not-found-cancel-button"></div>
                </div>
            </div>
            <div class="content no-margin add-new-product" style="display:none;">
                <div class="form-group">
                    <label>Varenummer</label>
                    <div class="item-detail item-details-barcode">359193429123</div>
                </div>
                <div class="form-group">
                    <label>Navn</label>
                    <div class="form-group-field">
                        <input type="text" name="name" class="new-product-name-input" minlength="2" maxlength="60" value="" placeholder="Eksempel: MATILDE KAKAO">
                    </div>
                </div>
                <div class="form-group">
                    <label>Detaljer</label>
                    <div class="form-group-field">
                        <input type="text" name="name" class="new-product-details-input" minlength="2" maxlength="60" value="" placeholder="Eksempel: 500 ML, SKUMMETMÆLK">
                    </div>
                </div>
                <div class="form-group">
                    <label>Pris (kr)</label>
                    <div class="form-group-field">
                        <div class="minus-button new-product-minus-price-button" data-target=".new-product-price-input">-</div>
                        <input type="number" name="price" class="new-product-price-input" value="10">
                        <div class="plus-button new-product-plus-price-button" data-target=".new-product-price-input">+</div>
                    </div>
                </div>
                <div class="add-new-product-error" style="display: none;"></div>
                <div class="add-new-product-footer">
                    <div class="add-new-product-footer-inner">
                        <div class="add-new-product-cancel-button"></div>
                        <div class="add-new-product-accept-button"></div>
                    </div>
                </div>
            </div>
            <div class="content no-margin add-products normal-content">
                <div class="add-products-table-wrapper">
                    <table class="add-products-table" cellspacing="0">
                        <tr>
                            <th>Varenavn</th>
                            <th>Pris</th>
                            <th>Mængde</th>
                        </tr>
                        <?php
                            global $dbh;

                            $getUserProducts = $dbh->prepare("SELECT * FROM user_products WHERE user_id = :user_id AND active = '0'");
                            $getUserProducts->bindParam(':user_id', $_SESSION['id']);
                            $getUserProducts->execute();

                            while ($row = $getUserProducts->fetch()) {
                            ?>
                            <tr class="data-row" data-barcode="<?= $row['barcode'] ?>" data-id="<?= $row['id'] ?>">
                                <td><?= $row["name"] ?> <?= $row["details"] ?></td>
                                <td><?= $row["price"] ?> kr</td>
                                <td><?= $row["amount"] ?></td>
                            </tr>
                            <?php
                            }
                        ?>
                    </table>
                </div>
                <div class="bottom-controls">
                    <div class="left">
                        <div class="cancel-button"></div>
                    </div>
                    <div class="center">
                        <div class="scan-button"></div>
                    </div>
                    <div class="right">
                        <div class="accept-button"></div>
                    </div>
                </div>
            </div>
            <?php include_once 'includes/footer.php' ?>
        </div>
    </body>
</html>
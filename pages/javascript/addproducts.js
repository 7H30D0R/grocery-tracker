var quaggaReady = false;

var mistakes = 0;
var timesScanned = 0;

let lastBarcodeScanned;
let currentBarcode;
let lastBarcodeError;

let totalPrice = 0;

var timeout;

var scanTimeout = 3000;
var lastTime = (new Date()).getTime();
var deltaTime = 0;

var productEditId;

$(document).ready(() => {

    $(".scan-button").click(() => {
        $(".scanner").show();
        $(".scan-barcode-title").show();

        if (quaggaReady == false) initializeQuagga();
        else resumeScanning();
    });

    $(".cancel-scan-button").click(() => {
        pauseScanning();
    });
    

    $(".accept-button").click( () => {
        jQuery.post( SITE_URL + '/request/user/accept_products', {}, ( response ) => {
            location.reload();
        });
    });

    $(".cancel-button").click( () => {
        jQuery.post( SITE_URL + '/request/user/cancel_products', {}, ( response ) => {
            location.reload();
        });
    });

    $(".data-row").click( function() {
        let priceString = $(this).children().eq(1).html();

        productEditId = $(this).attr('data-id');
        console.log(productEditId);

        $(".update-product .title").html( $(this).children().eq(0).html() );
        $(".price-input").val( Number( priceString.substring(0, priceString.length - 3) ) );
        $(".amount-input").val( Number($(this).children().eq(2).html()) );

        $(".update-product-wrapper").show();
    });

    $(".update-product-background").click( () => {

        let newPrice = Number( $(".price-input").val() ).toFixed(2);
        let newAmount = Number( $(".amount-input").val() );
        
        if ( newAmount == 0 ) {

            let data = {
                "id": productEditId
              }
        
            jQuery.post(SITE_URL + '/request/delete/user/product', data, ( response ) => {
                console.log(response);
            });

            $(`tr[data-id="${productEditId}"]`).remove();
            $(".update-product-wrapper").hide();
            return;

        }

        let postData = {
            "id": productEditId,
            "amount": newAmount,
            "price": newPrice
        }

        jQuery.post(SITE_URL + '/request/update/user/product', postData, (postResult) => {
            console.log(postResult);
        });

        $(`tr[data-id="${productEditId}"]`).children().eq(1).html( newPrice + " kr" );
        $(`tr[data-id="${productEditId}"]`).children().eq(2).html( newAmount );

        $(".update-product-wrapper").hide();
    });

    $(".minus-price-button").click( () => {
        $(".price-input").val( roundHalf( Number($(".price-input").val()) ) - 0.5 );
    });

    $(".plus-price-button").click( () => {
        $(".price-input").val( roundHalf( Number($(".price-input").val()) ) + 0.5 );
    });

    $(".minus-amount-button").click( () => {
        $(".amount-input").val( Math.max( 0, Number($(".amount-input").val()) - 1 ) );
    });

    $(".plus-amount-button").click( () => {
        $(".amount-input").val( Number($(".amount-input").val()) + 1 );
    });

});

function roundHalf(number) {
    return Math.floor(number * 2) / 2;
}

function initializeQuagga() {
    quaggaReady = true;
    let height = $('.scanner').height() - 200;

    Quagga.init({
        inputStream : {
            name : "Live",
            type : "LiveStream",
            constraints: {
                width: {min: 640},
                height: {min: 480},
                aspectRatio: {min: 1, max: 100},
                facingMode: "environment"
            },
            target: document.querySelector('#webcam')    // Or '#yourElement' (optional)
        },
        locator: {
            patchSize: "large",
            halfSample: true
        },
        numOfWorkers: 4,
        locate: true,
        config: {
            debug: {
                drawBoundingBox: true,
                showFrequency: true,
                drawScanline: true,
                showPattern: true
            },
        },
        decoder : {
            readers : ["ean_reader", "ean_8_reader"]
        }
        }, function(err) {
            if (err) {
                console.log(err);
                return
            }
            console.log("Initialization finished. Ready to start");
            Quagga.start();

            $("#webcam").filter('video').css({'width': window.innerWidth + "px", 'height': height + "px"});


            Quagga.onProcessed(function(result) {
                var drawingCtx = Quagga.canvas.ctx.overlay,
                    drawingCanvas = Quagga.canvas.dom.overlay;
        
                if (result) {
                    if (result.boxes) {
                        clearTimeout(timeout);
                        setTimeout(function() { drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height"))) }, 500);
                        drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
                        result.boxes.filter(function (box) {
                            return box !== result.box;
                        }).forEach(function (box) {
                            Quagga.ImageDebug.drawPath(box, {x: 0, y: 1}, drawingCtx, {color: "green", lineWidth: 2});
                        });
                    }
        
                    if (result.box) {
                        Quagga.ImageDebug.drawPath(result.box, {x: 0, y: 1}, drawingCtx, {color: "#00F", lineWidth: 2});
                    }
        
                    if (result.codeResult && result.codeResult.code) {
                        Quagga.ImageDebug.drawPath(result.line, {x: 'x', y: 'y'}, drawingCtx, {color: 'red', lineWidth: 3});
                    }
                }
            });

            

            Quagga.onDetected(function(data) {
                let code = "" + data.codeResult.code;
                
                console.log(`Barcode detected! [${data.codeResult.code}]`);
                    
                if (lastBarcodeScanned != code) timesScanned = 0;
                lastBarcodeScanned = code;
                timesScanned++;

                if (timesScanned <= 7) return;
                

                deltaTime = (new Date()).getTime() - lastTime; lastTime = (new Date()).getTime(); scanTimeout -= deltaTime;
                if (scanTimeout > 0) return;

                timeScanned = 0;
                scanTimeout = 3000;

                navigator.vibrate(200);
                Quagga.pause();

                $.getJSON(SITE_URL + '/request/product/' + code, ( data ) => {

                    console.log(data);
                    console.log(data["error"]);

                    if (data["error"] != undefined) productNotFound(code);

                    if (data["error"] == undefined) {
                        pauseScanning();

                        let existingProducts = $(`tr[data-barcode='${data["Ean"]}'`);
                        let existingProductsCount = existingProducts.length;

                        if ( existingProductsCount > 0 ) {

                            let latestExistingProduct = existingProducts.eq(existingProductsCount - 1);
                            let latestExistingProductAmount = latestExistingProduct.find("td").eq(2);

                            let userProductId = Number(latestExistingProduct.attr('data-id'));
                            

                            console.log(userProductId);

                            let oldAmount = Number(latestExistingProductAmount.html());
                            let newAmount = oldAmount + 1;

                            latestExistingProductAmount.html(newAmount);

                            let postData = {
                                "id": userProductId,
                                "amount": newAmount,
                                "date_added": Math.floor((new Date).getTime() / 1000)
                            }

                            jQuery.post(SITE_URL + '/request/update/user/product', postData, (postResult) => {
                                console.log(postResult);
                            });
                            
                        } else {

                            

                            let postData = {
                                "barcode": data["Ean"],
                                "price": data["Pris"],
                                "amount": 1
                            }
    
                            jQuery.post(SITE_URL + '/request/add/user/product', postData, (postResult) => {
                                postResult = JSON.parse(postResult);
                                console.log(postResult);

                                $(".add-products-table tbody").append(`
                                    <tr class="data-row" data-barcode="${data["Ean"]}" data-id="${postResult["id"]}">
                                        <td>${data["Navn"]} ${data["Navn2"]}</td>
                                        <td>${data["Pris"]} kr</td>
                                        <td>1</td>
                                    </tr>
                                `);

                                $(".data-row").click( function() {

                                    let priceString = $(this).children().eq(1).html();
                            
                                    productEditId = $(this).attr('data-id');
                                    console.log(productEditId);
                            
                                    $(".update-product .title").html( $(this).children().eq(0).html() );
                                    $(".price-input").val( Number( priceString.substring(0, priceString.length - 3) ) );
                                    $(".amount-input").val( Number($(this).children().eq(2).html()) );
                            
                                    $(".update-product-wrapper").show();
                                });
                            });
                        }

                    }

                });
            });
    });
}

function productNotFound(code) {
    $(".scanner").hide();
    $(".scan-barcode-title").hide();
    $(".product-not-found-title").show();
    $(".normal-content").hide();

    $(".product-not-found .item-details-barcode").html(code);
    $(".add-new-product-button").attr('data-code', code);
    $(".product-not-found").show();

    $(".add-new-product-button").click(function() {
        displayAddNewProduct(code);
    });

    $(".product-not-found-cancel-button").click(function() {
        $(".product-not-found-title").hide();
        $(".product-not-found").hide();
        $(".normal-content").show();
    });
}

function displayAddNewProduct(code) {
    $(".product-not-found-title").hide();
    $(".product-not-found").hide();

    $(".add-new-product .item-details-barcode").html(code);
    $(".new-product-name-input").val('');
    $(".new-product-details-input").val('');
    $(".new-product-price-input").val(10);

    $(".add-new-product-title").show();
    $(".add-new-product").show();

    $(".add-new-product-cancel-button").click(function() {
        $(".add-new-product-title").hide();
        $(".add-new-product").hide();
        $(".normal-content").show();
    });

    $(".add-new-product-accept-button").click(function() {
        let error;
        console.log('hey');

        if ( $(".new-product-name-input").val().length < 2 && error == undefined ) error = "Navnet skal mindst være to karakterer";
        if ( $(".new-product-details-input").val().length < 2 && error == undefined ) error = "Detaljer skal mindst være to karakterer"; 
        if ( $(".new-product-price-input").val() == '' && error == undefined ) error = "Angiv en pris";
        
        if (error == undefined) {
            $(".add-new-product-error").hide();

            let postData = {
                "barcode": code,
                "name": $(".new-product-name-input").val(),
                "details": $(".new-product-details-input").val(),
                "price": $(".new-product-price-input").val()
            }

            jQuery.post(SITE_URL + '/request/add/product', postData, ( product ) => {
                product = JSON.parse(product);
                console.log( product );

                if ( product["error"] != undefined ) return;

                let postData = {
                    "barcode": code,
                    "price": $(".new-product-price-input").val(),
                    "amount": 1
                }

                jQuery.post(SITE_URL + '/request/add/user/product', postData, (postResult) => {
                    postResult = JSON.parse(postResult);
                    console.log(postResult);

                    $(".add-products-table tbody").append(`
                        <tr class="data-row" data-barcode="${code}" data-id="${postResult["id"]}">
                            <td>${$(".new-product-name-input").val()} ${$(".new-product-details-input").val()}</td>
                            <td>${$(".new-product-price-input").val()} kr</td>
                            <td>1</td>
                        </tr>
                    `);

                    $(".data-row").click( function() {

                        let priceString = $(this).children().eq(1).html();
                
                        productEditId = $(this).attr('data-id');
                        console.log(productEditId);
                
                        $(".update-product .title").html( $(this).children().eq(0).html() );
                        $(".price-input").val( Number( priceString.substring(0, priceString.length - 3) ) );
                        $(".amount-input").val( Number($(this).children().eq(2).html()) );
                
                        $(".update-product-wrapper").show();
                    });
                });
            });

            
            
            $(".add-new-product-title").hide();
            $(".add-new-product").hide();
            $(".normal-content").show();
        } else {
            console.log(error);
            $(".add-new-product-error").html(error);
            $(".add-new-product-error").show();
        }

        /*
        $(".add-new-product-title").hide();
        $(".add-new-product").hide();
        $(".normal-content").show();*/
    });
}

function pauseScanning() {
    Quagga.pause();
    $(".scanner").hide();
    $(".scan-barcode-title").hide();
}

function resumeScanning() {
    Quagga.start();
    $(".scanner").show();
    $(".scan-barcode-title").show();
    scanTimeout = 0;
}
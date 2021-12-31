<html>
    <title>Buy now with NCoin Pay</title>
    <link rel="stylesheet" href="style.css">
    <div class="center">
        <div class="paybox">
            <p>Pay with NCoin</p>
            <img src="" title="Payment QR"/>
        </div>
    </div>
</html>

<?php
    $merchantid = $_GET["merchantid"];
    $productid = $_GET["productid"];
    $providedorderid = $_GET["orderid"];
    $npayid = $merchantid . $productid . $providedorderid;
    $payamount = json_decode(file_get_contents("https://algoindexer.algoexplorerapi.io/v2/accounts/2J7RQGH7BLTE5JYSVPL5T57VIWB67SUOBFEFEJLAIIZNRVNJBF3TAMARAI/transactions"), true);
    echo($payamount["current-round"])
?>
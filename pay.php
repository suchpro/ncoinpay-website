<html>
    <title>Buy now with NCoin Pay</title>
    <link rel="stylesheet" href="style.css">
</html>

<?php
    include('php-algorand-sdk/sdk/algorand.php');

    $merchantid = $_GET["merchantid"];
    $productid = $_GET["productid"];
    $providedorderid = $_GET["orderid"];
    $npayid = $merchantid . $productid . $providedorderid;
    $productpricedict = json_decode(file_get_contents("https://algoindexer.algoexplorerapi.io/v2/transactions/" . $productid), true);

    if $productpricedict["sender"] == $merchantid{
        $productprice = json_decode(base64_decode($productpricedict["note"]))["price-in-ncoin"]
    }

    echo('<div class="center"><div class="paybox">    <p>Pay with NCoin</p>    <img src="' . 'https://chart.googleapis.com/chart?chs=450x450&cht=qr&chl=' . 'algorand%3A%2F%2F' . $merchantid . '%3Famount%3D'. $productprice .'%26asset%3D338543684%26xnote%3Dnpay' . $npayid . '&choe=UTF-8' . '" title="Payment QR"/> </div></div>');
?>
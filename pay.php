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
    $productprice = json_decode(file_get_contents("https://algoindexer.algoexplorerapi.io/v2/accounts/2J7RQGH7BLTE5JYSVPL5T57VIWB67SUOBFEFEJLAIIZNRVNJBF3TAMARAI/transactions"), true)["current-round"]*100000;
    echo('<div class="center"><div class="paybox">    <p>Pay with NCoin</p>    <img src="' . 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . 'algorand%3A%2F%2F4UU5LKME4OJUHHOVIK7S6KDVBGYCOXE5G43367M775OWIEK4SSRRG5VYSY%3Famount%3D'. $productprice .'%26asset%3D338543684%26xnote%3Dnpay' . $npayid . '&choe=UTF-8' . '" title="Payment QR"/> </div></div>');
?>
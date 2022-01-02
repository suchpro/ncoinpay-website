<html>
    <title>Buy now with NCoin Pay</title>

    <style>
        .lds-ring {
            display: inline-block;
            position: relative;
            width: 100px;
            height: 100px;
            padding-top: 1000px;
        }
        .lds-ring div {
            box-sizing: border-box;
            display: block;
            position: absolute;
            width: 100px;
            height: 100px;
            margin: 8px;
            border: 8px solid #fff;
            border-radius: 50%;
            animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
            border-color: #fff transparent transparent transparent;
        }
        .lds-ring div:nth-child(1) {
            animation-delay: -0.45s;
        }
        .lds-ring div:nth-child(2) {
            animation-delay: -0.3s;
        }
        .lds-ring div:nth-child(3) {
            animation-delay: -0.15s;
        }
        @keyframes lds-ring {
            0% {
              transform: rotate(0deg);
            }
            100% {
            transform: rotate(360deg);
            }
        }
    </style>
    <link rel="stylesheet" href="style.css">
</html>

<?php
        
    $merchantid = $_GET["merchantid"];
    $productid = $_GET["productid"];
    $providedorderid = $_GET["orderid"];
    $npayid = $merchantid . $productid . $providedorderid;
    $productpricedict = json_decode(file_get_contents("https://algoindexer.algoexplorerapi.io/v2/transactions/" . $productid), true);
        
    $productprice = null;
    if ($productpricedict["sender"] == $merchantid and $productpricedict["payment-transaction"]["receiver"] == $merchantid)
    {
        $productprice = json_decode(base64_decode($productpricedict["note"]))["price-in-ncoin"];
    }
    echo($productprice);
    echo('<div class="center"><div class="paybox">    <p>Pay with NCoin</p>    <img src="' . 'https://chart.googleapis.com/chart?chs=450x450&cht=qr&chl=' . 'algorand%3A%2F%2F' . $merchantid . '%3Famount%3D'. $productprice .'%26asset%3D338543684%26xnote%3Dnpay' . $npayid . '&choe=UTF-8' . '" title="Payment QR"/>     <div class="lds-ring"><div></div><div></div><div></div><div></div></div>  </div></div>');
?>
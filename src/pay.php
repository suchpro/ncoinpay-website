<?php  
    $merchantid = $_GET["merchantid"];
    $productid = $_GET["productid"];
    $providedorderid = $_GET["orderid"];
    $npayid = $merchantid . $productid . $providedorderid;
    $productpricedict = json_decode(file_get_contents("https://algoindexer.algoexplorerapi.io/v2/transactions/" . $productid), true);
        
    $productprice = null;
    if ($productpricedict["transaction"]["sender"] == $merchantid and $productpricedict["transaction"]["payment-transaction"]["receiver"] == $merchantid)
    {
        $productprice = json_decode(base64_decode($productpricedict["transaction"]["note"]), true)["price-in-ncoin"];
    }
    echo('');
?>

<link rel="stylesheet" href="style.css">
<title>Pay with NCoin</title>

<div class="paybox">
       <img class="ncoinlogo" src="http://content.ncoincrypto.com/ncoinlogo.png" alt="noobs logo">
       <button id="myalgo" class="myalgo">Use MyAlgo</button>
       <img id="qr" src="https://chart.googleapis.com/chart?chs=350x350&cht=qr&chl=' . 'algorand%3A%2F%2F' . $merchantid . '%3Famount%3D' . $productprice*100000 . '%26asset%3D338543684%26xnote%3Dnpay' . $npayid . '&choe=UTF-8" title="Payment QR"/>     <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
</div>
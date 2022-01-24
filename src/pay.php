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
       <p class="mobilesendinginfo" style="text-align:center;color:white;font-family:arial;" id="amountmobile">Amount to send: <?php echo($productprice) ?></p>
       <button id="copy" onclick="copytext()" style="margin:0 auto;display:block;">Copy Address</button>
       <button id="myalgo" class="myalgo">Deposit with MyAlgo Connect</button>
       <div class="divider" style="height: 50px"></div>

       <button id="algowallet" class="algowallet">Deposit with Algorand Mobile Wallet</button>

       <img id="qr" src="https://chart.googleapis.com/chart?chs=350x350&cht=qr&chl=algorand%3A%2F%2F<?php echo($merchantid)?>%3Famount%3D<?php echo($productprice*100000)?>%26asset%3D338543684%26xnote%3Dnpay<?php echo($npayid)?>&choe=UTF-8" title="Payment QR"/>     <div class="lds-ring"><div></div><div></div>
</div>

<script src="https://content.ncoincrypto.com/myalgo.min.js"></script>
<script src="https://unpkg.com/algosdk@1.13.0-beta.2/dist/browser/algosdk.min.js" integrity="sha384-ArIfXzQ4ARpkRJIn6EKgtqbJaPXhEEvNoguSPToHMg2VNl2rNc6QuuOTyDX7Krps" crossorigin="anonymous"></script>

<script>
    async function asyncCall() {
        
        const myAlgoConnect = new MyAlgoConnect();
        const accountsSharedByUser = await myAlgoConnect.connect();

        const algodClient = new algosdk.Algodv2('', 'https://node.algoexplorerapi.io/', '');
        console.log(accountsSharedByUser);

        const params = await algodClient.getTransactionParams().do();

        let receiver = <?php echo("'" . $merchantid . "'") ?>;
        let txnote = "npay<?php echo($npayid) ?>"
        let revocationTarget = undefined;
        let closeRemainderTo = undefined;
        //Amount of the asset to transfer
        let txamount = <?php echo($productprice*100000) ?>;

        // signing and sending "txn" will send "amount" assets from "sender" to "recipient"
        const objtxn = {
            ...params,
            type: 'axfer',
            from: accountsSharedByUser[0]["address"],
            to: receiver,
            assetIndex: 338543684,
            amount: txamount,
            note: txnote
        };  
        const signedTxn = await myAlgoConnect.signTransaction(objtxn);
        const response = await algodClient.sendRawTransaction(signedTxn.blob).do();
    }

    document.getElementById("myalgo").onclick = function()
    {
        asyncCall();
    }

    document.getElementById("algowallet").onclick = function()
    {
        window.open("algorand://<?php echo($merchantid) ?>" + "?amount=<?php echo($productprice * 100000) ?>" + "&asset=338543684&xnote=npay<?php echo($npayid) ?>");
    }

    if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
        // true for mobile device
        var myobj = document.getElementById("qr");
        myobj.remove();

        var myobj2 = document.getElementById("sndto");
        myobj2.remove();

        document.getElementById("myalgo").style.marginTop = "100px";
    }else{
        // false for not mobile device
        var myobj = document.getElementById("algowallet");
        var myobj2 = document.getElementById("copy");
        var myobj3 = document.getElementById("amountmobile");
        myobj.remove();
        myobj2.remove();
        myobj3.remove();
    }

    function copytext() {
        /* Get the text field */
        var copyText = "<?php echo($merchantid) ?>"

        /* Copy the text inside the text field */
        navigator.clipboard.writeText(copyText);
    }
</script>
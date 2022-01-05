<html>
    <title>NCoin Pay</title>
    <style>
        .lds-ring {
            display: inline-block;
            position: relative;
            width: 100px;
            height: 100px;
            padding-left: 50px;
            padding-bottom: 180px;
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
    <link rel="stylesheet" type="text/css" href="style.css">
</html>

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
    echo('<div class="center"><div class="paybox">  <button id="myalgo" class="myalgo">1 Click with MyAlgo</button>  <p>Pay with NCoin</p> <p class="depositaddress">Send ' . $productprice . ' NCoin to ' . $merchantid . '</p>    <img id="qr" src=' . 'https://chart.googleapis.com/chart?chs=450x450&cht=qr&chl=' . 'algorand%3A%2F%2F' . $merchantid . '%3Famount%3D'. $productprice .'%26asset%3D338543684%26xnote%3Dnpay' . $npayid . '&choe=UTF-8' . '" title="Payment QR"/>     <div class="lds-ring"><div></div><div></div><div></div><div></div></div>  </div></div>');
?>

<script src="https://content.ncoincrypto.com/myalgo.min.js"></script>
<script src="https://unpkg.com/algosdk@1.13.0-beta.2/dist/browser/algosdk.min.js" integrity="sha384-ArIfXzQ4ARpkRJIn6EKgtqbJaPXhEEvNoguSPToHMg2VNl2rNc6QuuOTyDX7Krps" crossorigin="anonymous"></script>

<script>
    async function asyncCall() {
        
        const myAlgoConnect = new MyAlgoConnect();
        const accountsSharedByUser = await myAlgoConnect.connect();

        const algodClient = new algosdk.Algodv2('', 'https://node.algoexplorerapi.io/', '');
        const params = await algodClient.getTransactionParams().do();

        let sender = "RABQ4PRRWJDCMHCCCZ24YTLTT44MI4KFZEMRWGFEYESDRBIKD6QFPNNGBQ";
        let receiver = "5YOGUR4XWHBVD2TEMG77XULMSQSK6DTQ4HUEHJA5ILA4FSRSMMKZSRWBK4";
        let note = undefined
        let revocationTarget = undefined;
        let closeRemainderTo = undefined;
        //Amount of the asset to transfer
        amount = <?php echo("$productprice")?>

        // signing and sending "txn" will send "amount" assets from "sender" to "recipient"
        const objtxn = {
            ...params,
            type: 'axfer',
            from: accountsSharedByUser[0],
            to: receiver,
            assetIndex: 338543684,
            amount: amount,
            note: note
        };  
        const signedTxn = await myAlgoConnect.signTransaction(objtxn);
        const response = await algodClient.sendRawTransaction(signedTxn.blob).do();
    }

    document.getElementById("myalgo").onclick = function()
    {
        asyncCall();
    }
</script>
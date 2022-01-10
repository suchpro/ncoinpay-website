<html>
    <title>NCoin Pay</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <form id="emailfrm" style="text-align: center; font-size: 30px; padding-top: 100px;" action="javascript:void(0);">
            <label for="email" style="color: #FFFFFF; font-family: Arial, Helvetica, sans-serif;">Email</label>
            <input style="padding-top: 10px; height: 30px; width: 300px" type="text" id="email" name="email">
    </form>
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

        #myalgo {
            background-image: linear-gradient(to right, #1FA2FF 0%, #12D8FA  51%, #1FA2FF  100%)
        }
        #myalgo {
            margin-top: 30px;
            margin-left: 300px;
            text-align: center;
            transition: 0.5s;
            background-size: 200% auto;
            border-color: none;
            border: none;
            color: white;            
            border-radius: 10px;
            width: 400px;
            height: 50px;
            font-size: 20px;
            display: block;
        }

          #myalgo:hover {
            background-position: right center; /* change the direction of the change here */
            color: #fff;
            text-decoration: none;
          }
         
        }
    </style>
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
    echo('<div class="center"><div class="paybox">  <button id="myalgo" class="myalgo">Use MyAlgo</button>  <p>Pay with NCoin</p> <p style="font-size: 20px; font-family: Arial, Helvetica, sans-serif; padding-bottom: 35px;">Send ' . $productprice . ' NCoin to ' . $merchantid . '</p>    <img id="qr" src="https://chart.googleapis.com/chart?chs=450x450&cht=qr&chl=' . 'algorand%3A%2F%2F' . $merchantid . '%3Famount%3D' . $productprice*100000 . '%26asset%3D338543684%26xnote%3Dnpay' . $npayid . '&choe=UTF-8" title="Payment QR"/>     <div class="lds-ring"><div></div><div></div><div></div><div></div></div>  </div></div>');
?>

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
        let txnote = undefined
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
</script>
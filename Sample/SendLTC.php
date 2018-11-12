<?php
require '../AllPaymentsVirtualCurrencyWrapper.php';

// Alt Payments BC Token for the API
$token = "33b8929cb02d4e02b1368ee297f42a94";

$johnsLTCAddress = "LfiCfPi7zzKKGNNp9iwQE4URAQz2b7kCA5";
$myLTCAddress = "LbNbdSrk1xBeyEBopkAB3LgYAQu3UD2quS";

$gerardsWIFKey = "T83hZTTMZLJBnvNeBRSceifnuGgZHtmFQ7tJqHzFz3Eo4gW479DB";


$altPaymentVCAPI = new AltPaymentsVirtualCurrencyWrapper($myLTCAddress, $token);

$loggingConfig = array('log.LogEnabled' => true,
    'log.FileName' => 'BlockCypherLTC.log',
    'log.LogLevel' => 'DEBUG');

if (!$altPaymentVCAPI->init('ltc', $loggingConfig)) {
    echo "Error: Could not initialize the API";
    exit(255);
}


try {
    $tx = $altPaymentVCAPI->SendVirtualCurrency($johnsLTCAddress, 1000, $gerardsWIFKey);
    if ($tx->tx) {
        echo "Hash ::" . $tx->tx->hash;
    }

} catch (Exception $ex) {
    echo "ERROR: Exception -> " + $ex->getMessage();
}

echo "\nSUCCESS: Transaction submitted";
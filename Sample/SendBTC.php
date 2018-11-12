<?php
require '../AllPaymentsVirtualCurrencyWrapper.php';

// Alt Payments BC Token for the API
$token = "XXXXXX";

$johnsBTCAddress = "YYYYYY";

$myBTCAddress = "AAAAAA";

$gerardsWIFKey = "VVVVVVV";


$altPaymentVCAPI = new AltPaymentsVirtualCurrencyWrapper($myBTCAddress, $token);


$loggingConfig = array('log.LogEnabled' => true,
    'log.FileName' => 'BlockCypherBTC.log',
    'log.LogLevel' => 'DEBUG');


if ( !$altPaymentVCAPI->init('btc', $loggingConfig)) {
    echo "Error: Could not initialize the API";
    exit(255);
}


try {

    $tx = $altPaymentVCAPI->SendVirtualCurrency($johnsBTCAddress, 15000, $gerardsWIFKey);

    if ( $tx->tx ) {
        echo "Hash ::" . $tx->tx->hash;
        echo "\nSUCCESS: Transaction submitted";
    }
    else  {
        echo "Failed\n"; var_dump($tx);
    }
}
catch ( Exception $ex) {
    echo "ERROR:  " . $ex->getMessage() . "\n";
}

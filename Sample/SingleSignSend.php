<?php


require '../AllPaymentsVirtualCurrencyWrapper.php';


// Alt Payments BC Token for the API
$token = 'ccccccccc';

$myLTCAddress = "XXXXXXX URP4";
$johnsLTCAddress = "YYYYYYY";
$gerardsWIFKey = "ZZZZZZZ rNSouGLYZDsAUZq2SasBtg";


$altPaymentVCAPI = new AltPaymentsVirtualCurrencyWrapper(myWalltAddress, $token);

$loggingConfig = array('log.LogEnabled' => true, 'log.FileName' => 'BlockCypherLTC.log', 'log.LogLevel' => 'DEBUG');

if ( !$altPaymentVCAPI->init('ltc', $loggingConfig)) {
    echo "Error: Could not initialize the API";
    exit(255);
}


try {
    $altPaymentVCAPI->SendVirtualCurrency($johnsLTCAddress, 1000, $gerardsWIFKey);
}
catch ( Exception $ex) {
    echo "ERROR: Exception -> " + $ex->getMessage();
}

echo "SUCCESS: Transaction submitted";

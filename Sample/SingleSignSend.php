<?php
/**
 * Created by IntelliJ IDEA.
 * User: gerard
 * Date: 9/3/18
 * Time: 11:19 AM
 */

require '../AllPaymentsVirtualCurrencyWrapper.php';


// Alt Payments BC Token for the API
$token = '33b8929cb02d4e02b1368ee297f42a94';

$myLTCAddress = "LiFPsdKSsyRp7nMKFHBYi9FE9XS14Q URP4";
$johnsLTCAddress = "LfiCfPi7zzKKGNNp9iwQE4URAQz2b7kCA5";
$gerardsWIFKey = "T6McV7tzgNkBVNzYYkgLFfrSDc4RV8 rNSouGLYZDsAUZq2SasBtg";


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

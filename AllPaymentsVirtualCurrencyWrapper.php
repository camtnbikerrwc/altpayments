<?php


require 'vendor/autoload.php';

class AltPaymentsVirtualCurrencyWrapper
{

    private $sourceAddress;
    private $blockCypherToker;
    private $apiContext;
    private $txClient;

    /**
     * AltPaymentsVirtualCurrencyWrapper constructor.
     *
     * Save our Source Wallet Address and our BC Token
     *
     * @param $srcWIFAddress
     * @param $blockCypherToken
     *
     */
    function __construct($srcAddress, $blockCypherToken)
    {
        $this->sourceWifAddress = $srcAddress;
        $this->blockCypherToker = $blockCypherToken;
    }


    /**
     * init our API, set up an API Context and create a Transaction Client
     *
     * @param $virtualCurrencyType Type of VC: 'ltc', 'btc'
     * @param $loggingConfig  Configration for API ( Array of values )
     *                      array('log.LogEnabled' => true, 'log.FileName' => 'BlockCypherLTC.log', 'log.LogLevel' => 'DEBUG')
     *
     *
     *
     * @return true/false
     *
     * @throws BlockCypherConfigurationException
     */

    public function init( $virtualCurrencyType, $loggingConfig) {


        $this->apiContext = ApiContext::create(
            'main', $virtualCurrencyType, 'v1',
            new SimpleTokenCredential($this->blockCypherToken),
            $virtualCurrencyType
        );

        // Create a Client
        $this->txClient = new \BlockCypher\Client\TXClient($this->apiContext);

        if ( is_null($this->txClient)){
            return false;
        }

        return true;
    }

    /**
     * Create A Transaction Skeleton, set up the Inputs and OutPuts and Amount
     *
     * Assume using our Source Wallet
     *
     * @param $txClient
     * @param $to destination Wallet Address
     * @param $value  expressed in Satoshis
     *
     * @return \BlockCypher\Api\TXOutput
     */

    private function createTransactionSleleton($to, $valueInSatoshis)
    {

        /// Tx inputs
        $input = new \BlockCypher\Api\TXInput();
        $input->addAddress($this->sourceAddress);

        /// Tx outputs
        $output = new \BlockCypher\Api\TXOutput();
        $output->addAddress($to);
        $output->setValue($valueInSatoshis); // Satoshis

        /// Tx
        $tx = new \BlockCypher\Api\TX();
        $tx->addInput($input);
        $tx->addOutput($output);

        try {
            $output = $this->txClient->create($tx);
        } catch (Exception $ex) { // XXX DO we want Logging
            throw($ex);
        }

        // This is the TX Skeleton
        return $output;
    }


    /**
     * @param $toAddress Where we are sending the VC
     * @param $valueInSatoshis How much we want to send
     * @param $privateWIFKey The Private WIF Key of the Source Wallet
     *
     * @throws Exception
     */
    public function SendVirtualCurrency($toAddress, $valueInSatoshis, $privateWIFKey)
    {

        $txSkeleton = $this->createTransactionSleleton($toAddress, $valueInSatoshis);

        $privateKey = PrivateKeyFactory::fromWif($privateWIFKey);
        $pk = $privateKey->getHex();
        $privateKeys = array(
            $pk
        );

        // Sign TXSkeleton
        $txSkeleton = $this->txClient->sign($txSkeleton, $privateKeys);

        try {
            /// Send TX to the network
            $txSkeleton = $this->txClient->send($txSkeleton);
        } catch (Exception $ex) {
            throw $ex;
        }


    }


}


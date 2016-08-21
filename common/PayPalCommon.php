<?php
/**
 * Created by PhpStorm.
 * User: adeng
 * Date: 2016/7/4
 * Time: 14:47
 */
// namespace common;

use PayPal\Api\CreditCard;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;

require_once("model/Product.php");
require_once("model/Order.php");

Class PayPalCommon{


    public static function getApiContext(){
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AQAXva7JMeqMOR0yLmplhioqL6YqFf6NXn6as-bpjCRMUnE_qMLSKbKsLIuejpHvQ4MtM6HXEGJibnAH',     // sand box ClientID
                'EOde4SQ-SqWY5F2aFOEWGUEVi0sPIpIwL5P3SrhG0XJHLepNK_96aR2-zLcx2fAZJqCAKcroYIwvD4C-'      // sand box ClientSecret
            )
        );
        return $apiContext;
    }

    public static function getPayer($params){
        if(!is_array($params) || !MyHelper::arrKeyExistInArr($params,array("payMethod"))){
            throw new \Exception(Yii::t("app","Buyinfo Wrong"),400);
        }
        $payer=null;
        switch($params["payMethod"]){
            case "paypal":
                $payer = new Payer();
                $payer->setPaymentMethod($params["payMethod"]);
                break;
            case "credit_card":
                if(!MyHelper::arrKeyExistInArr($params,array('payType',"cardNumber",'expireMonth','expireYear'))){
                    throw new \Exception(Yii::t("app","credit car info Lack"),400);
                }
                $card = new CreditCard();
                $card->setType($params["payType"])
                    ->setNumber($params["cardNumber"])
                    ->setExpireMonth($params["expireMonth"])
                    ->setExpireYear($params["expireYear"]);
//                    ->setCvv2("012")
//                    ->setFirstName("Joe")
//                    ->setLastName("Shopper");
                $fi = new FundingInstrument();
                $fi->setCreditCard($card);
                $payer = new Payer();
                $payer->setPaymentMethod($params["payMethod"])
                    ->setFundingInstruments(array($fi));
                break;
            default:
                throw new \Exception("Paypal payMethod Wrong",400);
                break;
        }
        return $payer;
    }

    public static function getPayRes($params){
        $order=array();
        $cost=0;
        $totalCost=0;
        $tempItemList=array();
        //处理购物车

        //itemList
        foreach ($params["cart"] as $key => $val) {
            //item
            $item = new Item();
            $product=Product::find($val["id"]);

            $item->setName($product["name"])
                ->setCurrency('USD')
                ->setQuantity(intval($val["number"]) )
                ->setPrice(floatval($product["price"]) );

            array_push($tempItemList,$item);
            $cost+=floatval($product["price"])*intval($val["number"]);
        }
        $itemList = new ItemList();
        $itemList->setItems($tempItemList);
        
        //details
        $details = new Details();
        $details->setShipping(floatval($params["shipping"]) )
            ->setSubtotal($cost);


        //amount
        $amount = new Amount();
        $totalCost=floatval($params["shipping"])+$cost;
        $amount->setCurrency("USD")
            ->setTotal($totalCost)
            ->setDetails($details);

        //transaction
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("订单内容")
            ->setInvoiceNumber(uniqid());

        //redirectUrls
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(SITE."?controller=product&action=deal&success=true")
            ->setCancelUrl(SITE."?controller=product&action=deal&success=false");

        //payment
        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer(self::getPayer($params["payinfo"]) )
            ->setTransactions(array($transaction));

        switch ($params["payinfo"]["payMethod"]){
            case "paypal":
                $order["status"]=Order::PAYING;
                $payment->setRedirectUrls($redirectUrls);
                break;
            case "credit_card":
                $order["status"]=Order::SERVING;
                $order["payUrl"]="credit_card";
                break;
        }

        $request = clone $payment;
        $apiContext = self::getApiContext();

        try {
            $payment->create($apiContext);
        // }catch (\PayPal\Exception\PayPalConnectionException $ex) {
            // This will print the detailed information on the exception. 
            //REALLY HELPFUL FOR DEBUGGING
            // echo $ex->getData();
        } catch (\Exception $ex) {
            throw new Exception($ex->getMessage(), $ex->getCode());
        }
        $approvalUrl = $payment->getApprovalLink();
        $order["orderNum"]=$payment->id;
        $order["cart"]=json_encode($params["cart"] );
        $order["payUrl"]=$approvalUrl;
        $order["shipping"]=floatval($params["shipping"]);
        $order["cost"]=$cost;
        $order["totalCost"]=$totalCost;
        $order["userId"]=$_SESSION["user.id"];
        return $order;
    }


    public static function chkDeal(){
        if (isset($_GET['success']) && $_GET['success'] == 'true') {
            $paymentId = $_GET['paymentId'];
            $apiContext=self::getApiContext();
            $payment = Payment::get($paymentId, $apiContext);

            $execution = new PaymentExecution();
            $execution->setPayerId($_GET['PayerID']);

            $result = $payment->execute($execution, $apiContext);

            $payment = Payment::get($paymentId, $apiContext);
            return $payment;
        } else if($_GET['success']=="false"){
            throw new \Exception("付款失败！",400);
        } else {
            throw new \Exception("非正常渠道访问该地址！",400);
        }
    }
}
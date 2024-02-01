<?php

require_once('vendor/autoload.php');
$env = require_once('env.php');

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if
   ($username !== 'odeme_user' || $password !== 'odeme_user_password') 
   {echo "Hata: Geçersiz kimlik bilgileri"; exit;}

$options = new \Iyzipay\Options();
$options->setApiKey($env['apiKey']);
$options->setSecretKey($env['secretKey']);
$options->setBaseUrl($env['sandbox'] ? 'https://sandbox-api.iyzipay.com' : 'https://api.iyzipay.com');

$request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
$request->setLocale(\Iyzipay\Model\Locale::TR);
$request->setConversationId("2");
$request->setPrice("50"); 
$request->setPaidPrice("50");
$request->setCurrency(\Iyzipay\Model\Currency::TL);
$request->setBasketId("1");
$request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
$request->setCallbackUrl("http://google.com"); 
$request->setEnabledInstallments([2, 3, 6, 9]);
$request->setForceThreeDS(1);

$buyer = new \Iyzipay\Model\Buyer();
$buyer->setId("BY123"); 
$buyer->setName("Ahmet"); 
$buyer->setSurname("YILMAZ"); 
$buyer->setGsmNumber("+905055554444"); 
$buyer->setEmail("ahmet.yilmaz@example.com"); 
$buyer->setIdentityNumber("98765432101");
$buyer->setLastLoginDate("2023-01-20 14:30:00");
$buyer->setRegistrationDate("2023-01-10 10:15:00");
$buyer->setRegistrationAddress("Atatürk Mh. Caddesi No: 123 Ankara / Çankaya"); 
$buyer->setIp("192.168.1.1"); 
$buyer->setCity("Ankara"); 
$buyer->setCountry("Türkiye");
$buyer->setZipCode("06060");
$request->setBuyer($buyer);

$shippingAddress = new \Iyzipay\Model\Address();
$shippingAddress->setContactName("Ahmet YILMAZ"); 
$shippingAddress->setCity("Ankara"); 
$shippingAddress->setCountry("Türkiye");
$shippingAddress->setAddress("Atatürk Mh. Caddesi No: 123 Ankara / Çankaya");
$shippingAddress->setZipCode("06060");
$request->setShippingAddress($shippingAddress);

$billingAddress = new \Iyzipay\Model\Address();
$billingAddress->setContactName("Ahmet YILMAZ");
$billingAddress->setCity("Ankara");
$billingAddress->setCountry("Türkiye");
$billingAddress->setAddress("Atatürk Mh. Caddesi No: 123 Ankara / Çankaya");
$billingAddress->setZipCode("06060");
$request->setBillingAddress($billingAddress);

$basketItems = array();

$firstBasketItem = new \Iyzipay\Model\BasketItem();
$firstBasketItem->setId("P1"); 
$firstBasketItem->setName("Yeni Nesil Programlama Dilleri Eğitimi"); 
$firstBasketItem->setCategory1("Eğitimler");
$firstBasketItem->setCategory2("Bilgisayar Programlama");
$firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
$firstBasketItem->setPrice("30"); 
$basketItems[0] = $firstBasketItem;

$secondBasketItem = new \Iyzipay\Model\BasketItem();
$secondBasketItem->setId("V2"); 
$secondBasketItem->setName("Veri Bilimi ve Yapay Zeka Eğitimi");
$secondBasketItem->setCategory1("Eğitimler");
$secondBasketItem->setCategory2("Yapay Zeka");
$secondBasketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
$secondBasketItem->setPrice("20"); 
$basketItems[1] = $secondBasketItem;

$request->setBasketItems($basketItems);

$payment = \Iyzipay\Model\CheckoutFormInitialize::create($request, $options);

echo "<pre>";
print_r($payment->getStatus());
print_r($payment->getErrorMessage());
print_r($payment->getErrorCode());
print_r($payment->getErrorGroup());
print_r($payment->getCheckoutFormContent());
print_r($payment);
echo "</pre>";
?>

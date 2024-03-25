<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<?php
if ( $_SERVER['REQUEST_METHOD'] == 'POST' ){
   echo json_encode('123');
   exit;
}
$id = 1;
// App Key local
$key = "8d87e6f082de482bddd85de1f5bbd98c5e266d464a52c0404ae5665d097b086e";
$cipher = "aes-128-gcm";
// App iv local
$iv = hex2bin("e436e4cb6f3283aebfe46c12");

$data = array(
    "txn_amount" => "0",
    // "installments" => "5",
    // "txn_currency" => "CAD",
    // "txn_gateway_options" => ["jazzcash", "stripe", "paypal"],
    "txn_customer_id" => "0001", //customer id
    "txn_customer_name" => "Muhammad Shoaib Iqbal", //customer name
    "txn_customer_email" => "shoaib@ec.com.pk", //customer email
    "txn_customer_mobile" => "03453314547", // customer mobile
    "txn_payment_type" => "VBC subscription", // payment type
    "txn_customer_bill_order_id" => "order-234234-1123",
    "txn_description" => "VBC payment received for shoaib",
    "customer_ip" => "192.100.2.15",
    "txn_platform_return_url" => "http://localhost:9000/ec_platform_template.php",
);
$plaintext = http_build_query($data);

if (in_array($cipher, openssl_get_cipher_methods())) {
    $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options = 0, $iv, $tag);
    $ciphertext = $ciphertext . bin2hex($tag); // tag variable generated from encrypt
}
?>

<!-- <form method="post" action="https://pay.ec.com.pk/"> -->
    <!-- <form method="post" action="https://sbpay.ec.com.pk/"> -->
    <form method="post" action="http://localhost:9000">
    id: <input type="text" name="id" value="<?php echo $id ?>" /> <br />
    data: <input type="text" name="data" value="<?php echo $ciphertext; ?>" /> <br />
    <button type="submit">Submit encrypted</button>
</form>
<?php
// echo '<pre>';
// var_dump($_REQUEST);

//Stripe
// $result = json_decode('{"id":"evt_1JppIyHLHim8CAEbzX5YOEAq","object":"event","api_version":"2020-08-27","created":1635491711,"data":{"object":{"id":"cs_test_a11t2LC2XrqZtXcfQ8idTQ6ogPqO2cTQV5QmYTbspgJhkN5DarSDhVuXrg","object":"checkout.session","after_expiration":null,"allow_promotion_codes":null,"amount_subtotal":6000,"amount_total":6000,"automatic_tax":{"enabled":false,"status":null},"billing_address_collection":null,"cancel_url":"https:\/\/sbpay.ec.com.pk\/stripeCallback\/111","client_reference_id":"2ref1635491691","consent":null,"consent_collection":null,"currency":"usd","customer":"cus_KUowhatWyZEYpJ","customer_details":{"email":"shoaib@ec.com.pk","phone":null,"tax_exempt":"none","tax_ids":[]},"customer_email":"shoaib@ec.com.pk","expires_at":1635578093,"livemode":false,"locale":null,"metadata":[],"mode":"payment","payment_intent":"pi_3JppIfHLHim8CAEb1nwrxM2G","payment_method_options":[],"payment_method_types":["card"],"payment_status":"paid","phone_number_collection":{"enabled":false},"recovered_from":null,"setup_intent":null,"shipping":null,"shipping_address_collection":null,"submit_type":null,"subscription":null,"success_url":"https:\/\/sbpay.ec.com.pk\/stripeCallback\/111","total_details":{"amount_discount":0,"amount_shipping":0,"amount_tax":0},"url":null}},"livemode":false,"pending_webhooks":3,"request":{"id":null,"idempotency_key":null},"type":"checkout.session.completed"}');
// var_dump($result->type);
// var_dump($result->data->object->id);
// var_dump($result->data->object->client_reference_id);
// var_dump($result->data);
// var_dump($result);


//Paypal
// $result = json_decode('{"id":"WH-25820625EA046032E-3PR5807241739270U","event_version":"1.0","create_time":"2021-10-27T11:44:02.580Z","resource_type":"payment","event_type":"PAYMENTS.PAYMENT.CREATED","summary":"Checkout payment is created and approved by buyer","resource":{"update_time":"2021-10-27T11:44:02Z","create_time":"2021-10-27T11:43:45Z","redirect_urls":{"return_url":"https:\/\/sbpay.ec.com.pk\/?paymentId=PAYID-MF4TW4I60426821TU001793M","cancel_url":"https:\/\/sbpay.ec.com.pk"},"links":[{"href":"https:\/\/api.sandbox.paypal.com\/v1\/payments\/payment\/PAYID-MF4TW4I60426821TU001793M","rel":"self","method":"GET"},{"href":"https:\/\/api.sandbox.paypal.com\/v1\/payments\/payment\/PAYID-MF4TW4I60426821TU001793M\/execute","rel":"execute","method":"POST"},{"href":"https:\/\/www.sandbox.paypal.com\/cgi-bin\/webscr?cmd=_express-checkout&token=EC-9HD99177TR959725J","rel":"approval_url","method":"REDIRECT"}],"id":"PAYID-MF4TW4I60426821TU001793M","state":"created","transactions":[{"amount":{"total":"100.00","currency":"USD"},"payee":{"merchant_id":"4LTM6EZFP5KNE","email":"sb-ux06b8290786@business.example.com"},"description":"Enter Your transaction description","invoice_number":"2ref1635335019","item_list":{"items":[{"name":"Product 1","price":"100.00","currency":"USD","quantity":1}],"shipping_address":{"recipient_name":"John Doe","line1":"1 Main St","city":"San Jose","state":"CA","postal_code":"95131","country_code":"US","default_address":false,"preferred_address":false,"primary_address":false,"disable_for_transaction":false}},"related_resources":[]}],"intent":"sale","payer":{"payment_method":"paypal","status":"VERIFIED","payer_info":{"email":"sb-aoj6a8286752@personal.example.com","first_name":"John","last_name":"Doe","payer_id":"V4J6YS4QB6BVL","shipping_address":{"recipient_name":"John Doe","line1":"1 Main St","city":"San Jose","state":"CA","postal_code":"95131","country_code":"US","default_address":false,"preferred_address":false,"primary_address":false,"disable_for_transaction":false},"country_code":"US"}},"cart":"9HD99177TR959725J"},"links":[{"href":"https:\/\/api.sandbox.paypal.com\/v1\/notifications\/webhooks-events\/WH-25820625EA046032E-3PR5807241739270U","rel":"self","method":"GET"},{"href":"https:\/\/api.sandbox.paypal.com\/v1\/notifications\/webhooks-events\/WH-25820625EA046032E-3PR5807241739270U\/resend","rel":"resend","method":"POST"}]}');
// var_dump($result->resource->id);
// var_dump($result);
// var_dump($result->resource->payer->status);
// var_dump($result->resource->transactions[0]->invoice_number);
?>

========================== Simple payment Local ====================================
<?php
// App id local
$id = 1;
// App Key local
$key = "8d87e6f082de482bddd85de1f5bbd98c5e266d464a52c0404ae5665d097b086e";
$cipher = "aes-128-gcm";
// App iv local
$iv = hex2bin("e436e4cb6f3283aebfe46c12");

$data = array(
    "txn_amount" => "175",
    // "installments" => "5",
    // "txn_currency" => "USD",
    "txn_gateway_options" => ["stripep", "kuickpay2"],
    // "txn_gateway_options" => ["jazzcash", "stripe", "paypal"],
    "txn_customer_id" => "0001", //customer id
    "txn_customer_name" => "Muhammad Shoaib Iqbal", //customer name
    "txn_customer_email" => "shoaib@ec.com.pk", //customer email
    "txn_customer_mobile" => "03453314547", // customer mobile
    "txn_expiry_datetime" => Date('Y-m-d', strtotime('+30 days')), // 
    "txn_payment_type" => "Deposit slip test", // payment type
    "txn_customer_bill_order_id" => "deposit-346534".time(),
    "txn_description" => "deposit slip payment received for shoaib",
    "customer_ip" => "192.100.2.15",
    "txn_platform_return_url" => "http://localhost:9000/ec_platform_template.php",
);
$plaintext = http_build_query($data);

if (in_array($cipher, openssl_get_cipher_methods())) {
    $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options = 0, $iv, $tag);
    $ciphertext = $ciphertext . bin2hex($tag); // tag variable generated from encrypt
}
?>

<form id="simple_payment_local" method="post" action="http://localhost:9000">
    id: <input type="text" name="id" value="<?php echo $id ?>" /> <br />
    data: <input type="text" name="data" value="<?php echo $ciphertext; ?>" /> <br />
    <button type="submit">Submit encrypted</button>
</form>

========================== Simple payment Sandbox ====================================
<?php
$id = 2; // App id sbpay
$key = "360b9e5c338129b3abd4c9595b1094ff28675f4835dd74d7f8b30766e384847f"; // App Key sandbox
$cipher = "aes-128-gcm";
$iv = hex2bin("0f70886f89b103439545d0f3"); // App iv sandbox

$data = array(
    "txn_amount" => "80",
    // "installments" => "5",
    "txn_currency" => "USD",
    // "txn_gateway_options" => ["jazzcash", "stripe", "paypal"],
    "txn_customer_id" => "410", //customer id
    "txn_customer_name" => "Muhammad Shoaib Iqbal", //customer name
    "txn_customer_email" => "shoaib.iqbal@ec.com.pk", //customer email
    "txn_customer_mobile" => "03453314547", // customer mobile
    "txn_payment_type" => "Test Saudi seminar", // payment type
    "txn_customer_bill_order_id" => "order-".time(),
    "txn_allow_multiple" => true,
    "txn_expiry_datetime" => date('Y-m-d', strtotime("+3 days")),
    "txn_description" => "VBC payment received for shoaib",
    "customer_ip" => "192.100.2.15",
    "txn_platform_return_url" => "https://extreme.institute/ecprogram/payment/thankyou",
);
$plaintext = http_build_query($data);

if (in_array($cipher, openssl_get_cipher_methods())) {
    $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options = 0, $iv, $tag);
    $ciphertext = $ciphertext . bin2hex($tag); // tag variable generated from encrypt
}
?>

<form id="simple_payment_sandbox" method="post" action="https://pay.extreme.institute/">
    id: <input type="text" name="id" value="<?php echo $id ?>" /> <br />
    data: <input type="text" name="data" value="<?php echo $ciphertext; ?>" /> <br />
    <button type="submit">Submit encrypted</button>
</form>

========================== Simple payment Live ====================================
<?php
$id = 3; // App id live
$key = "51a3e62f586685a35c8f7e31d03d629a17974f38a243869e2330b6da299d80e5"; // App Key live
$cipher = "aes-128-gcm";
$iv = hex2bin("aca8e820c5ed2188031a8155"); // App iv live

$data = array(
    "txn_amount" => "8000",
    // "installments" => "5",
    "txn_currency" => "USD",
    // "txn_currency_rate" => "Dollar_Rates_1",
    // "txn_gateway_options" => [0 => "stripe", 1=> "stripep", 2=>"kuickpay"],
    "txn_customer_id" => "0001", //customer id
    "txn_customer_name" => "Muhammad Shoaib Iqbal", //customer name
    "txn_customer_email" => "shoaib@ec.com.pk", //customer email
    "txn_customer_mobile" => "03453314547", // customer mobile
    "txn_payment_type" => "Test saudi seminar", // payment type
    "txn_customer_bill_order_id" => "test-order-1111".time(),
    // "txn_allow_multiple" => true,
    // "txn_expiry_datetime" => date('Y-m-d', strtotime("+3 days")),
    "txn_description" => "Test expiry withou sending for 150pkr",
    "customer_ip" => "192.100.2.15",
    "txn_platform_return_url" => "http://localhost:9000/ec_platform_template.php",
);
$plaintext = http_build_query($data);

if (in_array($cipher, openssl_get_cipher_methods())) {
    $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options = 0, $iv, $tag);
    $ciphertext = $ciphertext . bin2hex($tag); // tag variable generated from encrypt
}
?>

<form id="simple_payment_sandbox" method="post" action="https://pay.ec.com.pk/">
    id: <input type="text" name="id" value="<?php echo $id ?>" /> <br />
    data: <input type="text" name="data" value="<?php echo $ciphertext; ?>" /> <br />
    <button type="submit">Submit encrypted</button>
</form>

========================== Simple payment Local with limited gateways ====================================
<?php
// App id local
$id = 1;
// App Key local
$key = "8d87e6f082de482bddd85de1f5bbd98c5e266d464a52c0404ae5665d097b086e";
$cipher = "aes-128-gcm";
// App iv local
$iv = hex2bin("e436e4cb6f3283aebfe46c12");

$data = array(
    "txn_amount" => "175",
    // "installments" => "5",
    "txn_currency" => "USD",
    "txn_gateway_options" => ["stripe"], //, "paypal"
    "txn_customer_id" => "0001", //customer id
    "txn_customer_name" => "Muhammad Shoaib Iqbal", //customer name
    "txn_customer_email" => "shoaib@ec.com.pk", //customer email
    "txn_customer_mobile" => "03453314547", // customer mobile
    "txn_payment_type" => "VBC subscription", // payment type
    "txn_customer_bill_order_id" => "order-3453243265-3",
    "txn_description" => "VBC payment received for shoaib",
    "customer_ip" => "192.100.2.15",
    "txn_platform_return_url" => "http://localhost:9000/ec_platform_template.php",
);
$plaintext = http_build_query($data);

if (in_array($cipher, openssl_get_cipher_methods())) {
    $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options = 0, $iv, $tag);
    $ciphertext = $ciphertext . bin2hex($tag); // tag variable generated from encrypt
}
?>

<form id="simple_payment_local" method="post" action="http://localhost:9000">
    id: <input type="text" name="id" value="<?php echo $id ?>" /> <br />
    data: <input type="text" name="data" value="<?php echo $ciphertext; ?>" /> <br />
    <button type="submit">Submit encrypted</button>
</form>

========================== Free Registration Sandbox ====================================
<?php
$id = 2; // App id sbpay
$key = "360b9e5c338129b3abd4c9595b1094ff28675f4835dd74d7f8b30766e384847f"; // App Key sandbox
$cipher = "aes-128-gcm";
$iv = hex2bin("0f70886f89b103439545d0f3"); // App iv sandbox

$data = array(
    "txn_amount" => "0",
    // "installments" => "5",
    // "txn_currency" => "CAD",
    // "txn_gateway_options" => ["jazzcash", "stripe", "paypal"],
    "txn_customer_id" => "0001", //customer id
    "txn_customer_name" => "Muhammad Shoaib Iqbal", //customer name
    "txn_customer_email" => "shoaib@ec.com.pk", //customer email
    "txn_customer_mobile" => "03453314547", // customer mobile
    "txn_payment_type" => "VBC subscription", // payment type
    "txn_customer_bill_order_id" => "order-3456786783265-1",
    "txn_description" => "VBC payment received for shoaib",
    "customer_ip" => "192.100.2.15",
    "txn_platform_return_url" => "http://localhost:9000/ec_platform_template.php",
);
$plaintext = http_build_query($data);

if (in_array($cipher, openssl_get_cipher_methods())) {
    $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options = 0, $iv, $tag);
    $ciphertext = $ciphertext . bin2hex($tag); // tag variable generated from encrypt
}
?>

<form id="free_reg_sandbox" method="post" action="https://sbpay.ec.com.pk/">
    id: <input type="text" name="id" value="<?php echo $id ?>" /> <br />
    data: <input type="text" name="data" value="<?php echo $ciphertext; ?>" /> <br />
    <button type="submit">Submit encrypted</button>
</form>

========================== Free Registration Live ====================================
<?php
$id = 3; // App id pay
$key = "51a3e62f586685a35c8f7e31d03d629a17974f38a243869e2330b6da299d80e5"; // App Key live dashboard
$cipher = "aes-128-gcm";
$iv = hex2bin("aca8e820c5ed2188031a8155"); // App iv live dashboard

$data = array(
    "txn_amount" => "0",
    // "installments" => "5",
    // "txn_currency" => "CAD",
    // "txn_gateway_options" => ["jazzcash", "stripe", "paypal"],
    "txn_customer_id" => "0001", //customer id
    "txn_customer_name" => "Muhammad Shoaib Iqbal", //customer name
    "txn_customer_email" => "shoaib@ec.com.pk", //customer email
    "txn_customer_mobile" => "03453314547", // customer mobile
    "txn_payment_type" => "VBC subscription", // payment type
    "txn_customer_bill_order_id" => "order-3456786783265-1",
    "txn_description" => "VBC payment received for shoaib",
    "customer_ip" => "192.100.2.15",
    "txn_platform_return_url" => "http://localhost:9000/ec_platform_template.php",
);
$plaintext = http_build_query($data);

if (in_array($cipher, openssl_get_cipher_methods())) {
    $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options = 0, $iv, $tag);
    $ciphertext = $ciphertext . bin2hex($tag); // tag variable generated from encrypt
}
?>

<form id="free_reg_sandbox" method="post" action="https://pay.ec.com.pk/">
    id: <input type="text" name="id" value="<?php echo $id ?>" /> <br />
    data: <input type="text" name="data" value="<?php echo $ciphertext; ?>" /> <br />
    <button type="submit">Submit encrypted</button>
</form>

========================== Multiple Vouchers Local ====================================
<?php
// App id local
$id = 1;
// App Key local
$key = "fad2dae47157a6d6e4e1fd53d3a4866145e6df96ed870e403228b4ee6456d49f";
$cipher = "aes-128-gcm";
// App iv local
$iv = hex2bin("157451c0d5956790d4336212");

$data = array(
    "txn_amount" => "175",
    "installments" => "1",
    // "txn_currency" => "CAD",
    // "txn_gateway_options" => ["jazzcash", "stripe", "paypal"],
    "txn_customer_id" => "0001", //customer id
    "txn_customer_name" => "Muhammad Shoaib Iqbal", //customer name
    "txn_customer_email" => "shoaib@ec.com.pk", //customer email
    "txn_customer_mobile" => "03453314547", // customer mobile
    "txn_payment_type" => "VBC subscription", // payment type
    "txn_customer_bill_order_id" => "order-3453243265-1",
    "txn_description" => "VBC payment received for shoaib",
    "customer_ip" => "192.100.2.15",
    "txn_platform_return_url" => "http://localhost:9000/ec_platform_template.php",
);
$plaintext = http_build_query($data);

if (in_array($cipher, openssl_get_cipher_methods())) {
    $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options = 0, $iv, $tag);
    $ciphertext = $ciphertext . bin2hex($tag); // tag variable generated from encrypt
}
?>

<form id="multi_voucher_local" method="post" action="http://localhost:9000/api/vouchers/create">
    id: <input type="text" name="id" value="<?php echo $id ?>" /> <br />
    data: <input type="text" name="data" value="<?php echo $ciphertext; ?>" /> <br />
    <button type="submit">Submit encrypted</button>
</form>
<script>
    $("#multi_voucher_local").submit(function(e) {
        e.preventDefault();
        $.ajax({
            "url": "http://127.0.0.1:8000/api/vouchers/create",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Authorization": "Bearer fad2dae47157a6d6e4e1fd53d3a4866145e6df96ed870e403228b4ee6456d49f",
                "Content-Type": "application/x-www-form-urlencoded",
            },
            "data": {
                "id": "<?php echo $id ?>",
                "data": "<?php echo $ciphertext; ?>"
            },
            // dataType: 'json',
            // contentType : 'application/x-www-form-urlencoded',
            // async: false
        })
    });
</script>

========================== Multiple Vouchers Sandbox ====================================
<?php
$id = 2; // App id sbpay
$key = "360b9e5c338129b3abd4c9595b1094ff28675f4835dd74d7f8b30766e384847f"; // App Key sandbox
$cipher = "aes-128-gcm";
$iv = hex2bin("0f70886f89b103439545d0f3"); // App iv sandbox

$data = array(
    "txn_amount" => "175",
    "installments" => "5",
    // "txn_currency" => "CAD",
    // "txn_gateway_options" => ["jazzcash", "stripe", "paypal"],
    "txn_customer_id" => "0001", //customer id
    "txn_customer_name" => "Muhammad Shoaib Iqbal", //customer name
    "txn_customer_email" => "shoaib@ec.com.pk", //customer email
    "txn_customer_mobile" => "03453314547", // customer mobile
    "txn_payment_type" => "VBC subscription", // payment type
    "txn_customer_bill_order_id" => "order-7685747658-1",
    "txn_description" => "VBC payment received for shoaib",
    "customer_ip" => "192.100.2.15",
    "txn_platform_return_url" => "http://localhost:9000/ec_platform_template.php",
);
$plaintext = http_build_query($data);

if (in_array($cipher, openssl_get_cipher_methods())) {
    $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options = 0, $iv, $tag);
    $ciphertext = $ciphertext . bin2hex($tag); // tag variable generated from encrypt
}
?>

<form id="multi_voucher_sbpay" method="post" action="https://sbpay.ec.com.pk/">
    id: <input type="text" name="id" value="<?php echo $id ?>" /> <br />
    data: <input type="text" name="data" value="<?php echo $ciphertext; ?>" /> <br />
    <button type="submit">Submit encrypted</button>
</form>

<script>
    $("#multi_voucher_sbpay").submit(function(e) {
        e.preventDefault();
        $.ajax({
            "url": "https://sbpay.ec.com.pk/api/vouchers/create",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Authorization": "Bearer 9a024a536900cbe69b564c29853517ab03968e7504836f219a7a8316b631cd2d",
                "Content-Type": "application/x-www-form-urlencoded",
            },
            "data": {
                "id": "<?php echo $id ?>",
                "data": "<?php echo $ciphertext; ?>"
            },
            // dataType: 'json',
            // contentType : 'application/x-www-form-urlencoded',
            // async: false
        })
    });
</script>

========================== Hashing test ====================================
<?php

// EC_INTRA_COMM_KEY="360b9e5c338129b3abd4c9595b1094ff28675f4835dd74d7f8b30766e384847f"
// EC_INTRA_COMM_CIPHER="aes-128-gcm"
// EC_INTRA_COMM_IV="0f70886f89b103439545d0f3"

$key = "360b9e5c338129b3abd4c9595b1094ff28675f4835dd74d7f8b30766e384847f"; // App Key sandbox
$cipher = "aes-128-gcm";
$iv = hex2bin("0f70886f89b103439545d0f3"); // App iv sandbox

$hash = "WTWE5qcMz%20oK8gS2l3aQRS32K9ryqhiUjTaFU6xi6eQ%2FFqOuHlYsofIowC%20c%2Fuq%20WDPk15NY6bvpwPzcDf8XMs3cnM4yR%20H2Eg%3D%3D5bdaf450824771a3a61ccc84eeb26041";
var_dump($hash);
$ciphertext = substr($hash, 0, -32); 
$tag = substr($hash, -32);

$original_plaintext = openssl_decrypt($ciphertext, $cipher, $key, $options = 0, hex2bin($iv), hex2bin($tag));

parse_str($original_plaintext, $decoded_array);

var_dump($decoded_array);
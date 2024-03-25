<form name="easypayform" action=" https://easypaystg.easypaisa.com.pk/easypay/Index.jsf " method="POST">
<! -- Store Id Provided by Easypay-->
<input name="storeId" value="{{$easypay_storeId}}" hidden = "true"/>
<! -- Amount of Transaction from merchant’s website -->
<input name="amount" value="{{$transaction->txn_amount}}" hidden = "true"/>
<! – Post back URL from merchant’s website -- >
<input name="postBackURL" value="{{config('app.url')}}/easypayCallback" hidden = "true"/>
<! – Order Reference Number from merchant’s website -- >
<input name="orderRefNum" value="{{$transaction->txn_reference}}" hidden = "true"/>
<! – Expiry Date from merchant’s website (Optional) -- >
<!-- <input type ="hidden" name="expiryDate" value="20140606 201521"> -->
<! – Merchant Hash Value (Optional) -- >
<input type ="hidden" name="merchantHashedReq" value="{{$merchantHashedReq}}">
<! – If Merchant wants to redirect to Merchant website after payment completion (Optional) -- >
<input type ="hidden" name="autoRedirect" value="1">
<! – If merchant wants to post specific Payment Method (Optional) -- >
<!-- <input type ="hidden" name="paymentMethod" value="MA_PAYMENT_METHOD"> -->
<! – If merchant wants to post specific Payment Method (Optional) -- >
<input type ="hidden" name="emailAddr" value="{{$transaction->txn_customer_email}}">
<! – If merchant wants to post specific Payment Method (Optional) -- >
<input type ="hidden" name="mobileNum" value="{{$transaction->txn_customer_mobile}}">
<! – If merchant wants to post specific Bank Identifier (Optional) -- >
<!-- <input type ="hidden" name="bankIdentifier" value="UBL456"> -->
<! – This is the button of the form which submits the form -- >
<!-- <input type = "image" src="/assets/img/easy_paisa.png" border="0" name= "pay"> -->
</form>
Redirecting to Easy Paisa checkout. Please wait...
<script data-cfasync="false" type="text/javascript">
    document.easypayform.submit();
</script>
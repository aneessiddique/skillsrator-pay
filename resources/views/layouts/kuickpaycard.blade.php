<form name="kuickpayform" method="POST" action="https://app2.kuickpay.com:5728/api/Redirection">
	<input type="text" name="OrderID" value="{{$transaction->txn_customer_bill_order_id}}"><br>
	<input type="text" name="InstitutionID" value="01234"><br>
	<input type="text" name="MerchantName" value="{{env('APP_NAME')}}"><br>
	<input type="text" name="Amount" value="{{$pkr_amount}}"><br>
	<input type="text" name="TransactionDescription" value="{{$transaction->txn_description}}"><br>
	<input type="text" name="CustomerMobileNumber" value="{{$transaction->txn_customer_mobile}}"><br>
	<input type="text" name="CustomerEmail" value="{{$transaction->txn_customer_email}}"><br>
	<input type="text" name="SuccessUrl" value="{{config('app.url') . "/kuickpay-success/" . $transaction->id}}"><br>
	<input type="text" name="FailureUrl" value="{{config('app.url') . "/kuickpay-failure/" . $transaction->id}}"><br>
	<input type="text" name="OrderDate" value="{{$transaction->created_at}}"><br>
	<input type="text" name="CheckoutUrl" value="{{config('app.url') . "/kuickpay-ipn/" . $transaction->id}}"><br>
	<input type="text" name="Token" value="{{$kuickpay_token}}"><br>
	<input type="text" name="Signature" value="{{$kuickpay_signature}}"><br>
{{-- <input type="submit" value="submit"> --}}
 </form>
 
 Redirecting to Kuickpay card checkout. Please wait...
<script data-cfasync="false" type="text/javascript">
    document.kuickpayform.submit();
</script>
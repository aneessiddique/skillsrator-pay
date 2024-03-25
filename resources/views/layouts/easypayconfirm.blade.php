@if(isset($_GET['auth_token']))
<form name="easypayform" action=" https://easypaystg.easypaisa.com.pk/easypay/Confirm.jsf " method="POST">
    <input name="auth_token" value="<?php echo $_GET['auth_token'] ?>" hidden="true" />
    <input name="postBackURL" value="{{config('app.url')}}/easypayCallback" hidden="true" />
    <!-- <input value="confirm" type="submit" name="pay" /> -->
</form>
Redirecting to Easy Paisa checkout. Please wait...
<script data-cfasync="false" type="text/javascript">
    document.easypayform.submit();
</script>
@elseif(isset($_GET['orderRefNumber']) && isset($_GET['amount']) && isset($_GET['message']))
    <div>Order# {{$_GET['orderRefNumber']}}</div>
    <div>Amount: {{$_GET['amount']}}</div>
    <div>Message: {{$_GET['message']}}</div>
    
@elseif(isset($_GET['orderRefNumber']) && isset($_GET['amount']) && isset($_GET['paymentToken']) && isset($_GET['tokenExpiryDate']))
    <div>Order# {{$_GET['orderRefNumber']}}</div>
    <div>Amount: {{$_GET['amount']}}</div>
    <div>Payment Token: {{$_GET['paymentToken']}}</div>
    <div>Payment Token Expiry: {{$_GET['tokenExpiryDate']}}</div>
@endif
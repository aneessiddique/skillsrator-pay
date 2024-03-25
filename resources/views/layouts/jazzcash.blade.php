<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
<body>

	@include('layouts.header')


	<script>
	function submitForm(mode) {
		
	var phphashC = "{{$phphashC}}";
	var phphashM = "{{$phphashM}}";
		document.getElementById("pp_TxnType").value = mode;
		if(mode == 'MPAY'){
			document.getElementById("pp_SecureHash").value = phphashC;
		} else if(mode == 'MWALLET'){
			document.getElementById("pp_SecureHash").value = phphashM;
		}
		
		// console.log('mode: ' + mode);
		// console.log('phpstring: ' + "{{$phpstring}}");
		// console.log('phphash: ' + document.getElementById("pp_SecureHash").value);

		document.jsform.submit();
	}
</script>
	<!-- Content -->
	<section id="ec-pay-main">
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					<div class="card main-card">
						<div class="card-body">

							<div class="title-price text-center">
							<h1 class="card-title">{{$transaction->txn_payment_type}}</h1>
							<h3 class="price">{{isset($data['txn_currency']) ? $data['txn_currency'] : 'PKR'}} {{$transaction->txn_amount / 100}}</h3>
						</div>

							<h6 class="choose-bank">Choose Payment Mode</h6>

							<div class="card po-card">
								<div class="card-body">

									<ul class="payment-options">

										<!-- Single Payment Option -->
										<li>
											<a class="single-po" onclick="submitForm('MPAY')">
												<div class="left">
													<div class="po-img">
														<img src="/assets/img/payment-option-1.png" alt="" width="auto">
													</div>
													<h4 class="po-name">Credit/Debit Card Payment</h4>
												</div>
											</a>
										</li>

										<!-- Single Payment Option -->
										<!-- <li>
											<a class="single-po" onclick="submitForm('MWALLET')">
												<div class="left">
													<div class="po-img">
														<img src="/assets/img/payment-option-1.png" alt="" width="auto">
													</div>
													<h4 class="po-name">Mobile Account</h4>
												</div>
											</a>
										</li> -->

										<!-- Single Payment Option -->
										<!-- <li>
											<a class="single-po" onclick="submitForm('OTC')">
												<div class="left">
													<div class="po-img">
														<img src="/assets/img/payment-option-1.png" alt="" width="auto">
													</div>
													<h4 class="po-name">Voucher Payment</h4>
												</div>
											</a>
										</li> -->

									</ul>

								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	@include('layouts.footer')

	<!-- Jquery -->
	<script src="/assets/js/jquery-3.6.0.min.js"></script>
	<!-- Bootstrap Script -->
	<script src="/assets/js/bootstrap.bundle.min.js"></script>
	<!-- Main -->
	<script src="/assets/js/main.js"></script>

</body>

<!-- new -->


<!-- <h3>JazzCash HTTP POST (Page Redirection) Testing</h3> -->
<div class="jsformWrapper">
	<form name="jsform" method="post" action="{{env('JAZZCASH_URL')}}">
		<input type="hidden" name="pp_Version" value="1.1">
		<!-- <input type="hidden" name="pp_TxnType" value="MPAY"> -->
		<!-- <input type="hidden" name="pp_TxnType" value="MWALLET"> -->
		<!-- <input type="hidden" name="pp_TxnType" value="OTC"> -->
		<input id="pp_TxnType" type="hidden" name="pp_TxnType" value="">
		<input type="hidden" name="pp_Language" value="EN">
		<input type="hidden" name="pp_MerchantID" value="{{$jazz_merchantid}}">
		<input type="hidden" name="pp_SubMerchantID" value="">
		<input type="hidden" name="pp_Password" value="{{$jazz_password}}">
		<input type="hidden" name="pp_BankID" value="TBANK">
		<input type="hidden" name="pp_ProductID" value="RETL">
		<input type="hidden" name="pp_TxnCurrency" value="PKR">
		<input type="hidden" name="pp_TxnDateTime" value="{{$transaction->pp_TxnDateTime}}">
		<input type="hidden" name="pp_TxnExpiryDateTime" value="{{$transaction->pp_TxnExpiryDateTime}}">


		<!-- <label class="active">pp_TxnRefNo: </label> -->
		<input type="hidden" name="pp_TxnRefNo" id="pp_TxnRefNo" value="{{$transaction->txn_reference}}">

		<!-- <label class="active">pp_Amount: </label> -->
		<input type="hidden" name="pp_Amount" value="{{$transaction->txn_amount}}">

		<!-- <label class="active">pp_BillReference: </label> -->
		<input type="hidden" name="pp_BillReference" value="{{$transaction->txn_customer_bill_order_id}}">

		<!-- <label class="active">pp_Description: </label> -->
		<input type="hidden" name="pp_Description" value="{{$transaction->txn_description}}">

		<!-- <label class="active">pp_ReturnURL: </label> -->
		<input type="hidden" name="pp_ReturnURL" value="{{config('app.url')}}/jazzCallback.php">
		
		<!-- <input type="text" name="txn_platform_return_url" value="http://abc.com/pay.html" /> <br /> -->


		<input type="hidden" id="pp_SecureHash" name="pp_SecureHash" value="">
		<input type="hidden" name="ppmpf_1" value="1">
		<input type="hidden" name="ppmpf_2" value="2">
		<input type="hidden" name="ppmpf_3" value="3">
		<input type="hidden" name="ppmpf_4" value="4">
		<input type="hidden" name="ppmpf_5" value="5">
		<!-- <button type="button" onclick="submitForm()">Submit</button> -->
	</form>

</div>

</html>
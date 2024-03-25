<!DOCTYPE html>
<html lang="en">
@include('layouts.head')

<body>

	@include('layouts.header')

	<!-- Content -->
	<section id="ec-pay-main">
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					<div class="card main-card">
						<div class="card-body">

							<div class="title-price text-center">
								<h1 class="card-title">{{$data['txn_payment_type']}}</h1>
								<h3 class="price">{{isset($data['txn_currency']) ? $data['txn_currency'] : 'PKR'}} {{$data['txn_amount']}}</h3>
							
								@isset($cancelmsg)
								<div class="text-center">
									<h6 class="alert alert-warning">{{$cancelmsg}}</h6>
								</div>
								@endisset
							</div>

							<h6 class="choose-bank">Choose your payment method</h6>

							<div class="card po-card">
								<div class="card-body">

									<ul class="payment-options">
									@if(count($gateways) > 0)
										@foreach($gateways as $gateway)
										<!-- Single Payment Option -->
										<li>
											<a class="single-po" href="/{{$gateway->ec_pay_gateway_url}}/{{$data['id']}}">
												<div class="left">
													<div class="po-img">
														<img src="/{{$gateway->ec_pay_gateway_image}}" alt="" width="auto">
													</div>
													<h4 class="po-name">{{$gateway->ec_pay_gateway_name}}</h4>
												</div>
											</a>
										</li>
										@endforeach
									@else
										<li>
											Sorry no Payment Gateway available at the moment.
										</li>
									@endif
										<!-- Single Payment Option -->
										<!-- <li>
											<a class="single-po" href="/jazzcash/{{$data['id']}}">
												<div class="left">
													<div class="po-img">
														<img src="assets/img/payment-option-1.png" alt="" width="auto">
													</div>
													<h4 class="po-name">Jazz cash</h4>
												</div>
											</a>
										</li> -->

										<!-- Single Payment Option -->
										<!-- <li>
											<a class="single-po" href="#">
												<div class="left">
													<div class="po-img">
														<img src="assets/img/payment-option-2.png" alt="" width="auto">
													</div>
													<h4 class="po-name">Bank Alfalah</h4>
												</div>
											</a>
										</li> -->

										<!-- Single Payment Option -->
										<!-- <li>
											<a class="single-po" href="#">
												<div class="left">
													<div class="po-img">
														<img src="assets/img/payment-option-3.png" alt="" width="auto">
													</div>
													<h4 class="po-name">Standard Chartered</h4>
												</div>
											</a>
										</li> -->

										<!-- Single Payment Option -->
										<!-- <li>
											<a class="single-po" href="#">
												<div class="left">
													<div class="po-img">
														<img src="assets/img/payment-option-4.png" alt="" width="auto">
													</div>
													<h4 class="po-name">UBL</h4>
												</div>
											</a>
										</li> -->

										<!-- Single Payment Option -->
										<!-- <li>
											<a class="single-po" href="#">
												<div class="left">
													<div class="po-img">
														<img src="assets/img/payment-option-5.png" alt="" width="auto">
													</div>
													<h4 class="po-name">Bank Al Habib Ltd.</h4>
												</div>
											</a>
										</li> -->

										<!-- Single Payment Option -->
										<!-- <li>
											<a class="single-po" href="#">
												<div class="left">
													<div class="po-img">
														<img src="assets/img/payment-option-6.png" alt="" width="auto">
													</div>
													<h4 class="po-name">Meezan Bank</h4>
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

</html>
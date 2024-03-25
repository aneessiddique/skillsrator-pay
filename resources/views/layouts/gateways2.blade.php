<!DOCTYPE html>
<html lang="en">
@include('layouts.head2')

<body>

	@include('layouts.header')

	<!-- banner -->
	<div class="container">
		<div class="hero_fees">
			@isset($cancelmsg)
			<div class="text-center">
				<h6 style="color: #721c24;" class="alert alert-danger">{{$cancelmsg}}</h6>
			</div>
			@endisset
			<div class="title-price text-center">
				<h1>{{$data['txn_payment_type']}}</h1>
				<h2 class="price"> {{$data['txn_amount']}} {{isset($data['txn_currency']) ? $data['txn_currency'] : 'PKR'}}</h2>
			</div>

			<form class="form">
				<div class="flex form_row">
					{{-- @if($data['txn_currency'] != 'PKR')
					<div class="form-group col-md-6">
						<label for="">Amount in {{$base_currency}}</label>
						<span>{{$base_currency}}</span>
						<div class="form-control">
							<label for="" id="pay_usd">
								{{$base_amount}}
							</label>
						</div>
						<!-- <input type="number" value="10" placeholder="" id="" name="" class="form-control"> -->
					</div>

					<div class="form-group img">
						<a href=""><img src="img/arrow-shift.png" alt=""></a>
					</div>

					<div class="form-group col-md-6">
						<label for="">Amount in PKR</label>
						<span>PKR</span>
						<div class="form-control">
							<label for="" id="pay_pkr">
								{{$pkr_amount}}
							</label>
						</div>
						<!-- <input type="number" value="172" placeholder="" id="" name="" class="form-control"> -->
					</div>
					@endif --}}
				</div>
			</form>
		</div>
	</div>

	<!-- payment -->
	<section id="ec-pay-main">
		<h4 class="text-center">Please select your preferred payment method</h4>
		<div class="card main-card">
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
									<h4 class="po-name">
										{{$gateway->ec_pay_gateway_name}} 
									</h4>
								</div>
							</a>
							
							@if(isset($gateway->ec_pay_gateway_fee_percent) && $gateway->ec_pay_gateway_fee_percent > 0)
								<h6 style="text-align: center;margin: 10px 0 0;font-size: 14px;"> 
								Amount : {{$base_currency}} {{ number_format($base_amount, 2) }} | 
								Gateway Fee : {{$base_currency}} {{($base_amount * $gateway->ec_pay_gateway_fee_percent / 100) }} | 
								Total : {{$base_currency}} {{ number_format($base_amount + ($base_amount * $gateway->ec_pay_gateway_fee_percent / 100), 2) }} </h6>
							@endif
							@if(isset($gateway->ec_pay_gateway_currency) && $gateway->ec_pay_gateway_currency == 'USD' 
								&& substr($gateway->ec_pay_gateway_url, 0, 6) == 'stripe')
							<span style="text-align: center;font-size: 11px;color: red;font-weight: bold;">
								Note: Stripe's payments platform lets you accept credit cards and debit cards around the world the transaction will be conducted using the current exchange rate at the time of payment.
							</span>
							@endif
						</li>
						@endforeach
						@else
						<li>
							Sorry no Payment Gateway available at the moment.
						</li>
						@endif

					</ul>

				</div>
			</div>
		</div>
	</section>
	<!-- payment -->


	@include('layouts.footer2')

	<!-- Jquery -->
	<script src="/assets2/js/jquery-3.6.0.min.js"></script>
	<!-- Bootstrap Script -->
	<script src="/assets2/js/bootstrap.bundle.min.js"></script>
	<!-- Main -->
	<script src="/assets2/js/main.js"></script>

	@if(count($gateways) == 1 && isset($auto_click) && $auto_click)
	<script>
		$(document).ready(function() {
			$('.payment-options .single-po')[0].click();
		});
	</script>
	@endif

</body>

</html>
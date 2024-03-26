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
				<h6 class="alert alert-warning">{{$cancelmsg}}</h6>
			</div>
			@endisset
			<div class="title-price text-center">
				<h1>{{$transaction->txn_payment_type}}</h1>
				<h2 class="price"> {{$pkr_amount}} PKR</h2>
			</div>

			<form class="form">
				<div class="flex form_row">
					@if($transaction->txn_currency != 'PKR')
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
						<a href=""><img src="/assets2/img/arrow-shift.png" alt=""></a>
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
					@endif
				</div>
			</form>
		</div>
	</div>

	<!-- voucher -->
	<div class="voucher container">
		<div class="row">
			<div class="col-md-9">
				<div class="title-price text-center">
					<h3>Voucher Number</h3>
					<h2 class="price">{{$transaction->txn_reference}}</h2>
				</div>

				@if($transaction->txn_currency != 'PKR')
				<h3>{{$transaction->txn_payment_type}} <br>{{$base_amount}} {{$base_currency}} </h3>
				@endif
				<p>Amount in PKR ~ {{$pkr_amount}}</p>
			</div>

			<!-- logos -->
			<div class="col-md-3 text-center">
				<p class="img">
					<img src="/assets2/img/sr-logo.png" alt="" title="">
				</p><br>
				<p class="img">
					<img src="/assets2/img/one-link.png" alt="" title="">
				</p><br>
				<p class="img">
					<img src="/assets2/img/kuickpay.png" alt="" title="">
				</p>
			</div>
		</div>
	</div>


	<div class="text-center pay_flow">
		<h4>
			<a href="https://app.kuickpay.com/PaymentsBillPayment?cn={{$transaction->txn_reference}}" style="text-decoration: underline;" target="_blank">
				Click here for instructions on how to pay!
			</a>
		</h4>
		<h2>How it works – Payment Flow (Online Banking)</h2>
	</div>

	<!-- sections -->
	<div class="section bg_grey">

		<!-- sbs -->
		<section class="sbs">
			<div class="flex no-flex-xs cnt">
				<div class="txt">
					<div class="content">
						<p>
							<img src="/assets2/img/01.png" alt="" title="">
						</p>
						<h2>Menu selection</h2>
						<p>Select Bill Payment or Online Payment from your respective Internet Banking channel</p>
					</div>
				</div>
				<div class="img" style="background: url(/assets2/img/sbs-img-1.svg);">

				</div>
			</div>
		</section>
		<p class="dots dots-left">
			<img src="/assets2/img/dot1.svg" alt="" title="">
			<img src="/assets2/img/dot-mob.svg" alt="" title="" class="dot_mob">
		</p>

		<!-- sbs -->
		<section class="sbs flip">
			<div class="flex no-flex-xs cnt">
				<div class="txt">
					<div class="content">
						<p>
							<img src="/assets2/img/02.png" alt="" title="">
						</p>
						<h2 class="">select <img src="/assets2/img/kuickpay.png" alt="" style="margin:0 0;"></h2>
						<p>Select Kuickpay from the Billing Companies listed</p>
					</div>
				</div>
				<div class="img img2" style="background: url(/assets2/img/sbs-img-2.svg);">

				</div>
			</div>
		</section>
		<p class="dots dots2 dots-right">
			<img src="/assets2/img/dot2.svg" alt="" title="">
			<img src="/assets2/img/dot-mob2.svg" alt="" title="" class="dot_mob">
		</p>

		<!-- sbs -->
		<section class="sbs">
			<div class="flex no-flex-xs cnt">
				<div class="txt">
					<div class="content">
						<p>
							<img src="/assets2/img/03.png" alt="" title="">
						</p>
						<h2>Voucher Number</h2>
						<p>Input the Voucher number for your transaction</p>
					</div>
				</div>
				<div class="img img3" style="background: url(/assets2/img/sbs-img-3.svg);">
					<label for="" id="pay_label">
					{{$transaction->txn_reference}}
					</label>
				</div>
			</div>
		</section>
		<p class="dots dots3">
			<img src="/assets2/img/dot1.svg" alt="" title="">
			<img src="/assets2/img/dot-mob.svg" alt="" title="" class="dot_mob">
		</p>

		<!-- sbs -->
		<section class="sbs flip">
			<div class="flex no-flex-xs cnt">
				<div class="txt">
					<div class="content">
						<p>
							<img src="/assets2/img/04.png" alt="" title="">
						</p>
						<h2>Confirmation & Payment</h2>
						<p>Verify the details for your invoice and make the payment</p>
					</div>
				</div>
				<div class="img img4" style="background: url(/assets2/img/sbs-img-4.svg);">

				</div>
			</div>
		</section>
		<p class="dots dots4 dots-right">
			<img src="/assets2/img/dot2.svg" alt="" title="">
			<img src="/assets2/img/dot-mob2.svg" alt="" title="" class="dot_mob">
		</p>

		<!-- sbs -->
		<section class="sbs">
			<div class="flex no-flex-xs cnt">
				<div class="txt">
					<div class="content">
						<p>
							<img src="/assets2/img/05.png" alt="" title="">
						</p>
						<h2>Transaction Successful</h2>
						<p>Once transaction is completed, the system will automatically notify us within 24 hours to process your order</p>
					</div>
				</div>
				<div class="img img5" style="background: url(/assets2/img/sbs-img-5.svg);">

				</div>
			</div>
		</section>


		<!-- toll_free -->
		<section class="toll_free">
			<p>In case of any technical issues faced related to the payment, please connect with us on below details</p>

			<div class="flex no-flex-sm">
				<p>Toll Free Number: <span>+92 21 111 00 32 32</span></p>
				<p>Email : info@ec.com.pk</p>
			</div>
		</section>

	</div>



	<!-- sections -->
	<section class="section ">
		<div class="container">
			<div class="text-center">
				<h2>Payment Channels (Internet / Mobile / ATM)</h2>
			</div>
			<!-- <p class="img">
          <img src="/assets2/img/logos-payment.png" alt="" title="">
        </p> -->

			<div class="flex logos_pay">
				<div class="column">
					<div class="img"><img src="/assets2/img/hbl.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/upaisa.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/bk.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/ubl.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/khushhali.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/askari.png" alt=""></div>
				</div>

				<div class="column">
					<div class="img"><img src="/assets2/img/dubai.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/keenu.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/easy.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/bank-alhabib.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/mcb.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/mib.png" alt=""></div>
				</div>

				<div class="column">
					<div class="img"><img src="/assets2/img/payment-option-6.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/sindh.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/payment-option-2.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/faysal.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/jazz.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/silk.png" alt=""></div>
				</div>

				<div class="column">
					<div class="img"><img src="/assets2/img/allied.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/nbp.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/bk.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/nayapay.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/albarka.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/samba.png" alt=""></div>
				</div>

				<div class="column">
					<div class="img"><img src="/assets2/img/soneri.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/alhabib.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/hbl.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/js.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/summit.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/bop.png" alt=""></div>
				</div>

				<div class="column">
					<div class="img"><img src="/assets2/img/bankislami.png" alt=""></div>
				</div>
				<div class="column">
					<div class="img"><img src="/assets2/img/nrsp.png" alt=""></div>
				</div>
			</div>

		</div>
	</section>

	<!-- sections -->
	<section class="section bg_grey">
		<div class="container">
			<div class="text-center">
				<h2>Payments – Over The Counter</h2>
			</div>
			<div class="row">
				<div class="col-md-8">
					<p>Over The Counter (Walk-in)</p><br>
					<p class="img">
						<img src="/assets2/img/counter.png" alt="" title="">
					</p>
				</div>
				<div class="col-md-4">
					<p class="img">
						<img src="/assets2/img/counter-logos.png" alt="" title="">
					</p>
				</div>
			</div>
		</div>
	</section>

	@include('layouts.footer2')

	<!-- Jquery -->
	<script src="/assets/js/jquery-3.6.0.min.js"></script>
	<!-- Bootstrap Script -->
	<script src="/assets/js/bootstrap.bundle.min.js"></script>
	<!-- Main -->
	<script src="/assets/js/main.js"></script>

	<script>
		$(function() {
			$('#cancel-btn').show();
		});
	</script>
</body>

</html>

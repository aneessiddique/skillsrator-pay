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
				<h1>Thank you!</h1>
				<div>Your transaction slip has been received and will be updated soon.</div>
			</div>			
		</div>
	</div>


	@include('layouts.footer2')

	<!-- Jquery -->
	<script src="/assets2/js/jquery-3.6.0.min.js"></script>
	<!-- Bootstrap Script -->
	<script src="/assets2/js/bootstrap.bundle.min.js"></script>
	<!-- Main -->
	<script src="/assets2/js/main.js"></script>

	<script>
		$('.ec-pay-cancel').remove();
	</script>

</body>

</html>
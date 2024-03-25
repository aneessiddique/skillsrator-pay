<!DOCTYPE html>
<html lang="en">
@include('layouts.head2')

<body>

	@include('layouts.header')
<style>
.upload_imgs {
    width: 50%;
    margin-bottom: 20px;
}
.imgUp {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 10px;
    border-radius: 10px;
    border: 1px solid #d7d7d7;
    background-color: #fff;
    width: 100%;
}
.imagePreview {
    background-image: url(/assets/img/upload-sample.svg);
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background-position: center center;
    background-color: #fff;
    background-size: cover;
    background-repeat: no-repeat;
    display: inline-block;
    /* box-shadow: 0px 0px 6px 2px rgb(0 0 0 / 15%); */
    margin-bottom: 15px;
}
.imgUp .upload_txt {
    text-align: center;
    font-size: 13px;
}
.imgUp .upload_txt span {
    color: #0086e4;
    font-weight: bold;
}
.uploadFile {
    width: 0px;
    height: 0px;
    overflow: hidden;
    padding: 0 !important;
}
.card_details {
    margin: 0 auto;
    text-align: left;
    padding: 50px 50px 30px;
}
</style>
	<!-- banner -->
	<div class="container">
		<div class="hero_fees">
			@isset($cancelmsg)
			<div class="text-center">
				<h6 style="color: #721c24;" class="alert alert-danger">{{$cancelmsg}}</h6>
			</div>
			@endisset
			<div class="title-price text-center">				
				<h1>{{$gateway->ec_pay_gateway_name}}</h1>
				<h2 class="price"> {{$data['txn_amount']}} {{isset($data['txn_currency']) ? $data['txn_currency'] : 'PKR'}}</h2>
			</div>

			<form class="form">
				<div class="flex form_row">
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
				</div>
			</form>
		</div>
	</div>

<!-- sections -->
<div class="section bg_grey">
	<!-- toll_free -->
	<section class="toll_free card_details">
		<div class="row">
			<div class="col-md-12 text-center">
				{!! $gateway->ec_pay_gateway_description !!}
			  </div>
		</div>
		<hr>
		<div class="container" style="padding: 20px;background-color: aliceblue;">
			<form action="{{ route('deposit_slip_save', $data['id']) }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
				<div class="row text-center">
					<div class="col-md-12">
						<h2>Enter Payment Details Below.</h2>
					</div>
					@foreach ($gateway->deposit_slip_fields as $deposit_slip_field)
						<div class="col-md-6"><label>{{$deposit_slip_field->field_name}}</label></div>
						<div class="col-md-6"><input type="text" name="{{$deposit_slip_field->id}}" value="" class="form-control"></div>
					@endforeach
					<div class="col-md-12">
						<label class="w-100 p-3 upload_imgs" for="upload_front">
							<div class="imgUp">
								<div class="imagePreview"></div>
								<div class="upload_txt">							
									Upload your payment receipt <br><span>Browse</span>
									<input type="file" id="upload_front" name="slip_image[]" class="uploadFile" value="Upload Photo" multiple required>
										
									{{-- </label> --}}
									{{-- <input type="file" name="slip_image"> --}}							
								</div>
							</div>
						</label>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="gateway_id" value="{{ $gateway->id }}">
						<br>
						<input type="submit" class="btn btn-xs btn-primary" value="{{ trans('global.submit') }}">
					</div>
				</div> 
			</form>
		</div>	
	</section>
</div>

	@include('layouts.footer2')

	<!-- Jquery -->
	<script src="/assets2/js/jquery-3.6.0.min.js"></script>
	<!-- Bootstrap Script -->
	<script src="/assets2/js/bootstrap.bundle.min.js"></script>
	<!-- Main -->
	<script src="/assets2/js/main.js"></script>

	<!-- image upload with preview -->
<script>
	// $(".imgAdd").click(function(){
	//   $(this).closest(".row").find('.imgAdd').before('<div class="col-sm-2 imgUp"><div class="imagePreview"></div><label class="btn btn-primary">Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width:0px;height:0px;overflow:hidden;"></label><i class="fa fa-times del"></i></div>');
	// });
	$(document).on("click", "i.del" , function() {
		$(this).parent().remove();
	});
	$(function() {
		$(document).on("change",".uploadFile", function()
		{
			var uploadFile = $(this);
			var files = !!this.files ? this.files : [];
			if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
  
			if (/^image/.test( files[0].type)){ // only image file
				var reader = new FileReader(); // instance of the FileReader
				reader.readAsDataURL(files[0]); // read the local file
  
				reader.onloadend = function(){ // set image data as background of div
					//alert(uploadFile.closest(".upimage").find('.imagePreview').length);
					uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url("+this.result+")");
				}
			}
  
		});

		$('#cancel-btn').show();
	});
  </script>

</body>

</html>
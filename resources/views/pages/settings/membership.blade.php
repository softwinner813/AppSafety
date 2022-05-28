@extends('layout.default')

@section('styles')
<link href="/css/pages/wizard/wizard-3.css" rel="stylesheet" type="text/css">
<style type="text/css">
	.price-active {
		/*border: 4px solid #6993ff!important;*/
		background-color: #6993ff59!important; 
	}

	.method-active {
		border: 4px solid #6993ff!important;
	}
	.price-switch-left {
	    border-top-left-radius: 15px;
	    border: 1px solid #6993FF;
	    border-bottom-left-radius: 15px;
	}
	.price-switch-right {
	    border-top-right-radius: 15px;
	    border: 1px solid #6993FF;
	    border-bottom-right-radius: 15px;
	}
	.price-switch-active {
		background-color: #6993FF!important;
		color: white!important;
	}
</style>
@endsection

@section('content')

<!--begin::Profile Personal Information-->
<div class="d-flex flex-row">
    @include('pages.settings.aside')

    <!--begin::Content-->
    <div class="flex-row-fluid ml-lg-8">
        <!--begin::Card-->
        <div class="card card-custom">
			<div class="card-body p-0">
				<!--begin: Wizard-->
				<div class="wizard wizard-3" id="kt_wizard_v3" data-wizard-state="step-first" data-wizard-clickable="true">
					<!--begin: Wizard Nav-->
					<div class="wizard-nav">
						<div class="wizard-steps px-8 py-8 px-lg-15 py-lg-3">
							<!--begin::Wizard Step 1 Nav-->
							<div class="wizard-step" data-wizard-type="step" data-wizard-state="current">
								<div class="wizard-label">
									<h3 class="wizard-title">
									<span>1.</span>Membership Type</h3>
									<div class="wizard-bar"></div>
								</div>
							</div>
							<!--end::Wizard Step 1 Nav-->
							<!--begin::Wizard Step 2 Nav-->
							<div class="wizard-step" data-wizard-type="step">
								<div class="wizard-label">
									<h3 class="wizard-title">
									<span>2.</span>Choose Payment</h3>
									<div class="wizard-bar"></div>
								</div>
							</div>
							<!--end::Wizard Step 2 Nav-->
							<!--begin::Wizard Step 3 Nav-->
							<div class="wizard-step" data-wizard-type="step">
								<div class="wizard-label">
									<h3 class="wizard-title">
									<span>3.</span>Subscription</h3>
									<div class="wizard-bar"></div>
								</div>
							</div>
							<!--end::Wizard Step 3 Nav-->
						</div>
					</div>
					<!--end: Wizard Nav-->
					<!--begin: Wizard Body-->
					<div class="row justify-content-center px-lg-10">
						<div class="col-xl-12 col-xl-12 mx-5">
							<!--begin: Wizard Form-->
							<form class="form" id="kt_form">
								<!--begin: Wizard Step 1-->
								<div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
									<div class="card card-custom">
									<div class="card-header">
										<div class="card-title">
											<!-- <span class="card-icon">
												<i class="flaticon2-box-1 text-success"></i>
											</span> -->
											<a href="javascript;" class="price-switch-left py-2 px-3 font-size-h6 price-switch-active" id="monthly-btn">Monthly</a>
											<a href="javascript;" class="price-switch-right py-2 px-3 font-size-h6" id="annual-btn">Annually</a>
										</div>
									</div>
									<div class="card-body" id="month-packages">
										<div class="row my-10">
											@foreach ($mpackages as $key => $package)
											<!--begin: Pricing-->
											<div class="col-md-6 col-xxl-3 border-right-0 border-right-md border-bottom border-bottom-xxl-0">
												<div class="pt-30 pt-md-25 pb-15 px-5  text-center">
													<div class="d-flex flex-center position-relative mb-25">
														<span class="svg svg-fill-primary opacity-4 position-absolute">
															<svg width="175" height="200">
																<polyline points="87,0 174,50 174,150 87,200 0,150 0,50 87,0"></polyline>
															</svg>
														</span>
														<!--begin::Svg Icon | path:assets/media/svg/icons/Shopping/Safe.svg-->
														{!! $package->icon !!}
														<!--end::Svg Icon-->
													</div>
													<span class="font-size-h1 d-block font-weight-boldest text-dark-75 py-2">{{$package->price}}
													<sup class="font-size-h3 font-weight-normal pl-1">{{$package->currency}}</sup></span>
													{!! $package->desc !!}
													<div class="d-flex justify-content-center">
														<button type="button" class="btn btn-primary text-uppercase font-weight-bolder px-15 py-3" onclick="setPrice($(this), {{$package}})">Purchase</button>
													</div>
												</div>
											</div>
											<!--end: Pricing-->
											@endforeach
										</div>
									</div>

									<div class="card-body" id="annual-packages" style="display: none;">
										<div class="row my-10">
											<!--begin: Pricing-->
											@foreach ($apackages as $key => $package)
											<!--begin: Pricing-->
											<div class="col-md-6 col-xxl-3 border-right-0 border-right-md border-bottom border-bottom-xxl-0">
												<div class="pt-30 pt-md-25 pb-15 px-5  text-center">
													<div class="d-flex flex-center position-relative mb-25">
														<span class="svg svg-fill-primary opacity-4 position-absolute">
															<svg width="175" height="200">
																<polyline points="87,0 174,50 174,150 87,200 0,150 0,50 87,0"></polyline>
															</svg>
														</span>
														<!--begin::Svg Icon | path:assets/media/svg/icons/Shopping/Safe.svg-->
														{!! $package->icon !!}
														<!--end::Svg Icon-->
													</div>
													<span class="font-size-h1 d-block font-weight-boldest text-dark-75 py-2">{{$package->price}}
													<sup class="font-size-h3 font-weight-normal pl-1">{{$package->currency}}</sup></span>
													{!! $package->desc !!}
													<div class="d-flex justify-content-center">
														<button type="button" class="btn btn-primary text-uppercase font-weight-bolder px-15 py-3" onclick="setPrice($(this), {{$package}})">Purchase</button>
													</div>
												</div>
											</div>
											<!--end: Pricing-->
											@endforeach
											<!--end: Pricing-->
										</div>
									</div>
								</div>
								</div>
								<!--end: Wizard Step 1-->
								<!--begin: Wizard Step 2-->
								<div class="pb-5" data-wizard-type="step-content">
									<div class="row">
										<div class="col-xl-6" >
											<!--begin::Stats Widget 13-->
											<a href="#" id="paypalBtn" class="card card-custom bg-success bg-hover-state-danger card-stretch gutter-b">
												<!--begin::Body-->
												<div class="card-body">
													<span class="svg-icon svg-icon-white svg-icon-4x ml-n1">
														<img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_37x23.jpg" border="0" alt="PayPal Logo" width="auto" height="80">
													</span>
													<div class="text-inverse-danger font-weight-bolder font-size-h5 mb-2 mt-5">Paypal</div>
													<div class="font-weight-bold text-inverse-danger font-size-sm">Paypal payment</div>
												</div>
												<!--end::Body-->
											</a>
											<!--end::Stats Widget 13-->
										</div>
										<div class="col-xl-6">
											<!--begin::Stats Widget 14-->
											<a href="#"  id="stripeBtn" class="card card-custom bg-danger bg-hover-state-primary card-stretch gutter-b">
												<!--begin::Body-->
												<div class="card-body">
													<span class="svg-icon svg-icon-white svg-icon-3x ml-n1">
														<img src="/media/cards/card.png" height="80">
													</span>
													<div class="text-inverse-primary font-weight-bolder font-size-h5 mb-2 mt-5">Stripe Payment</div>
													<div class="font-weight-bold text-inverse-primary font-size-sm">Creadit Card, Debit Card, Master Card</div>
												</div>
												<!--end::Body-->
											</a>
											<!--end::Stats Widget 14-->
										</div>
										<!--begin::Select-->
										<div class="col-md-12" id="card_form" style="display: none;">
											<hr>

											<div class="row">
												<div class="form-group col-md-6">
													<label>Card Holder Name</label>
													<input type="text" name="cardName" id="cardName" class="form-control" placeholder="Jack Son" >
												</div>
												<div class="form-group col-md-6">
													<label>Card Number</label>
													<input type="text" name="cardNumber" id="cardNumber" class="form-control" placeholder="4242 4242 4242 4242">
												</div>
												
												<div class="form-group col-md-4">
													<label>Expiry Month</label>
													<input type="text" name="expireMonth" id="expireMonth" placeholder="MM" class="form-control">
												</div>
												<div class="form-group col-md-4">
													<label>Expiry Year</label>
													<input type="text" name="expireYear" id="expireYear" class="form-control" placeholder="YY">
												</div>

												<div class="form-group col-md-4">
													<label>CVV</label>
													<input type="password" name="cvv" id="cvv" class="form-control">
												</div>
											</div>
										</div>
										<!--end::Select-->
									</div>
								</div>
								<!--end: Wizard Step 2-->
								<!--begin: Wizard Step 3-->
								<div class="pb-5" data-wizard-type="step-content">
									<h4 class="mb-10 font-weight-bold text-dark">Final Subscripition Checkout</h4>
									<!--begin::Select-->
									<div class="row">
										<div class="col-md-6">
											<div class="d-flex justify-content-center align-item-center flex-column">
												<h3 class="text-center text-dark-50 font-size-h5" id="final-price-type"></h3>
												<div class="d-flex justify-content-center align-item-center">
													<div class="d-flex flex-center position-relative mb-10">
														<!--begin::Svg Icon | path:/media/svg/icons/Shopping/Safe.svg-->
														<span class="svg-icon svg-icon-primary svg-icon-4x" id="final-icon"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Shopping\Money.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													        <rect x="0" y="0" width="24" height="24"/>
													        <path d="M2,6 L21,6 C21.5522847,6 22,6.44771525 22,7 L22,17 C22,17.5522847 21.5522847,18 21,18 L2,18 C1.44771525,18 1,17.5522847 1,17 L1,7 C1,6.44771525 1.44771525,6 2,6 Z M11.5,16 C13.709139,16 15.5,14.209139 15.5,12 C15.5,9.790861 13.709139,8 11.5,8 C9.290861,8 7.5,9.790861 7.5,12 C7.5,14.209139 9.290861,16 11.5,16 Z" fill="#000000" opacity="0.3" transform="translate(11.500000, 12.000000) rotate(-345.000000) translate(-11.500000, -12.000000) "/>
													        <path d="M2,6 L21,6 C21.5522847,6 22,6.44771525 22,7 L22,17 C22,17.5522847 21.5522847,18 21,18 L2,18 C1.44771525,18 1,17.5522847 1,17 L1,7 C1,6.44771525 1.44771525,6 2,6 Z M11.5,16 C13.709139,16 15.5,14.209139 15.5,12 C15.5,9.790861 13.709139,8 11.5,8 C9.290861,8 7.5,9.790861 7.5,12 C7.5,14.209139 9.290861,16 11.5,16 Z M11.5,14 C12.6045695,14 13.5,13.1045695 13.5,12 C13.5,10.8954305 12.6045695,10 11.5,10 C10.3954305,10 9.5,10.8954305 9.5,12 C9.5,13.1045695 10.3954305,14 11.5,14 Z" fill="#000000"/>
													    </g>
													</svg><!--end::Svg Icon--></span>
														<!--end::Svg Icon-->
													</div> &nbsp;&nbsp;
													<span class="font-size-h1 d-block font-weight-boldest text-dark-75 py-2">
														<label id="final-price"></label>
														<sup class="font-size-h3 font-weight-normal pl-1" id="final-currency">$</sup>
													</span>
												</div>
												<div class="d-flex justify-content-center align-item-center">
													<div class="d-flex flex-column justify-content-center align-item-center" id="final-desc"></div>
													
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="d-none justify-content-center align-item-center" id="final-payal-board" >
												<img src="/media/payment/paypal.png" width="250">
											</div>

											<div class="d-none flex-column justify-content-center align-item-center" id="final-stripe-board" >
												<div class="d-flex justify-content-center align-item-center">
													<img class="mb-5" src="/media/payment/card.png" width="150">
												</div>
												<div class="card card-custom card-stretch gutter-b">
													<!--begin::Body-->
													<div class="card-body">
														<div class="row">
															<div class="form-group col-md-6">
																<label>Card Holder Name</label>
																<input type="text" id="final-cardname"  disabled class="form-control" placeholder="Jack Son" >
															</div>
															<div class="form-group col-md-6">
																<label>Card Number</label>
																<input type="text" id="final-cardnumber" disabled class="form-control" placeholder="4242 4242 4242 4242">
															</div>
															
															<div class="form-group col-md-4">
																<label>Expiry Month</label>
																<input type="text" id="final-cardmonth" disabled placeholder="MM" class="form-control">
															</div>
															<div class="form-group col-md-4">
																<label>Expiry Year</label>
																<input type="text" disabled id="final-cardyear" class="form-control" placeholder="YY">
															</div>

															<div class="form-group col-md-4">
																<label>CVV</label>
																<input type="password" id="final-cardcvv" disabled class="form-control">
															</div>
														</div>	
													</div>
													<!--end::Body-->
												</div>
											</div>
										</div>
										
									</div>
									<!--end::Select-->
									
								</div>
								<!--end: Wizard Step 3-->
								
								<!--begin: Wizard Actions-->
								<div class="d-flex justify-content-between border-top mt-5 mb-5 pt-10">
									<div class="mr-2">
										<button type="button" class="btn btn-light-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-prev">Previous</button>
									</div>
									<div>
										<button type="button" class="btn btn-success font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-submit"><i class="fas fa-cart-plus"></i> Purchase</button>
										<button type="button" class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-next">Next</button>
									</div>
								</div>
								<!--end: Wizard Actions-->
							</form>
							<!--end: Wizard Form-->
						</div>
					</div>
					<!--end: Wizard Body-->
				</div>
				<!--end: Wizard-->
			</div>
		</div>
    </div>
    <!--end::Content-->
</div>
<!--end::Profile Personal Information-->


@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="/js/pages/apps/membership.js"></script>

<!--end::Page Scripts-->
@endsection


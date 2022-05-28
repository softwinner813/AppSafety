"use strict";

var priceType = 0;
var methodType = 0;

// Class definition
var KTWizard3 = function () {
	// Base elements
	var _wizardEl;
	var _formEl;
	var _wizardObj;
	var _validations = [];

	// Private functions
	var _initWizard = function () {
		// Initialize form wizard
		_wizardObj = new KTWizard(_wizardEl, {
			startStep: 1, // initial active step number
			clickableSteps: true  // allow step clicking
		});

		// Validation before going to next page
		_wizardObj.on('change', function (wizard) {
			if (wizard.getStep() > wizard.getNewStep()) {
				return; // Skip if stepped back
			}

			// Validate form before change wizard step
			var validator = _validations[wizard.getStep() - 1]; // get validator for currnt step
			var isValid = false;

			if (validator) {
				if( wizard.getNewStep() > 1) {
					if(priceType > 0) {
						isValid = true;
					} else {
						isValid = false;

						Swal.fire({
							text: "Sorry, Please choose membership type!.",
							icon: "error",
							buttonsStyling: false,
							confirmButtonText: "Ok, got it!",
							customClass: {
								confirmButton: "btn font-weight-bold btn-light"
							}
						}).then(function () {
							KTUtil.scrollTop();
						});
					}
				}

				if( wizard.getNewStep() > 2) {
					isValid = false;
					if(methodType == 1 || methodType == 2) {
						if(methodType == 2) {
							validator.validate().then(function (status) {
								if (status == 'Valid') {
									isValid = true;
									wizard.goTo(wizard.getNewStep());
									KTUtil.scrollTop();
								} else {
									isValid = false;

									Swal.fire({
										text: "Sorry, looks like there are some errors detected, please try again.",
										icon: "error",
										buttonsStyling: false,
										confirmButtonText: "Ok, got it!",
										customClass: {
											confirmButton: "btn font-weight-bold btn-light"
										}
									}).then(function () {
										KTUtil.scrollTop();
									});
								}
							});
						} else {
							isValid = true;
						}
					} else {
						isValid = false;

						Swal.fire({
							text: "Sorry, Please select payment method!.",
							icon: "error",
							buttonsStyling: false,
							confirmButtonText: "Ok, got it!",
							customClass: {
								confirmButton: "btn font-weight-bold btn-light"
							}
						}).then(function () {
							KTUtil.scrollTop();
						});
					}
				}

				if(isValid) {
					wizard.goTo(wizard.getNewStep());
					KTUtil.scrollTop();
				}
			}

			return false;  // Do not change wizard step, further action will be handled by he validator
		});

		// Changed event
		_wizardObj.on('changed', function (wizard) {
			
			var step = wizard.getStep();
			// Final Step
			if(step == 3) {
				if(methodType == 1) {
					$('#final-stripe-board').removeClass('d-flex').addClass('d-none');
					$('#final-payal-board').removeClass('d-none').addClass('d-flex');

				} else if(methodType == 2) {
					$('#final-cardname').val($('#cardName').val()); 
					$('#final-cardnumber').val($('#cardNumber').val()); 
					$('#final-cardmonth').val($('#expireMonth').val()); 
					$('#final-cardyear').val($('#expireYear').val()); 
					$('#final-cardcvv').val($('#cvv').val()); 

					$('#final-payal-board').removeClass('d-flex').addClass('d-none');
					$('#final-stripe-board').removeClass('d-none').addClass('d-flex');
				}
			}
			KTUtil.scrollTop();
		});

		// Submit event
		_wizardObj.on('submit', function (wizard) {
			// Validate form before submit
			// var validator = _validations[wizard.getStep() - 1]; // get validator for currnt step

			// if (validator) {
			// 	validator.validate().then(function (status) {
			// 		if (status == 'Valid') {
			// 			_formEl.submit(); // submit form
			// 		} else {
			// 			Swal.fire({
			// 				text: "Sorry, looks like there are some errors detected, please try again.",
			// 				icon: "error",
			// 				buttonsStyling: false,
			// 				confirmButtonText: "Ok, got it!",
			// 				customClass: {
			// 					confirmButton: "btn font-weight-bold btn-light"
			// 				}
			// 			}).then(function () {
			// 				KTUtil.scrollTop();
			// 			});
			// 		}
			// 	});
			// }

			if(priceType == 0 || methodType == 0) {
				Swal.fire({
					text: "Sorry, looks like there are some errors detected, please try again.",
					icon: "error",
					buttonsStyling: false,
					confirmButtonText: "Ok, got it!",
					customClass: {
						confirmButton: "btn font-weight-bold btn-light"
					}
				}).then(function () {
					KTUtil.scrollTop();
				});
			} else {
				// Paypal
				if(methodType == 1) {
					$('#membershipID').val(priceType);
					$('#paypal-form').submit();
				} else if( methodType == 2) {

				}
			}
		});

		// Monthly Switch Click
		$('#monthly-btn').on('click',function(e) {
			e.preventDefault();
			$('.price-switch-active').removeClass('price-switch-active');
			$(this).addClass('price-switch-active');
			
			priceType = 0;
			$('.price-active').removeClass('price-active');
			$('#annual-packages').hide();
			$('#month-packages').show();
		})

		// Annual Switch Click
		$('#annual-btn').on('click',function(e) {
			e.preventDefault();
			$('.price-switch-active').removeClass('price-switch-active');
			$(this).addClass('price-switch-active');
			
			priceType = 0;
			$('.price-active').removeClass('price-active');
			$('#annual-packages').show();
			$('#month-packages').hide();
		})

		// Paypal Button Click
		$('#paypalBtn').on('click', function(e){
			e.preventDefault();
			$('.method-active').removeClass('method-active');
			$(this).addClass('method-active');
			methodType = 1;

			// Hide Stripe Panel
			$('#card_form').hide();

			$('#final-stripe-board').hide();
			$('#final-payal-board').show();
		})

		// Stripe Button Click
		$('#stripeBtn').on('click', function(e){
			e.preventDefault();
			$('.method-active').removeClass('method-active');
			$(this).addClass('method-active');
			methodType = 2;

			// Show Card Panel
			$('#card_form').show();

			$('#final-payal-board').hide();
			$('#final-stripe-board').show();
		})

	}

	var _initValidation = function () {
		// Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
		// Step 1
		_validations.push(FormValidation.formValidation(
			_formEl,
			{
				fields: {
					address1: {
						validators: {
							notEmpty: {
								message: 'Address is required'
							}
						}
					}
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					// Bootstrap Framework Integration
					bootstrap: new FormValidation.plugins.Bootstrap({
						//eleInvalidClass: '',
						eleValidClass: '',
					})
				}
			}
		));

		// Step 2
		_validations.push(FormValidation.formValidation(
			_formEl,
			{
				fields: {
					cardName: {
						validators: {
							notEmpty: {
								message: 'Card holder name is required'
							}
						}
					},
					cardNumber: {
						validators: {
							notEmpty: {
								message: 'Card number is required'
							},
							digits: {
								message: 'Card number is not valid'
							}
						}
					},
					expireMonth: {
						validators: {
							notEmpty: {
								message: 'This field is required'
							},
							digits: {
								message: 'This field is not valid number'
							}
						}
					},
					expireYear: {
						validators: {
							notEmpty: {
								message: 'This field is required'
							},
							digits: {
								message: 'This field is not valid number'
							}
						}
					},
					cvv: {
						validators: {
							notEmpty: {
								message: 'This field is required'
							},
							digits: {
								message: 'The value added is not valid'
							}
						}
					}
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					// Bootstrap Framework Integration
					bootstrap: new FormValidation.plugins.Bootstrap({
						//eleInvalidClass: '',
						eleValidClass: '',
					})
				}
			}
		));

		// Step 3
		_validations.push(FormValidation.formValidation(
			_formEl,
			{
				fields: {
					delivery: {
						validators: {
							notEmpty: {
								message: 'Delivery type is required'
							}
						}
					},
					packaging: {
						validators: {
							notEmpty: {
								message: 'Packaging type is required'
							}
						}
					},
					preferreddelivery: {
						validators: {
							notEmpty: {
								message: 'Preferred delivery window is required'
							}
						}
					}
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					// Bootstrap Framework Integration
					bootstrap: new FormValidation.plugins.Bootstrap({
						//eleInvalidClass: '',
						eleValidClass: '',
					})
				}
			}
		));

		
	}

	return {
		// public functions
		init: function () {
			_wizardEl = KTUtil.getById('kt_wizard_v3');
			_formEl = KTUtil.getById('kt_form');

			_initWizard();
			_initValidation();
		}
	};
}();

jQuery(document).ready(function () {
	KTWizard3.init();


});

function setPrice(e, membership){
	$('.price-active').removeClass('price-active');
	e.parent().parent().addClass('price-active');
	priceType = membership.id;
	var title = membership.type == 0 ? "Monthly Subscription" : "Annual Subscription";
	$('#final-price-type').text(title);
	$('#final-icon').html(membership.icon);
	$('#final-price').html(membership.price);
	$('#final-currency').html(membership.currency);
	$('#final-desc').html(membership.desc);
}
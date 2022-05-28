"use strict";

// Class Definition
var KTLoginGeneral = function() {
    var _handleSignInForm = function() {
        var validation;

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(
			KTUtil.getById('profile_form'),
			{
				fields: {
					name: {
						validators: {
							notEmpty: {
								message: 'Company name is required'
							}
						}
					},
					phonenumber: {
						validators: {
							notEmpty: {
								message: 'Phone number is required'
							}
						}
					},
					address: {
						validators: {
							notEmpty: {
								message: 'Company address is required'
							}
						}
					}
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					bootstrap: new FormValidation.plugins.Bootstrap()
				}
			}
		);

        $('#profile_submit').on('click', function (e) {
            e.preventDefault();

            validation.validate().then(function(status) {
		        if (status == 'Valid') {
				    var form = $("#profile_form");
				    var url = form.attr('action');
			        var data = new FormData(form[0]);
			 		
			 		$(this).attr('disabled', 'true');
				    $.ajax({
				        type: "POST",
				        // enctype: 'multipart/form-data',
				        url: url,
				        // data: form.serialize(),
				        data: data,
				        contentType: false,
						cache: false,
						processData:false,
				        success: function(data) {
				              
				            // Ajax call completed successfully
                             swal.fire({
				                text: "Company information is updated successfully!",
				                icon: "success",
				                buttonsStyling: false,
				                confirmButtonText: "Ok",
				                confirmButtonClass: "btn font-weight-bold btn-light-primary"
				            }).then(function() {
								KTUtil.scrollTop();
							});
				        },
				        error: function(data) {
				              
				            // Some error in ajax call
				            swal.fire({
				                text:data['message'],
				                icon: "error",
				                buttonsStyling: false,
				                confirmButtonText: "Ok",
				                confirmButtonClass: "btn font-weight-bold btn-light"
				            }).then(function() {
								KTUtil.scrollTop();
							});
				        }
				    });
			 		$(this).removeAttr('disabled');


				} else {
					swal.fire({
		                text: "Sorry, looks like there are some errors detected, please try again.",
		                icon: "error",
		                buttonsStyling: false,
		                confirmButtonText: "Ok, got it!",
		                confirmButtonClass: "btn font-weight-bold btn-light"
		            }).then(function() {
						KTUtil.scrollTop();
					});
				}
		    });
        });

       
    }
   
    // Public Functions
    return {
        // public functions
        init: function() {

            _handleSignInForm();
        }
    };
}();

// Class definition
var KTProfile = function () {
	// Elements
	var avatar;
	var offcanvas;

	// Private functions
	var _initAside = function () {
		// Mobile offcanvas for mobile mode
		offcanvas = new KTOffcanvas('kt_profile_aside', {
            overlay: true,
            baseClass: 'offcanvas-mobile',
            //closeBy: 'kt_user_profile_aside_close',
            toggleBy: 'kt_subheader_mobile_toggle'
        });
	}

	var _initForm = function() {
		avatar = new KTImageInput('kt_profile_avatar');
	}

	return {
		// public functions
		init: function() {
			_initAside();
			_initForm();
		}
	};
}();


// Class Initialization
jQuery(document).ready(function() {
    KTLoginGeneral.init();
	KTProfile.init();

});

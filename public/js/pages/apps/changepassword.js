"use strict";

// Class Definition
var KTLoginGeneral = function() {
    var _handleSignInForm = function() {
        var validation;

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(
			KTUtil.getById('changepass_form'),
			{
				fields: {
					oldpass: {
						validators: {
							notEmpty: {
								message: 'Current password is required'
							}
						}
					},
					newpass: {
						validators: {
							notEmpty: {
								message: 'New password is required'
							}
						}
					},
					cpass: {
						validators: {
							notEmpty: {
								message: 'Password is required'
							}
						},
						identical: {
                            compare: function() {
                                return form.querySelector('[name="newpass"]').value;
                            },
                            message: 'The password and its confirm are not the same'
                        }
					}
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					bootstrap: new FormValidation.plugins.Bootstrap()
				}
			}
		);

        $('#changepass_submit').on('click', function (e) {
            e.preventDefault();

            validation.validate().then(function(status) {
		        if (status == 'Valid') {
				    var form = $("#changepass_form");
				    form.submit();


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

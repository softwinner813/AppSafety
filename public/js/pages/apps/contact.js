// Class Definition
var KTAddEmail = function() {

    var _handleAddEmailForm = function() {
        var validation;

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(
			KTUtil.getById('contact_form'),
			{
				fields: {
					name: {
						validators: {
							notEmpty: {
								message: 'Fullname is required'
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
					company: {
						validators: {
							notEmpty: {
								message: 'Comany name is required'
							}
						}
					},
					comment: {
						validators: {
							notEmpty: {
								message: 'Please enter your question'
							}
						}
					},
					email: {
						validators: {
							notEmpty: {
								message: 'Email is required'
							}
						},
                        emailAddress: {
							message: 'The value is not a valid email address'
						}
					},
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					bootstrap: new FormValidation.plugins.Bootstrap()
				}
			}
		);

        $('#sendBtn').on('click', function (e) {
	 		var sendButton = $(this);
            e.preventDefault();

            validation.validate().then(function(status) {
		        if (status == 'Valid') {
		        	var form = $("#contact_form");
				    var url = form.attr('action');
			 		sendButton.attr('disabled', 'true');

				    $.ajax({
				        type: "POST",
				        url: url,
				        data: form.serialize(),
				        success: function(data) {
				            // Ajax call completed successfully
				           if(data.result) {
                           		toastr.info("Your message sent successfully", "SUCCESS");
				           } else {
                           		toastr.error("Some error occurred. Please retry!", "ERROR");
				           }
                           sendButton.removeAttr('disabled');
				        },
				        error: function(data) {
				            console.log("AAAAAAA", data['responseJSON']);
				            // Some error in ajax call
				            var message = 'Error!';
        					if(data['status'] == 500) {
        						message = "Sorry, Server error. Please retry after for a while!";
        					} else {
        					    var res = data['responseJSON'];
        						message = res['message']['email'];
        						if( message == undefined) {
        							message = res['message'];
        						}

        						if( message == undefined) {
        							message = "Some error occured. Please retry!";
        						}

        					}			          
        			        // Some error in ajax call
        			        swal.fire({
        			            text:message,
        			            icon: "error",
        			            buttonsStyling: false,
        			            confirmButtonText: "Ok",
        			            confirmButtonClass: "btn font-weight-bold btn-light"
        			        }).then(function(e) {
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
            _handleAddEmailForm();
        }
    };
}();


jQuery(document).ready(function() {
	KTAddEmail.init();
});

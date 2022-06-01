"use strict";
// Class definition

var KTDatatableHtmlTableDemo = function() {
    // Private functions

    // demo initializer
    var demo = function() {

		var datatable = $('#kt_datatable').KTDatatable({
			data: {
				saveState: {cookie: false},
			},
			search: {
				input: $('#kt_datatable_search_query'),
				key: 'generalSearch'
			},
			columns: [
				{
					field: 'No',
					type: 'number',
				},
				{
					field: 'Email',
					type: 'email',
				}, 
				{
					field: 'Delete',
				}, 
			],
		});



        $('#kt_datatable_search_status').on('change', function() {
            datatable.search($(this).val().toLowerCase(), 'Status');
        });

        $('#kt_datatable_search_type').on('change', function() {
            datatable.search($(this).val().toLowerCase(), 'Type');
        });

        $('#kt_datatable_search_status, #kt_datatable_search_type').selectpicker();

    };

    return {
        // Public functions
        init: function() {
            // init dmeo
            demo();
        },
    };
}();

// Class Definition
var KTAddEmail = function() {

    var _handleAddEmailForm = function() {
        var validation;

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(
			KTUtil.getById('kt_add_email_form'),
			{
				fields: {
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
					name: {
						validators: {
							notEmpty: {
								message: 'Name is required'
							}
						}
					},
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					bootstrap: new FormValidation.plugins.Bootstrap()
				}
			}
		);

        $('#add_btn').on('click', function (e) {
            e.preventDefault();

            validation.validate().then(function(status) {
		        if (status == 'Valid') {
		        	var form = $("#kt_add_email_form");
				    var url = form.attr('action');
			        // var data = new FormData(form[0]);
			 		
			 		$(this).attr('disabled', 'true');
				    $.ajax({
				        type: "POST",
				        url: url,
				        data: form.serialize(),
				        success: function(data) {
				            $('#exampleModalCustomScrollable').modal('hide');  
				            // Ajax call completed successfully
                           toastr.info("Email added successfully", "SUCCESS");

                           location.reload();
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
            _handleAddEmailForm();
        }
    };
}();


jQuery(document).ready(function() {
	KTDatatableHtmlTableDemo.init();
	KTAddEmail.init();


});

function deleteEmail(id) {
	console.log("FDSFSD=====================>",id);

	swal.fire({
	    title: "Warning",
        text:"Are you sure to remove this email from your list?",
        icon: "warning",
        buttonsStyling: false,
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        confirmButtonClass: "btn font-weight-bold btn-light",
        showCancelButton: true,
	    customClass: {
		   confirmButton: "btn btn-danger",
		   cancelButton: "btn btn-success"
     	}	
    }).then(function(result) {
		// KTUtil.scrollTop();
		if (result.value) { 
			$.ajax({
		    type: "POST",
			    url: '/setting/employee/delete',
			    data: {id: id},
			    success: function(data) {
			        // Ajax call completed successfully
			       toastr.info("Email deleted from your list successfully", "SUCCESS");
			       location.reload();
			    },
			    error: function(data) {
			          
			        // Some error in ajax call
			        swal.fire({
			            text:data['message'],
			            icon: "error",
			            buttonsStyling: false,
			            confirmButtonText: "Ok",
			            confirmButtonClass: "btn font-weight-bold btn-light"
			        }).then(function(e) {
						KTUtil.scrollTop();
					});
			    }
			});
		} else if (result.dismiss === "cancel") {
            
        }
		
	});	
	
}

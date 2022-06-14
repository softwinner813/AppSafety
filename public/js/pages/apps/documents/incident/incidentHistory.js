"use strict";
// Class definition

var KTDatatableHtmlTableDemo = function() {
    // Private functions
    var demo = function() {
        var datatable = $('#kt_datatable').KTDatatable({
            // datasource definition
            data: {
				// saveState: {cookie: false},
			},
            // layout definition
            layout: {
                scroll: false,
                footer: false,
            },

            // column sorting
            sortable: true,

            pagination: true,

            search: {
                input: $('#kt_datatable_search_query'),
                key: 'generalSearch'
            },

            // columns definition
            columns: [{
                field: '#No',
                title: '#No',
                sortable: 'asc',
                width: 30,
                type: 'number',
                selector: false,
                autoHide: false,
                textAlign: 'center',
            }, {
                field: 'from',
                // width: 500,
                autoHide: false,
                title: 'from',
            },
             {
                field: 'To',
                width: 200,
                autoHide: false,
                title: 'To',
            }, {
                field: 'Created',
                title: 'Created',
                width: 200,
                // autoHide: false,
            }, {
                field: 'Actions',
                title: 'Actions',
                sortable: false,
                width: 100,
                overflow: 'visible',
                // autoHide: false,
            }],

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
        // public functions
        init: function() {
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

function deleteDoc(id) {
	console.log("FDSFSD=====================>",id);

	swal.fire({
	    title: "Warning",
        text:"Are you sure to delete this document?",
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

			$('#progressModal').modal('show');
			$.ajax({
		    type: "POST",
			    url: '/document/delete',
			    data: {id: id},
			    success: function(data) {
		       		$('#progressModal').modal('hide');
			        
			        // Ajax call completed successfully
  			        toastr.info("Document deleted successfully", "SUCCESS");
			        location.reload();
			    },
			    error: function(data) {
		       		$('#progressModal').modal('hide');
			          
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

function resendDoc(id) {
	console.log("FDSFSD=====================>",id);

	$('#progressModal').modal('show');
	$.ajax({
	    type: "POST",
		    url: '/document/induction/resend',
		    data: {id: id},
		    success: function(data) {
		        // Ajax call completed successfully
		       	$('#progressModal').modal('hide');
		       toastr.info("Email sent successfully", "SUCCESS");

		    },
		    error: function(data) {
		       	$('#progressModal').modal('hide');
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

}

function showShareModal(id) {
    $('#doc_id').val(id);
    $('#sendEmailModal').modal('show');
}

$('#sendEm_btn').click(function(e){
    e.preventDefault();
    var id = $('#doc_id').val();
    var email = $('#email').val();
    console.log("Share Document: ", id, email);

    $.ajax({
    type: "POST",
        url: '/document/sendEmail',
        data: {id: id, email: email},
        success: function(data) {
            console.log("result", data);
            // Ajax call completed successfully
           toastr.info("Document shared successfully", "SUCCESS");
           // location.reload();
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
}) 

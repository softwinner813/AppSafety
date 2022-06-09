var pdf;
var file;

function selectTemplate(filename) {
    path = "Guidances";
    var dir = '/template/' + path + '/' + filename; 
    console.log(dir);

    pdf = new PDFAnnotate("pdf-container", dir, {
      onPageUpdated(page, oldData, newData) {
        console.log(page, oldData, newData);
      },
      ready() {
        console.log("Plugin initialized successfully");
        file = filename;

        $('#template_board').removeClass('d-flex').addClass('d-none');
        $('#sendBtn').removeClass('d-none');
        $('#showTmpBtn').removeClass('d-none');

        $('#filename').val(filename);
      },
      scale: 2,
      pageImageCompression: "MEDIUM", // FAST, MEDIUM, SLOW(Helps to control the new PDF file size)
    });

}

function showTemplates() {
    $('#pdf-container').html('');
    $('#showTmpBtn').addClass('d-none');
    $('#sendBtn').addClass('d-none');
    $('#template_board').removeClass('d-none').addClass('d-flex');

}




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

        $('#sendEm_btn').on('click', function (e) {
            e.preventDefault();

            validation.validate().then(function(status) {
                if (status == 'Valid') {
                    $('#sendEm_btn').attr('disabled', true);
                    $('#progressModal').modal('show');
                    $('#kt_add_email_form').submit();
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

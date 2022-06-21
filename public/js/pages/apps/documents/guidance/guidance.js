var pdf;
var file;
var isSignActive = false;


// Load Document
function loadDocument(path) {
    

    pdf = new PDFAnnotate("pdf-container", path, {
      onPageUpdated(page, oldData, newData) {
        console.log(page, oldData, newData);
      },
      ready() {
        console.log("Plugin initialized successfully");
        file = filename;

        // $('#template_board').removeClass('d-flex').addClass('d-none');
        // $('#sendBtn').removeClass('d-none');
        // $('#showTmpBtn').removeClass('d-none');

        // Init Singature Text
        pdf.enableAddText('', true);
        $('canvas').trigger('click');


      },
      scale: 1.5,
      pageImageCompression: "SLOW", // FAST, MEDIUM, SLOW(Helps to control the new PDF file size)
    });

}


function changeActiveTool(event) {
    var element = $(event.target).hasClass(".menu-item")
      ? $(event.target)
      : $(event.target).parents(".menu-item").first();
    // var element = event.target;
    $(".menu-item.active").removeClass("active");
    $(element).addClass("active");
}

function enableSelector(event) {
    event.preventDefault();
    changeActiveTool(event);
    pdf.enableSelector();
}

function enablePencil(event) {
    event.preventDefault();
    changeActiveTool(event);
    pdf.enablePencil();
}

function enableAddText(event, text, isSign = false) {
    event.preventDefault();
    changeActiveTool(event);
    pdf.enableAddText(text, isSign);
}

function enableAddArrow(event) {
    event.preventDefault();
    changeActiveTool(event);
    pdf.enableAddArrow();
}

function addImage(event) {
    event.preventDefault();
    pdf.addImageToCanvas(event)
}

function enableRectangle(event) {
    event.preventDefault();
    changeActiveTool(event);
    pdf.setColor('rgba(255, 0, 0, 0.3)');
    pdf.setBorderColor('blue');
    pdf.enableRectangle();
}

function deleteSelectedObject(event) {
  event.preventDefault();
  pdf.deleteSelectedObject();
}


function savePDF(callback) {
    if(pdf != undefined) {
        setTimeout(function(){
            pdf.getBlob(function(blob){
                return callback(blob);
            }); // save with given file name
        }, 500);
    }

}

// Get Fill-Form 
function getFillForm(callback) {
    if(pdf != undefined) {
        let json = pdf.serializePdf();
        return callback(json);
    }
}

function showModal(id, show) {
    $(id).modal(show);
}

function clearPage() {
    pdf.clearActivePage();
}

function showPdfData() {
    var string = pdf.serializePdf();
    $('#dataModal .modal-body pre').first().text(string);
    PR.prettyPrint();
    $('#dataModal').modal('show');
}

// Show/Hide Next&Finish Button
function toggleNextFinish(isNext) {
    if(isNext) {
        $('#finishBtnPanel').hide();
        $('#nextBtnPanel').show();
        
        $('#fill-form-field').hide();
        $('#standard-field').show();

    } else {
        $('#nextBtnPanel').hide();
        $('#finishBtnPanel').show();

        $('#standard-field').hide();
        $('#fill-form-field').show();
    }
}

// Handle Document
var KTHandleDocument = function() {
    var _initLoad = function() {
        var path = '/template/Guidances/' + filename; 
        console.log(path);
        loadDocument(path);

        
        // Show Next Button
        toggleNextFinish(true);
    }

    var _handleDocument = function() {

        var validation;
        // var filepath;
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
                    subject: {
                        validators: {
                            notEmpty: {
                                message: 'Subject is required'
                            }
                        },
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap()
                }
            }
        );

        // Next Button
        $('.next-btn').on('click', function (e) {
            e.preventDefault();
            // Show Progress Dialog
            showModal('#progressModal', 'show');

            // Disable button
            // $(this).attr('disabled', true);

            savePDF(function(blob){
                console.log("========>", blob);

                // Upload Document to server
                filename = "Guidance" + '-' + `${new Date().getTime()}`;
                var fd = new FormData();
                fd.append('filename', filename);
                fd.append('document', blob);
                $.ajax({
                    type: 'POST',
                    url: '/document/guidance/upload',
                    data: fd,
                    processData: false,
                    contentType: false
                }).done(function(data) {
                       console.log(data);
                       if(data.result) {
                            filepath = data.file;
                            $('#filepath').val(filepath);
                            loadDocument('/' + filepath);
                            showModal('#progressModal', 'hide');

                            // Show Finish Button
                            toggleNextFinish(false);

                            // Show Message
                            toastr.info("Your signature saved successfully!", "SUCCESS");

                       }
                }).fail(function(xhr, status, error) {
                      //Ajax request failed.
                      var errorMessage = xhr.status + ': ' + xhr.statusText
                      // alert('Error - ' + errorMessage);
                      showModal('#progressModal', 'hide');

                      // Show Message
                      toastr.error(errorMessage, "ERROR");
                });

            })

        });


        // Submit Button
        $('#sendEm_btn').on('click', function (e) {
            e.preventDefault();

            validation.validate().then(function(status) {
                if (status == 'Valid') {
                    $('#sendEm_btn').attr('disabled', true);
                    showModal('#progressModal', 'show');

                    getFillForm(function(json) {
                        var jsonData = JSON.parse(json);
                        $('#fills').val(JSON.stringify(jsonData));
                        

                        // Hide Email Modal
                        showModal('#sendEmailModal', 'hide');

                        // Show Progress Modal
                        showModal('#progressModal', 'show');
                        
                        // Submit Data
                        $('#kt_add_email_form').submit();

                    });

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

        // Change Color
        $('.color-tool').click(function () {
            $('.color-tool.active').removeClass('active');
            $(this).addClass('active');
            color = $(this).get(0).style.backgroundColor;
            pdf.setColor(color);
        });

        // Change Brush Size
        $('#brush-size').change(function () {
            var width = $(this).val();
            pdf.setBrushSize(width);
        });

        // Change Font Size
        $('#font-size').change(function () {
            var font_size = $(this).val();
            pdf.setFontSize(font_size);

        });


        // Change Document
        $('#documentFile').change(function(e){
            var filename = e.target.files[0];
            $('#uploadFileTxt').text(filename.name);
        });

        // Change Draw Signature        
        $('#selected_sign').click(function(){
            signData = $(this).attr('src');
            if(signData != undefined && signData != '') {
                isSignActive = true;
            }
        });

        // Attach Draw Signature when mouse click
        $('#pdf-container').mouseup(function(e) {
            if(isSignActive) {
                const target = e.target;

                // Get the bounding rectangle of target
                const rect = target.getBoundingClientRect();

                // Mouse position
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                console.log("Mouse Position: ", x, y);
                pdf.addSignature(signData, x, y, function(){
                    signData = '';
                    isSignActive = false;
                });
            }
        });

    }

    // Public Functions
    return {
        // public functions
        init: function() {
            _initLoad();
            _handleDocument();
        }
    };
}();


jQuery(document).ready(function() {
    KTHandleDocument.init();
});

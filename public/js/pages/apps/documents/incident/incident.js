var pdfRowData ;
var isSignActive = false;
var signData;
// var pdf = new PDFAnnotate("pdf-container", '/'+ filepath, {
//   onPageUpdated(page, oldData, newData) {
//     console.log(page, oldData, newData);
//   },
//   ready() {
//     console.log("Plugin initialized successfully");
//   },
//   scale: 1.5,
//   pageImageCompression: "MEDIUM", // FAST, MEDIUM, SLOW(Helps to control the new PDF file size)
// });

// $('input[type=file]').change(function () {


function selectTemplate(filename) {
    path = "Incidents";
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

$('.loadPDF').change(function (e) {
    // document.querySelector(domID).addEventListener("change", function(e){
        var canvasElement = document.querySelector("canvas")
        var file = e.target.files[0]
        if(file.type != "application/pdf"){
            console.error(file.name, "is not a pdf file.")
            return
        }
        
        var fileReader = new FileReader();

        fileReader.onload = function() {
            var typedarray = new Uint8Array(this.result);
            console.log("ARRAY", typedarray);

            pdf = new PDFAnnotate("pdf-container", typedarray, {
              onPageUpdated(page, oldData, newData) {
                console.log(page, oldData, newData);
              },
              ready() {
                console.log("Plugin initialized successfully");
              },
              scale: 1.5,
              pageImageCompression: "MEDIUM", // FAST, MEDIUM, SLOW(Helps to control the new PDF file size)
            });
        };

        fileReader.readAsArrayBuffer(file);
    // })
});


function changeActiveTool(event) {
    var element = $(event.target).hasClass("tool-button")
      ? $(event.target)
      : $(event.target).parents(".tool-button").first();
    $(".tool-button.active").removeClass("active");
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

function enableAddText(event) {
    event.preventDefault();
    changeActiveTool(event);
    pdf.enableAddText();
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

function savePDF() {
    filename = "Incident" + '-' + `${new Date().getTime()}.pdf`;
    if(pdf != undefined) {
        $('#progressModal').modal('show');
        setTimeout(function(){
            pdf.savePdf(filename, function(){
                $('#progressModal').modal('hide');
                $('#uploadModal').modal('show');
            }); // save with given file name
        }, 500);
    }

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



function changeUserType(myRadio) {
    currentValue = myRadio.value;
    console.log("fdsfsdfsd",currentValue);
    if(currentValue == 1) {
        $('#paidEmail').hide();
        $('#nonePaidEmail').show();
    } else if(currentValue == 2) {
        $('#nonePaidEmail').hide();
        $('#paidEmail').show();
    }
}

$(function () {
    $('.color-tool').click(function () {
        $('.color-tool.active').removeClass('active');
        $(this).addClass('active');
        color = $(this).get(0).style.backgroundColor;
        pdf.setColor(color);
    });

    $('#brush-size').change(function () {
        var width = $(this).val();
        pdf.setBrushSize(width);
    });

    $('#font-size').change(function () {
        var font_size = $(this).val();
        pdf.setFontSize(font_size);

    });

    $('#documentFile').change(function(e){
        var filename = e.target.files[0];
        $('#uploadFileTxt').text(filename.name);
    });

    $('#selected_sign').click(function(){

        signData = $(this).attr('src');
        console.log("SignData", signData);
        if(signData != undefined && signData != '') {
            isSignActive = true;
        }
    });

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
});



var pdfRowData ;
// var pdf = new PDFAnnotate("pdf-container", "http://localhost:8000/uploads/test.pdf", {
//   onPageUpdated(page, oldData, newData) {
//     console.log(page, oldData, newData);
//   },
//   ready() {
//     console.log("Plugin initialized successfully");
//   },
//   scale: 1.5,
//   pageImageCompression: "MEDIUM", // FAST, MEDIUM, SLOW(Helps to control the new PDF file size)
// });
var pdf;
var isSignActive = false;
var signData;
// $('input[type=file]').change(function () {
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

function selectTemplate(filename) {
    var path = "";
    switch (type * 1) {
        case 1:
            path = "RA";
            break;

        case 2:
            path = "Audits";
            break;

        case 3:
            path = "Permits";
            break;

        case 4:
            path = "Guidances";
            break;
        case 5:
            path = "Incidents";
            break;
        case 6:
            path = "Inductions";
            break;
        default:
            // code...
            break;
    }
    var dir = '/template/' + path + '/' + filename; 
    console.log(dir);

    pdf = new PDFAnnotate("pdf-container", dir, {
      onPageUpdated(page, oldData, newData) {
        console.log(page, oldData, newData);
      },
      ready() {
        console.log("Plugin initialized successfully");
        $('#template_board').removeClass('d-flex').addClass('d-none');
      },
      scale: 1.5,
      pageImageCompression: "MEDIUM", // FAST, MEDIUM, SLOW(Helps to control the new PDF file size)
    });


}


function showTemplates() {
    $('#pdf-container').html('');
    $('#template_board').removeClass('d-none').addClass('d-flex');
}

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
    // pdf.savePdf();
    var filename = getTypeName(type);
    filename = filename + '-' + `${new Date().getTime()}.pdf`;
    if(pdf != undefined) {
        $('#staticBackdrop').modal('show');
        setTimeout(function(){
            pdf.savePdf(filename, function(){
                $('#staticBackdrop').modal('hide');
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

function getTypeName(type) {
    switch (type) {
        case '1':
            return "Risk Assessment";
            break;
        case '2':
            return "AUDITS";
            break;
        case '3':
            return "Permits";
            break;
        case '4':
            return "Guidance";
            break;
        case '5':
            return "Incidents";
            break;
        case '6':
            return "Inductions";
            break;
        default:
            return "Document"
            break;
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



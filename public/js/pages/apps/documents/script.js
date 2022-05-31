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
            // PDFJS.getDocument(typedarray).then(function(pdf) {
            //  // you can now use *pdf* here
            //  console.log("the pdf has ",pdf.numPages, "page(s).")
            //  pdf.getPage(pdf.numPages).then(function(page) {
            //      // you can now use *page* here
            //      var viewport = page.getViewport(2.0);
            //      var canvas = document.querySelector("canvas")
            //      canvas.height = viewport.height;
            //      canvas.width = viewport.width;


            //      page.render({
            //          canvasContext: canvas.getContext('2d'),
            //          viewport: viewport
            //      });
            //  });

            // });

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
    pdf.addImageToCanvas()
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
    // $('#kt_header')
    // kt_header
});

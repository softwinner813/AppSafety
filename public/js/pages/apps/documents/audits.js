function selectTemplate(url) {
	console.log("Template URL", url);
	$('#form_frame').attr('src', url);
    $('#template_board').removeClass('d-flex').addClass('d-none');
	$('#form_board').show();
	$('#tempBtn').show();

}

function showTemplates() {
    $('#form_board').hide();
    $('#tempBtn').hide();
    $('#template_board').removeClass('d-none').addClass('d-flex');
}

$(function () {
    $('#form_board').hide();
    $('#tempBtn').hide();
    
});
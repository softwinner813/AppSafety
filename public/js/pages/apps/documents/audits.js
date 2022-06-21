function selectTemplate(url) {
    heightOfContainer();
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

function heightOfContainer() {
    let h = $("#kt_content").innerHeight();
    let th = $("#tempBtn").innerHeight();
    $('#form_board').innerHeight(h - th);
}

$(function () {
    $('#form_board').hide();
    $('#tempBtn').hide();
    heightOfContainer();
});
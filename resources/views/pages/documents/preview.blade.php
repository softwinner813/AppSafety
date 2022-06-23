@extends('layout.default')

@section('content')
	@if ($message = Session::get('success'))
	<?php Session::forget('success');?>
	@endif

    <div class="container-fluid p-0"  style='background-color: #eef0f8; font-family: "Helvetica Neue", "Helvetica", "Arial", "sans-serif"; height: 100% ; '>
		<div class="row" style="height: 100%;">
			
			<div class="col-md-12 col-xs-9 bg-green " id="pdf-wrapper" style="background-color: #c0c0c0; position: relative; min-height:100% ; height: calc(100% + 20px); overflow-y: auto;">
				<div id="pdf-container" style="width: 100%;padding-top: 10px; background-color: #c0c0c0;" class="d-flex flex-column justify-content-center align-item-center" ></div>
			</div>
				
		</div>
    </div>

	@include('layout.partials.extras._progressModal')
@endsection

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.css">
<link rel="stylesheet" href="/css/pages/documents/styles.css">
<link rel="stylesheet" href="/css/pages/documents/pdfannotate.css">
<link rel="stylesheet" href="/css/pages/documents/component.css">
<!-- <link rel="stylesheet" href="/css/pages/documents/normalize.css"> -->

<style type="text/css">
	.tool-title {
	    text-transform: uppercase;
	    font-weight: bold;
	    font-size: 15px;
	    margin-bottom: 5px;
	    margin-left: 24px;
	    letter-spacing: 0.6px;
	}
	.sidebar_group {
	    border-top: 1px solid #d9d9d9;
	    margin-top: 0 30px;
	    padding: 20px 20px;
	}
	.menu-fields .menu-item{
		color: #333!important;
	    cursor: pointer;
	    display: block!important;
	    font-size: 13px;
	    /*line-height: 16px;*/
	    padding-bottom: 5px;
	    padding-left: 10px;
	    padding-right: 10px;
	    padding-top: 5px;
	    font-weight: bold;
	    margin-bottom: 5px;
	    border-radius: 5px;
	    z-index: 100;
	}
	.menu-fields .menu-item:hover {
		background-color: #d2d2d2;
	}
	.menu-fields  .menu-item.active {
		background-color: #d2d2d2;
	}
	.color-tool{
		width: 30px;
		height: 30px;
		border-radius: 100%;
		border: none;
		margin-left: 3px;
	}
	.color-tool.active {
		border: 3px solid #7b09d2;
	}
	.btn-caret-right{
		background-color: #ffc820 !important;
		z-index: 10000;
	}
	.btn-caret-right::before {
	    border-left-color: #ffc820 !important;
	    content: "";
	    position: absolute;
	    top: 50%;
	    margin-top: -18px;
	    border-top: 18px solid transparent;
	    border-bottom: 18px solid transparent;
	    border-right: 1em solid #ffc820;
	    /*right: -1em;*/
	   /* content:"\A";
	    border-style: solid;
	    border-width: 10px 15px 10px 0;
	    border-color: transparent #ffc820 transparent transparent;
	    position: absolute;*/
	    left: -12px;
	}
</style>
@endsection

{{-- Scripts Section --}}
@section('scripts')

<script src="/js/pdfjs/pdf.js"></script>
<script src="/js/pdfjs/pdf.worker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.3.0/fabric.min.js"></script>
<script src="https://rawgit.com/bramstein/fontfaceobserver/master/fontfaceobserver.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.2.0/jspdf.umd.min.js"></script>
<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.js"></script>


<script src="/js/pages/apps/documents/arrow.fabric.js"></script>
<script src="/js/pages/apps/documents/pdfannotate.js"></script>
<script type="text/javascript">
	var filepath = `{!! $filepath !!}`;
	pdf = new PDFAnnotate("pdf-container", '/' + filepath, {
      onPageUpdated(page, oldData, newData) {
        console.log(page, oldData, newData);
      },
      ready() {
        console.log("Plugin initialized successfully");
      },
      scale: 1.5,
      pageImageCompression: "SLOW", // FAST, MEDIUM, SLOW(Helps to control the new PDF file size)
    });
</script>
@endsection


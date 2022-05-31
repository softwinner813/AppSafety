@extends('layout.default')

@section('content')
<div class="body">
	<div class="toolbar px-20">
		<div class="tool">
			<div class="box d-flex">
				<input type="file" name="loadPDF" id="loadPDF" class="loadPDF inputfile inputfile-1 d-none" accept=".pdf, .PDF"/>
				<label for="loadPDF" class=" py-1 px-5"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span class="font-size-sm">Choose a file&hellip;</span></label>
			</div>
		</div>
		<div class="tool">
			<label for="">Brush size</label>
			<input type="number" class="form-control text-right" value="1" id="brush-size" max="50">
		</div>
	
		<div class="tool">
			<label for="">Font size</label>
			<select id="font-size" class="form-control">
				<option value="10">10</option>
				<option value="12">12</option>
				<option value="16" selected>16</option>
				<option value="18">18</option>
				<option value="24">24</option>
				<option value="32">32</option>
				<option value="48">48</option>
				<option value="64">64</option>
				<option value="72">72</option>
				<option value="108">108</option>
			</select>
		</div>
		<div class="tool">
			<button class="color-tool active" style="background-color: #212121;"></button>
			<button class="color-tool" style="background-color: red;"></button>
			<button class="color-tool" style="background-color: blue;"></button>
			<button class="color-tool" style="background-color: green;"></button>
			<button class="color-tool" style="background-color: yellow;"></button>
		</div>
		<div class="tool">
			<button class="tool-button active"><i class="fa fa-hand-paper-o" title="Free Hand" onclick="enableSelector(event)"></i></button>
		</div>
		<div class="tool">
			<button class="tool-button"><i class="fa fa-pencil" title="Pencil" onclick="enablePencil(event)"></i></button>
		</div>
		<div class="tool">
			<button class="tool-button"><i class="fa fa-font" title="Add Text" onclick="enableAddText(event)"></i></button>
		</div>
		<div class="tool">
			<button class="tool-button"><i class="fa fa-long-arrow-right" title="Add Arrow" onclick="enableAddArrow(event)"></i></button>
		</div>
		<div class="tool">
			<button class="tool-button"><i class="fa fa-square-o" title="Add rectangle" onclick="enableRectangle(event)"></i></button>
		</div>
		<div class="tool">
			<button class="tool-button"><i class="fa fa-picture-o" title="Add an Image" onclick="addImage(event)"></i></button>
		</div>
		<div class="tool">
			<button class="btn btn-danger btn-sm" onclick="deleteSelectedObject(event)"><i class="fa fa-trash"></i></button>
		</div>
		<div class="tool">
			<button class="btn btn-danger btn-sm" onclick="clearPage()">Clear Page</button>
		</div>
		<div class="tool">
			<button class="btn btn-info btn-sm" onclick="showPdfData()">{}</button>
		</div>
		<div class="tool">
			<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#uploadModal"><i class="fas fa-upload"></i>&nbsp;Upload</button>
		</div>
		<div class="tool">
			<button class="btn btn-primary btn-sm" onclick="savePDF()"><i class="fa fa-save"></i> Save</button>
		</div>
	</div>
	<div class="d-flex flex-column-fluid body">
	    <div class="{{ Metronic::printClasses('content-container', false) }}">
			<div class="row">
				<div class="col-md-12">
					@if ($message = Session::get('error'))
				  <div class="alert alert-custom alert-notice alert-light-danger mt-20 fade show" role="alert">
					    <div class="alert-icon"><i class="flaticon-warning"></i></div>
					    <div class="alert-text">{!! $message !!}</div>
					    <div class="alert-close">
					        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
					            <span aria-hidden="true"><i class="ki ki-close"></i></span>
					        </button>
					    </div>
					</div>
					<?php Session::forget('error');?>
					@endif
					<div id="pdf-container" class="d-flex flex-column justify-content-center align-item-center">
						<div class="">
							<input type="file" name="loadPDF1" id="loadPDF1" class="loadPDF inputfile inputfile-4 d-none" accept=".pdf, .PDF" >
							<label for="loadPDF1"><figure><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg></figure> <span >Choose a PDF file...</span></label>
						</div>
					</div>
				</div>
			</div>
	    </div>
	</div>
</div>

<!-- Progress Dialog -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" style="width: 80px!important;">
    <div class="modal-content">
      
      <div class="modal-body d-flex flex-column justify-content-center align-item-center">
      	<div class="spinner-border text-primary" role="status">
		  <span class="sr-only">Loading...</span>
		</div>
      </div>
    </div>
  </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<form action="{!! Route('document.upload') !!}" method="POST" enctype="multipart/form-data">
    		@csrf
    		<input type="text" name="docType" value="{!! $type !!}" class="d-none">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Upload Document</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert">
				    <div class="alert-icon"><i class="flaticon-warning"></i></div>
				    <div class="alert-text">Please select that is just downloaded pdf file!</div>
				    <div class="alert-close">
				        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				            <span aria-hidden="true"><i class="ki ki-close"></i></span>
				        </button>
				    </div>
					</div>

	        <div class="col-md-12 d-flex justify-content-center align-item-center">
						<input type="file" name="documentFile" id="documentFile" class="inputfile inputfile-4 " accept=".pdf, .PDF" style="width:0px; height: 0px;">
						<label for="documentFile"><figure><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg></figure> <span id="uploadFileTxt">Choose a PDF file...</span></label>
					</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
	        <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i>&nbsp;UPLOAD</button>
	      </div>
    	</form>
    </div>
  </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.css">
<link rel="stylesheet" href="/css/pages/documents/styles.css">
<link rel="stylesheet" href="/css/pages/documents/pdfannotate.css">
<link rel="stylesheet" href="/css/pages/documents/component.css">
<!-- <link rel="stylesheet" href="/css/pages/documents/normalize.css"> -->
@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="/js/pdfjs/pdf.js"></script>
<script src="/js/pdfjs/pdf.worker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.3.0/fabric.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.2.0/jspdf.umd.min.js"></script>
<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.js"></script>


<script src="/js/pages/apps/documents/arrow.fabric.js"></script>
<script src="/js/pages/apps/documents/pdfannotate.js"></script>
<script src="/js/pages/apps/documents/script.js"></script>
<script type="text/javascript">
	var type = `{!! $type !!}`;
</script>
@endsection


@extends('layout.default')

@section('content')
<div class="body">
	<div class="toolbar px-20">
		<div class="tool">
			<label for="" class="text-dark">Brush size</label>
			<input type="number" class="form-control text-right" value="1" id="brush-size" max="50">
		</div>
	
		<div class="tool">
			<label for="" class="text-dark">Font size</label>
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
				<!-- FDSFDS -->
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
			<button class="tool-button btn btn-outline-warning btn-sm active" onclick="enableSelector(event)"><i class="fa fa-hand-paper-o" title="Free Hand" ></i></button>
		</div>
		<div class="tool">
			<button class="tool-button btn btn-outline-warning btn-sm" onclick="enablePencil(event)"><i class="fa fa-pencil" title="Pencil" ></i></button>
		</div>
		<div class="tool">
			<button class="tool-button btn btn-outline-warning btn-sm" onclick="enableAddText(event)"><i class="fa fa-font" title="Add Text" ></i></button>
		</div>
		<div class="tool">
			<button class="tool-button btn btn-outline-warning btn-sm" onclick="enableAddArrow(event)"><i class="fa fa-long-arrow-right" title="Add Arrow" ></i></button>
		</div>
<!-- 		<div class="tool">
			<button class="tool-button btn btn-outline-warning btn-sm"><i class="fa fa-square-o" title="Add rectangle" onclick="enableRectangle(event)"></i></button>
		</div> -->
		<div class="tool">
			<button class="tool-button btn btn-outline-warning btn-sm" onclick="addImage(event)"><i class="fa fa-picture-o" title="Add an Image" ></i></button>
		</div>
		<div class="tool">
			<button class="btn btn-danger btn-sm" onclick="deleteSelectedObject(event)"><i class="fa fa-trash"></i></button>
		</div>
		<div class="tool">
			<button class="btn btn-danger btn-sm" onclick="clearPage()">Clear Page</button>
		</div>
		<div class="tool">
			<div class="btn-group">
				
					<div type="button" class="btn btn-secondary btn-sm"><img src="" id="selected_sign" height="20"></div>
					<button type="button" class="btn btn-secondary btn-sm addSignBtn" id="addSignBtnTitle"  data-toggle="modal" data-target="#signModal"><i class="fas fa-pencil-alt"></i> Add Signature</button>
				  <button type="button" class="btn btn-secondary btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			        <span class="sr-only"></span>
			    </button>
			    <div class="dropdown-menu " id="sign-dropdown">
						
			      <div class="dropdown-item"><button id="addSignBtn" class=" addSignBtn btn-outline-primary btn btn-sm" data-toggle="modal" data-target="#signModal"><i class="fas fa-pencil-alt"></i> Add Signature</button></div>
			    </div>
			</div>
		</div>

		<div class="tool float-right">
			<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#uploadModal"><i class="fas fa-upload"></i>&nbsp;Upload</button>
		</div>
		<div class="tool">
			<button class="btn btn-primary btn-sm" onclick="savePDF()"><i class="fa fa-save"></i> Save</button>
		</div>
	</div>
	<div  class="d-flex flex-column-fluid body">
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

					@if ($message = Session::get('success'))
				  <div class="alert alert-custom alert-notice alert-light-primary mt-20 fade show" role="alert">
					    <div class="alert-icon"><i class="flaticon-warning"></i></div>
					    <div class="alert-text">{!! $message !!}</div>
					    <div class="alert-close">
					        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
					            <span aria-hidden="true"><i class="ki ki-close"></i></span>
					        </button>
					    </div>
					</div>
					<?php Session::forget('success');?>
					@endif

					<div class="d-flex flex-column justify-content-center align-item-center">
						<div class="mt-20">
							<h1 class="text-center">{{$doc->name}}</h1>
							<p class="font-size-h5 mb-1 text-center">
								<i class="far fa-user-circle"></i>
								From: {{$doc->user->email}} </p>
							<p class="font-size-h5 text-center"><i class="far fa-calendar-alt"></i> Date: {{$doc->created_at}} </p>
						</div>
					</div>

					<div id="pdf-container" class="d-flex flex-column justify-content-center align-item-center"></div>
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
    	<form action="{!! Route('document.guidance.save') !!}" method="POST" enctype="multipart/form-data">
    		@csrf
    		<input type="text" name="id" value="{{$doc->id}}">
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


<div class="modal fade" id="signModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Create New Signature</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
					<div class="example mb-10">
						<div class="example-preview p-3">
							<ul class="nav nav-tabs" id="myTab1" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="home-tab-1" data-toggle="tab" href="#home-1">
										<span class="nav-icon">
											<i class="fas fa-pencil-alt"></i>
										</span>
										<span class="nav-text">Draw</span>
									</a>
								</li>
								<!-- <li class="nav-item">
									<a class="nav-link" id="profile-tab-1" data-toggle="tab" href="#profile-1" aria-controls="profile">
										<span class="nav-icon">
											<i class="fas fa-font"></i>
										</span>
										<span class="nav-text">Type</span>
									</a>
								</li> -->
							</ul>
							<div class="tab-content mt-5" id="myTabContent1">
								<div class="tab-pane fade show active" id="home-1" role="tabpanel" aria-labelledby="home-tab-1">

	        				<canvas id="drawCanvans" width="400"	></canvas>
									
									<div class="form-group row">
										<div class="col-6 col-form-label">
											<div class="radio-inline">
												<label class="radio radio-accent radio-dark">
												<input type="radio" name="radios18" checked="checked" onchange="changeColor('#0d0d0d');" />
												<span></span></label>
												<label class="radio radio-accent radio-primary">
												<input type="radio" name="radios18"  onchange="changeColor('#2f53b0');"/>
												<span></span></label>
												<label class="radio radio-accent radio-danger">
												<input type="radio" name="radios18" onchange="changeColor('#bf0a0a');"/>
												<span></span></label>
											</div>
										</div>
										<div class="col-6">
											<button class="btn btn-secondary btn-sm float-right ml-2" id="clear"><i class="fas fa-trash-alt"></i> Clear</button>
											<button class="btn btn-secondary btn-sm float-right" id="undo">
												<span class="svg-icon svg-icon-secondary svg-icon-sm"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Communication\Reply.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											        <rect x="0" y="0" width="24" height="24"/>
											        <path d="M21.4451171,17.7910156 C21.4451171,16.9707031 21.6208984,13.7333984 19.0671874,11.1650391 C17.3484374,9.43652344 14.7761718,9.13671875 11.6999999,9 L11.6999999,4.69307548 C11.6999999,4.27886191 11.3642135,3.94307548 10.9499999,3.94307548 C10.7636897,3.94307548 10.584049,4.01242035 10.4460626,4.13760526 L3.30599678,10.6152626 C2.99921905,10.8935795 2.976147,11.3678924 3.2544639,11.6746702 C3.26907199,11.6907721 3.28437331,11.7062312 3.30032452,11.7210037 L10.4403903,18.333467 C10.7442966,18.6149166 11.2188212,18.596712 11.5002708,18.2928057 C11.628669,18.1541628 11.6999999,17.9721616 11.6999999,17.7831961 L11.6999999,13.5 C13.6531249,13.5537109 15.0443703,13.6779456 16.3083984,14.0800781 C18.1284272,14.6590944 19.5349747,16.3018455 20.5280411,19.0083314 L20.5280247,19.0083374 C20.6363903,19.3036749 20.9175496,19.5 21.2321404,19.5 L21.4499999,19.5 C21.4499999,19.0068359 21.4451171,18.2255859 21.4451171,17.7910156 Z" fill="#000000" fill-rule="nonzero"/>
											    </g>
											</svg><!--end::Svg Icon--></span>
 										Undo</button>

										</div>
									</div>
								</div>
								<!-- <div class="tab-pane fade" id="profile-1" role="tabpanel" aria-labelledby="profile-tab-1">
									
								</div> -->
							</div>
						</div>
					</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
	        <button type="submit" class="btn btn-primary" id="saveSignBtn"><i class="fas fa-save"></i>&nbsp;CREATE</button>
	      </div>
    	</form>
    </div>
  </div>
</div>

@include('layout.partials.extras._progressModal')
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
<script type="text/javascript">
	var filepath = `{!! $doc->file !!}`;
	
</script>
<script src="/js/pdfjs/pdf.js"></script>
<script src="/js/pdfjs/pdf.worker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.3.0/fabric.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.2.0/jspdf.umd.min.js"></script>
<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.js"></script>


<script src="/js/pages/apps/documents/arrow.fabric.js"></script>
<script src="/js/pages/apps/documents/pdfannotate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="/js/pages/apps/documents/guidance/guidanceSign.js"></script>
<script src="/js/pages/apps/documents/sign.js"></script>

@endsection


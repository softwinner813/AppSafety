@extends('layout.default')

@section('content')
<div class="body">
	<div  class="d-flex flex-column-fluid body">
	    <div class="{{ Metronic::printClasses('content-container', false) }}">
			<div class="row">
				<div class="col-md-12">
					<div id="template_board" class="d-flex flex-column justify-content-center align-item-center" style="height: 100%;" >
						<h1 class="mb-5 mt-20 text-center">
							{{$docname}} Templates 
							
						</h1>
						<div class="row mx-1 px-20" style="" >
							@foreach($templates as $key => $template)
							<div class="col-md-3 mb-5">
								<div class="card card-custom overlay">
								    <div class="card-body p-0 bg-secondary">
								        <div class="overlay-wrapper text-danger py-5 px-2 d-flex flex-column align-items-center justify-content-center">
								        	<i class="fas fa-file-pdf text-danger" style="font-size: 100px;"></i>
								        	<h6 class="text-dark ">{{$template}}</h6>
								        </div>
								        <div class="overlay-layer">
								            <a href="#" class="btn font-weight-bold btn-primary btn-shadow" onclick="selectTemplate(`{!! $template !!}`);" ><i class="fas fa-upload"></i> CHOOSE</a>
								        </div>
								    </div>
								</div>
							</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<form action="{!! Route('document.incident.edit') !!}" id="docForm" method="POST" style="display: none;">
	@csrf
	<input type="text" name="docName" id="docName">
</form>
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
function selectTemplate(docName) {
	console.log(docName);
	$('#docName').val(docName);
	$('#docForm').submit();
}
</script>
@endsection


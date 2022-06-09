@extends('layout.default')

@section('content')
<div class="body">
	<div  class="d-flex flex-column-fluid body">
	    <div class="{{ Metronic::printClasses('content-container', false) }}">
			<div class="row">
				<div class="col-md-12">
					@if ($message = Session::get('error'))
				  <div class="alert alert-custom alert-notice alert-light-danger mt-10 fade show" role="alert">
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

					
					<div id="template_board" class="d-flex flex-column justify-content-center align-item-center">
						<div class="">
							<h1 class="mb-5 mt-20 text-center">
								{{$docname}} Templates 
								
							</h1>
							<div class="row">
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
					

					<div class="row d-flex justify-content-between  pt-15">
						<button class="btn btn-icon btn-primary btn-circle mr-2 float-left d-none" id="showTmpBtn" onclick="showTemplates();">
							<span class="svg-icon"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Layout\Layout-4-blocks.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
							    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
							        <rect x="0" y="0" width="24" height="24"/>
							        <rect fill="#000000" x="4" y="4" width="7" height="7" rx="1.5"/>
							        <path d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z" fill="#000000" opacity="0.3"/>
							    </g>
							</svg><!--end::Svg Icon--></span>
						</button>

						<button class="btn btn-primary float-right d-none" id="sendBtn" data-toggle="modal" data-target="#sendEmailModal" >
								<span class="svg-icon"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Communication\Mail-opened.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
								    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								        <rect x="0" y="0" width="24" height="24"/>
								        <path d="M6,2 L18,2 C18.5522847,2 19,2.44771525 19,3 L19,12 C19,12.5522847 18.5522847,13 18,13 L6,13 C5.44771525,13 5,12.5522847 5,12 L5,3 C5,2.44771525 5.44771525,2 6,2 Z M7.5,5 C7.22385763,5 7,5.22385763 7,5.5 C7,5.77614237 7.22385763,6 7.5,6 L13.5,6 C13.7761424,6 14,5.77614237 14,5.5 C14,5.22385763 13.7761424,5 13.5,5 L7.5,5 Z M7.5,7 C7.22385763,7 7,7.22385763 7,7.5 C7,7.77614237 7.22385763,8 7.5,8 L10.5,8 C10.7761424,8 11,7.77614237 11,7.5 C11,7.22385763 10.7761424,7 10.5,7 L7.5,7 Z" fill="#000000" opacity="0.3"/>
								        <path d="M3.79274528,6.57253826 L12,12.5 L20.2072547,6.57253826 C20.4311176,6.4108595 20.7436609,6.46126971 20.9053396,6.68513259 C20.9668779,6.77033951 21,6.87277228 21,6.97787787 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6.97787787 C3,6.70173549 3.22385763,6.47787787 3.5,6.47787787 C3.60510559,6.47787787 3.70753836,6.51099993 3.79274528,6.57253826 Z" fill="#000000"/>
								    </g>
								</svg><!--end::Svg Icon--></span>
							  SEND
						</button>
					</div>
					<div class="col-md-12">
						<div id="pdf-container" class="d-flex flex-column justify-content-center align-item-center"></div>
						
					</div>
						
				</div>
			</div>
	    </div>
	</div>
</div>

@include('layout.partials.extras._progressModal')

<!-- Modal-->
<div class="modal fade" id="sendEmailModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <form class="form" id="kt_add_email_form"  method="POST" action="{{ route('document.induction.save') }}">
                    @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Document Share Dialog</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
              	  <label for="email">Document :</label>
                  <div class="form-group">
                      <input readonly  class="form-control h-auto text-dark placeholder-dark bg-dark-o-40 rounded-pill border-0 py-4 px-8 mb-5 "  placeholder="" id="filename" type="filename" name="filename" required autocomplete="filename" autofocus />
                  </div>
                  <label for="email">Email To :</label>
                  <div class="form-group">
                      <input class="form-control h-auto text-dark placeholder-dark bg-dark-o-20 rounded-pill border-0 py-4 px-8 mb-5 "  placeholder="Email" id="email" type="email" name="email"  required autocomplete="email" autofocus />
                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger font-weight-bold" data-dismiss="modal">CLOSE</button>
                    <button type="submit" class="btn btn-primary font-weight-bold" id="sendEm_btn"><i class="fab fa-telegram-plane"></i>&nbsp;SEND EMAIL</button>
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
<script src="/js/pages/apps/documents/induction/induction.js"></script>
<script type="text/javascript">
	// var type = `{!! $type !!}`;
</script>
@endsection


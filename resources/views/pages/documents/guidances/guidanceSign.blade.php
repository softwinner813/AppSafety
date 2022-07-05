@extends('layout.default')

@section('content')
		@if ($message = Session::get('success'))
		<div class="container" >
	   	<div class="alert alert-custom alert-notice alert-light-success mt-10 fade show" role="alert">
		    <div class="alert-icon"><i class="flaticon-warning"></i></div>
			    <div class="alert-text">{!! $message !!}
					<a href="" style="float: right;cursor: pointer;" class="text-decoration: underline text-danger"><i class="fa fa-long-arrow-left text-danger"></i>&nbsp;Back</a>
			    </div>

			    <div class="alert-close">
			        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			            <span aria-hidden="true"><i class="ki ki-close"></i></span>
			        </button>
			    </div>
			</div>
		</div>
		<?php Session::forget('success');?>
		@else
			@if ($message = Session::get('error'))
			<div class="container" >
		   	<div class="alert alert-custom alert-notice alert-light-danger mt-10 fade show" role="alert">
			    <div class="alert-icon"><i class="flaticon-warning"></i></div>
				    <div class="alert-text">{!! $message !!}
						<a href="" style="float: right;cursor: pointer;" class="text-decoration: underline text-danger"><i class="fa fa-long-arrow-left text-danger"></i>&nbsp;Back</a>
				    </div>

				    <div class="alert-close">
				        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				            <span aria-hidden="true"><i class="ki ki-close"></i></span>
				        </button>
				    </div>
				</div>
			</div>
			<?php Session::forget('error');?>
			
			@endif
	    <div class="container-fluid p-0"  style='background-color: #eef0f8; font-family: "Helvetica Neue", "Helvetica", "Arial", "sans-serif"; height: 100% ; '>
			

				<div class="row" style="height: 100%;">
					
					@include('layout.partials.extras._signToolbar')

					<div class="col-md-10 col-xs-9 bg-green " id="pdf-wrapper" style="background-color: #c0c0c0; position: relative; min-height:100% ; height: calc(100% + 20px); overflow-y: auto;">
						<div>
							<!-- <span style="width:10px; height:10px; background-color: red;z-index: 10000000; border-radius: 100%; position: absolute; top: 106px; left: 575.44px;"></span> -->
						</div>
						<div id="nextBtnPanel" style="display: none;">
							<div style=" height: 70px; width: 100%; background-color: #005cb9; position: fixed; bottom: 0px;left: 0px; z-index: 10000000;" class="d-flex justify-content-between align-items-center py-2 px-5">
								<label class="font-size-h5 text-white">Once you have added your signature please press "FINISH" to complete the form</label>
								<button class="btn btn-warning btn-sm px-10 next-btn">FINISH</button>
							</div>
						</div>
						<div id="finishBtnPanel" style="display: none;">
							<div style=" height: 70px; width: 100%; background-color: #005cb9; position: fixed; bottom: 0px;left: 0px; z-index: 10000000;" class="d-flex justify-content-between align-items-center py-2 px-5">
								<label class="font-size-h5 text-white">Once you have added your signature please press "FINISH" to complete the form</label>
								<button class="btn btn-warning btn-sm px-10 finish-btn" data-toggle="modal" data-target="#sendEmailModal">FINISH</button>
							</div>
						</div>
						<div class="hidden-xs ">
			        <button type="button" style="display: none; position: fixed; top: 90px ; right: 20px;" track="autoNavigate" id="navigate-btn" class="btn btn-caret-right " data-action="autoNavigate" data-qa="auto-nav-caret" aria-hidden="true" tabindex="-1" style="opacity: 1; top: 218.547px;">
			          <span>START</span>
			        </button>
			      </div>
						<div id="pdf-container" style="width: 100%;padding-top: 10px; background-color: #c0c0c0;" class="d-flex flex-column justify-content-center align-item-center" ></div>
					</div>
						
				</div>
	    </div>


	    @include('layout.partials.extras._progressModal')
			@include('layout.partials.extras._signPanelModal')

			<!-- Modal-->
			<div class="modal fade" id="sendEmailModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
			    <div class="modal-dialog " role="document">
			        <div class="modal-content">
			            <form class="form" id="kt_add_email_form"  method="POST" action="{{ route('document.guidance.save') }}">
			                @csrf
			                <div class="modal-header">
			                    <h5 class="modal-title" id="exampleModalLabel">Document Share Dialog</h5>
			                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			                        <i aria-hidden="true" class="ki ki-close"></i>
			                    </button>
			                </div>
			                <div class="modal-body">
			                  <label for="email">Email To<span class="text-danger"></span></label>
			                  <div class="form-group">
			                      <input class="form-control h-auto text-dark placeholder-dark bg-dark-o-20  border-0 py-4 px-8 mb-5 " readonly value="{{$docHistory->document->user->email}}"  placeholder="Email" id="email" type="email" name="email"  required autocomplete="email" autofocus />
			                  </div>
			                  <label for="subject">Email Subject<span class="text-danger">*</span></label>
			                  <div class="form-group">
			                      <input class="form-control h-auto text-dark placeholder-dark bg-dark-o-20  border-0 py-4 px-8 mb-5 "  placeholder="Email Subject" id="subject" type="subject" name="subject" value="Completed Sign: {{$docHistory->document->name}}"  required autocomplete="subject" autofocus />
			                  </div>
			                  <label for="comment">Email Message<span class="text-danger">*</span></label>
			                  <div class="form-group">
			                      <textarea class="form-control h-auto text-dark placeholder-dark bg-dark-o-20  border-0 py-4 px-8 mb-5 "  placeholder="Message here..." id="comment" type="comment" name="comment" rows="4" required autocomplete="comment" autofocus ></textarea>
			                  </div>
			                  <textarea name="fills" id="fills" style="display: none;"></textarea>
			                  <input type="text" name="filepath" id="filepath" style="display: none;">
			                  <input type="text" name="id" value="{{$docHistory->id}}" style="display: none;">
			                  <input type="text" name="filename" id="filename" value="" style="display: none;">

			                </div>
			                <div class="modal-footer">
			                    <button type="button" class="btn btn-danger font-weight-bold" data-dismiss="modal">CLOSE</button>
			                    <button type="submit" class="btn btn-primary font-weight-bold" id="sendEm_btn"><i class="fab fa-telegram-plane"></i>&nbsp;SEND</button>
			                </div>
			            </form>
			        </div>
			    </div>
			</div>

		@endif






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
<script type="text/javascript">
	var filepath = `{!! $docHistory ? $docHistory->document->file : '' !!}`;
	var jsonObjects = `{!! $docHistory ? $docHistory->fill_forms : '[]' !!}`;
</script>
<script src="/js/pdfjs/pdf.js"></script>
<script src="/js/pdfjs/pdf.worker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.3.0/fabric.min.js"></script>
<script src="https://rawgit.com/bramstein/fontfaceobserver/master/fontfaceobserver.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.2.0/jspdf.umd.min.js"></script>
<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.js"></script>


<script src="/js/pages/apps/documents/arrow.fabric.js"></script>
<script src="/js/pages/apps/documents/pdfannotate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="/js/pages/apps/documents/guidance/guidanceSign.js"></script>
<script src="/js/pages/apps/documents/sign.js"></script>

@endsection


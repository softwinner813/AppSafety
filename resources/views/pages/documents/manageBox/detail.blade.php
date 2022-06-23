@extends('layout.default')

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container" >
        <!--begin::Profile Personal Information-->
        <div class="d-flex flex-row" style="height: 100%;">
            <!--begin::Content-->
            <div class="flex-row-fluid" style="height: 100%;">
                <!--begin::Card-->
                <!--begin::Card-->
                <div class="card card-custom" style="height: 100%;">

                	<!--  ERROR & SUCCESS MESSAGE -->
                	@if ($message = Session::get('success'))
					<div class="container" >
				   	<div class="alert alert-custom alert-notice alert-light-success mt-10 fade show" role="alert">
					    <div class="alert-icon"><i class="flaticon-warning"></i></div>
						    <div class="alert-text">{!! $message !!}
						    </div>

						    <div class="alert-close">
						        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
						            <span aria-hidden="true"><i class="ki ki-close"></i></span>
						        </button>
						    </div>
						</div>
					</div>
					<?php Session::forget('success');?>
					@endif

					@if ($message = Session::get('error'))
					<div class="container" >
				   	<div class="alert alert-custom alert-notice alert-light-danger mt-10 fade show" role="alert">
					    <div class="alert-icon"><i class="flaticon-warning"></i></div>
						    <div class="alert-text">{!! $message !!}
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


                    <div class="card-header flex-wrap border-0 pt-6 pb-0">
                        <div class="card-title">
                            <h1 class="card-label text-dark h3"> {{$doc->subject}} Please Sign:FSDFS
                            	<span class="d-block text-dark-50 pt-5 font-size-sm">From: <label >{{$doc->from}}<label></span>
                            	<span class="d-block text-dark-50 font-size-sm">Received on {{ date('m/d/Y   h:i:s', strtotime($doc->updated_at))}}</span>
                            	<span class="d-block text-dark-50 pt-2 font-size-sm">Created on {{ date('m/d/Y  h:i:s', strtotime($doc->document->created_at))}}</span>
                        	</h1>
                        </div>
                        
                    </div>

                    <div class="card-body">
                        <div class="ds-center d-flex justify-content-between align-items-center">
                        	@if($doc->document->isCompleted)
                        	<span><i class="fa fa-check text-success"></i> Completed</span>
                        	@elseif($doc->to == Auth::user()->email)
                        	<span class="label label-primary label-inline font-weight-lighter text-white text-center"><i class="fas fa-marker text-white font-size-sm"></i>&nbsp;Need to sign</span>
                        	@else
                        	<span class="label label-danger label-inline font-weight-lighter text-white text-center"><i class="fas fa-marker text-white font-size-sm"></i>&nbsp;Waiting signing...</span>
                        	@endif


                        	<div class="d-flex justify-content-center ">
	                        	@if($doc->document->isCompleted)
                        		<a class="btn btn-secondary btn-sm mr-2" href="{{Route('document.box.download', [$doc->id])}}"><i class="fas fa-download icon-sm"></i>&nbsp;DOWNLOAD</a>
                        		@elseif($doc->to == Auth::user()->email)
                        		<a class="btn btn-primary btn-sm mr-2" href="{{Route('document.box.sign', [$doc->id])}}"><i class="fas fa-pencil-alt icon-sm"></i>&nbsp;SIGN</a>
	                        	@else
	                        	<a class="btn btn-primary btn-sm mr-2" href="{{Route('document.box.resend', [$doc->id])}}"><i class="far fa-paper-plane icon-sm"></i>&nbsp;RESEND</a>
	                        	@endif
								<a class="btn btn-danger btn-sm" href="{{Route('document.box.moveDel', [$doc->id])}}"><i class="fa fa-trash icon-sm"></i> &nbsp;DELETE</a>
                        	</div>
                        </div>

						<blockquote class="blockquote mt-4 mb-10 bg-gray-100 p-5 success-bar">
						    <p class="mb-0 h6"><i class="text-dark-75 far fa-comment-dots"></i> Message</p>
						    <footer class="blockquote-footer pt-3">
						        <cite title="Source Title">{{$doc->message}}fdsfds</cite>
						    </footer>
						</blockquote>

						@if($doc->document->user_id == Auth::user()->id)
						<div class="col-md-12 p-0">
							<hr>
							<h5>
								<i class="far fa-file-pdf text-dark-50"></i> &nbsp;{{$doc->document->name}}
								<span class="text-dark-50 pt-2 font-size-sm">Created on {{ date('m/d/Y  h:i:s', strtotime($doc->document->created_at))}}</span>
							</h5>
							@foreach($doc->document->history as $his)
							<div class="col-md-12 mt-5">
								<div class="ml-5  py-2 pl-5 primary-bar bg-primary-o-50 d-flex ">
									<div class="col-md-6">
										<h6 class="mt-2">{{$his->subject}}</h6>
										<span class="d-block text-secondary pt-2 font-size-sm">From: <a href="#">{{ $his->from}}</a></span>
										<span class="d-block text-secondary pt-2 font-size-sm">To: <a href="#">{{ $his->to}}</a></span>
										<span class="d-block text-secondary pt-3 font-size-sm">Sent on {{ date('m/d/Y  h:i:s', strtotime($his->created_at))}}</span>
									</div>

									<div class="col-md-6">
										<h6 class="mt-2"><i class="text-dark-75 far fa-comment-dots"></i> Message</h6>
										<p>
											@if(is_null($his->message))
											No message have been entered
											@else
											{{$his->message}}
											@endif
										</p>
									</div>
								</div>
							</div>
							@endforeach
						</div>
						@endif
                   	
                    </div>
                </div>
                <!--end::Card-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Profile Personal Information-->
    </div>
</div>

@endsection

{{-- Style Section --}}
@section('styles')

<link rel="stylesheet" type="text/css" href="/css/pages/documents/box.css">
@endsection

{{-- Scripts Section --}}
@section('scripts')

@endsection
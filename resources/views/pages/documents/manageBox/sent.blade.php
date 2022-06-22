@extends('layout.default')

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container" >
        <!--begin::Profile Personal Information-->
        <div class="d-flex flex-row" style="height: 100%;">
            @include('pages.documents.manageBox._aside')

            <!--begin::Content-->
            <div class="flex-row-fluid" style="height: 100%;">
                <!--begin::Card-->
                <!--begin::Card-->
                <div class="card card-custom" style="border-top-left-radius: 0px;border-bottom-left-radius: 0px;height: 100%;">

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
                            <h3 class="card-label">SENT
                            <span class="d-block text-muted pt-2 font-size-sm">Sent Documents</span></h3>
                        </div>
                        <div class="card-toolbar">
                            <div class="col-lg-12 col-xl-12">
                            	<form action="{!! Route('document.box.sent.search', [$type]) !!}" method="POST">
                            		@csrf
	                                <div class="row align-items-center justify-content-between">
	                                    <div class="col-md-8 my-2 my-md-0">
	                                        <div class="input-icon">
	                                            <input type="text" class="form-control" name="q" placeholder="Search..." id="kt_datatable_search_query" />
	                                            <span>
	                                                <i class="flaticon2-search-1 text-muted"></i>
	                                            </span>
	                                        </div>
	                                    </div>
	                                    <button type="submit" class="btn btn-light-primary px-6 font-weight-bold">Search</button>
	                                </div>
                            	</form>
                            </div>
                            <!--end::Button-->
                        </div>
                    </div>



                    <div class="card-body pl-0">
                       	
               			{{ $documents->links('vendor.pagination.bootstrap-4')}}
                        <table class="col-md-12 table  box-table" id="" style="overflow-y: auto;">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Last Change</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            	@forelse($documents as $key => $doc)
	                            	@if($doc->document->type == $type)
		                            <tr>
		                            	<td>
		                            		<div class="left-border">
		                            			<div class="p-3">
				                            		<a href="{!! Route('document.box.detail', [$doc->id]) !!}" class="font-size-h6">{{$doc->subject}}SSSS</a><br>
				                            		<span>From: {{$doc->from}}</span>
		                            			</div>
		                            		</div>
		                            	</td>
		                               	<td>
		                               		<div class="p-3">
		                               			@if($doc->document->isCompleted)
		                               			<span class="label label-primary label-inline font-weight-lighter text-white text-center"><i class="fas fa-check text-white font-size-sm"></i>&nbsp;Completed</span>
		                               			@else
		                               			<span class="label label-danger label-inline font-weight-lighter text-white text-center"><i class="fas fa-pencil-alt text-white font-size-sm"></i>&nbsp; Need to Sign</span>
		                               			@endif
		                               		</div>
		                               	</td>
		                               	<td>
		                               		<div class="p-3">
		                               			<strong>{{ date('m/d/Y', strtotime($doc->created_at)) }}</strong><br>
		                               			<span>{{ date('h:i:s', strtotime($doc->created_at)) }}</span>
		                               		</div>
		                               	</td>
		                               	<td>
		                               		<div class="p-3">
		                               			@if($doc->document->isCompleted)
		                               			<div class="btn-group">
												    <a href="{{Route('document.box.preview', [$doc->id])}}" class="btn btn-secondary btn-sm">PREVIEW</a>
												    <button type="button" class="btn btn-secondary btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												        <span class="sr-only">Toggle Dropdown</span>
												    </button>
												    <div class="dropdown-menu">
												    	<a class="dropdown-item" href="{{Route('document.box.preview', [$doc->id])}}"><i class="fas fa-link font-size-sm text-primary"></i>&nbsp;&nbsp; PREVIEW</a>
														<a class="dropdown-item" href="{{Route('document.box.download', [$doc->id])}}"><i class="fa fa-download font-size-sm text-primary"></i> &nbsp;&nbsp; DOWNLOAD</a>
												    </div>
												</div>
												@else
												<div class="btn-group">
												    <a href="{{Route('document.box.sign', [$doc->id])}}" class="btn btn-primary btn-sm">SIGN</a>
												    <button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												        <span class="sr-only">Toggle Dropdown</span>
												    </button>
												    <div class="dropdown-menu" style="">
														<a class="dropdown-item" href="{{Route('document.box.sign', [$doc->id])}}"><i class="fas fa-pencil-alt font-size-sm text-danger"></i>&nbsp;&nbsp; SIGN</a>
														<a class="dropdown-item" href="{{Route('document.box.moveDel', [$doc->id])}}"><i class="fa fa-trash font-size-sm text-danger"></i> &nbsp;&nbsp; DELETE</a>
													</div>
												</div>
												@endif
		                               		</div>
		                               	</td>	
	                            	</tr>
	                            	@endif
                               	@empty 
                               	<tr class="text-center text-dark"><td colspan="3">No available data<td></tr>
                               	@endforelse  
                            </tbody>
                        </table>
                        <!--end: Datatable-->
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
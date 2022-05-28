{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

	{{-- Dashboard 1 --}}

	<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
		<!--begin::Entry-->
		<div class="d-flex flex-column-fluid">
			<!--begin::Container-->
			<div class="container">
				<!--begin::Card-->
				<div class="card card-custom">
					<!--begin::Header-->
					<div class="card-header flex-wrap border-0 pt-6 pb-0">
						<div class="card-title">
							<h3 class="card-label">User Information
						</div>
						
					</div>
					<!--end::Header-->
					<!--begin::Body-->
					<div class="card-body">
						<table class="table">
								<thead>
									<th style="text-align: center;">AVATAR</th>
									<th style="text-align: center;">NAME</th>
									<th style="text-align: center;">EMAIL</th>
									<th style="text-align: center;">GENDER</th>
									<th style="text-align: center;">BIRTHDAY</th>
									<th style="text-align: center;">CREATED</th>
									<th style="text-align: center;">UPDATED</th>
									<th style="text-align: center;">ACTION</th>
								</thead>
								<tbody>
									<tr style="text-align: center;">
										<td>
											@if($user->avatar != null)
												<div class="symbol symbol-40 symbol-sm flex-shrink-0">
													<img class="" src="{{$user->avatar}}" alt="photo">
												</div>
											@else
												<div class="symbol symbol-40 symbol-sm flex-shrink-0">
													<img class="" src="/media/users/default.jpg" alt="photo">
												</div>
											@endif
											
										</td>
										<td>{{$user->name}}</td>
										<td>
											{{$user->email}}
										</td>
										<td>
											@if($user->gender == 'Male')
												<span class="label label-lg font-weight-bold label-light-success label-inline">
													Male
												</span>
											@else
												<span class="label label-lg font-weight-bold label-light-danger label-inline">
													Female
												</span>
											@endif
											
										</td>
										<td>
											{{date('m/d/Y',strtotime($user->birthday))}}
										</td>
										<td>
											{{date('m/d/Y H:i',strtotime($user->created_at))}}
										</td>
										<td>
											{{date('m/d/Y H:i',strtotime($user->updated_at))}}
										</td>
										<td>
											<a href="/users/individual_detail/{{$user->id}}" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon" title="Detail">
						                     	<span class="svg-icon svg-icon-md">
													<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="svg-icon">
														<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
															<rect x="0" y="0" width="24" height="24"/>
															<path d="M7,3 L17,3 C19.209139,3 21,4.790861 21,7 C21,9.209139 19.209139,11 17,11 L7,11 C4.790861,11 3,9.209139 3,7 C3,4.790861 4.790861,3 7,3 Z M7,9 C8.1045695,9 9,8.1045695 9,7 C9,5.8954305 8.1045695,5 7,5 C5.8954305,5 5,5.8954305 5,7 C5,8.1045695 5.8954305,9 7,9 Z" fill="#000000"/>
															<path d="M7,13 L17,13 C19.209139,13 21,14.790861 21,17 C21,19.209139 19.209139,21 17,21 L7,21 C4.790861,21 3,19.209139 3,17 C3,14.790861 4.790861,13 7,13 Z M17,19 C18.1045695,19 19,18.1045695 19,17 C19,15.8954305 18.1045695,15 17,15 C15.8954305,15 15,15.8954305 15,17 C15,18.1045695 15.8954305,19 17,19 Z" fill="#000000" opacity="0.3"/>
														</g>
													</svg>
												</span>
					                        </a>
										</td>
									</tr>
								</tbody>
							</table>
					</div>
					<!--end::Body-->
				</div>
				<!--end::Card-->
			</div>
			<!--end::Container-->
		</div>
		<!--end::Entry-->
	</div>
	<!--end::Content-->

@endsection

{{-- Scripts Section --}}
@section('scripts')

	<!-- <script src="{{ asset('/js/pages/users/users.js')}}"></script> -->
	<!--end::Page Scripts-->
@endsection

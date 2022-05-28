<div class="flex-row-auto offcanvas-mobile w-250px w-xxl-350px" id="kt_profile_aside">
	<!--begin::Profile Card-->
	<div class="card card-custom card-stretch">
		<!--begin::Body-->
		<div class="card-body pt-4">
			<!--begin::User-->
			<div class="d-flex align-items-center">
				<div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
					@if($user->avatar != null && $user->avatar != '')
					<div class="symbol-label" style="background-image:url('{{$user->avatar}}')"></div>
					@else 
					<div class="symbol-label" style="background-image:url('/media/users/default.jpg')"></div>
					@endif
					<i class="symbol-badge bg-success"></i>
				</div>
				<div>
					<a href="#" class="font-weight-bolder font-size-h5 text-dark-75 text-hover-primary">{{$user->name}}</a>
				</div>
			</div>
			<!--end::User-->
			<!--begin::Contact-->
			<div class="py-9">
				<div class="d-flex align-items-center justify-content-between mb-2">
					
					<span class="font-weight-bold mr-2">
					<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Communication\Mail-attachment.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
					    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
					        <rect x="0" y="0" width="24" height="24"/>
					        <path d="M14.8571499,13 C14.9499122,12.7223297 15,12.4263059 15,12.1190476 L15,6.88095238 C15,5.28984632 13.6568542,4 12,4 L11.7272727,4 C10.2210416,4 9,5.17258756 9,6.61904762 L10.0909091,6.61904762 C10.0909091,5.75117158 10.823534,5.04761905 11.7272727,5.04761905 L12,5.04761905 C13.0543618,5.04761905 13.9090909,5.86843034 13.9090909,6.88095238 L13.9090909,12.1190476 C13.9090909,12.4383379 13.8240964,12.7385644 13.6746497,13 L10.3253503,13 C10.1759036,12.7385644 10.0909091,12.4383379 10.0909091,12.1190476 L10.0909091,9.5 C10.0909091,9.06606198 10.4572216,8.71428571 10.9090909,8.71428571 C11.3609602,8.71428571 11.7272727,9.06606198 11.7272727,9.5 L11.7272727,11.3333333 L12.8181818,11.3333333 L12.8181818,9.5 C12.8181818,8.48747796 11.9634527,7.66666667 10.9090909,7.66666667 C9.85472911,7.66666667 9,8.48747796 9,9.5 L9,12.1190476 C9,12.4263059 9.0500878,12.7223297 9.14285008,13 L6,13 C5.44771525,13 5,12.5522847 5,12 L5,3 C5,2.44771525 5.44771525,2 6,2 L18,2 C18.5522847,2 19,2.44771525 19,3 L19,12 C19,12.5522847 18.5522847,13 18,13 L14.8571499,13 Z" fill="#000000" opacity="0.3"/>
					        <path d="M9,10.3333333 L9,12.1190476 C9,13.7101537 10.3431458,15 12,15 C13.6568542,15 15,13.7101537 15,12.1190476 L15,10.3333333 L20.2072547,6.57253826 C20.4311176,6.4108595 20.7436609,6.46126971 20.9053396,6.68513259 C20.9668779,6.77033951 21,6.87277228 21,6.97787787 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6.97787787 C3,6.70173549 3.22385763,6.47787787 3.5,6.47787787 C3.60510559,6.47787787 3.70753836,6.51099993 3.79274528,6.57253826 L9,10.3333333 Z M10.0909091,11.1212121 L12,12.5 L13.9090909,11.1212121 L13.9090909,12.1190476 C13.9090909,13.1315697 13.0543618,13.952381 12,13.952381 C10.9456382,13.952381 10.0909091,13.1315697 10.0909091,12.1190476 L10.0909091,11.1212121 Z" fill="#000000"/>
					    </g>
					</svg><!--end::Svg Icon--></span> Email:</span>
					<a href="#" class="text-muted text-hover-primary">{{$user->email}}</a>
				</div>
				<div class="d-flex align-items-center justify-content-between mb-2">
					<span class="font-weight-bold mr-2">

						<i class="fas fab fa-birthday-cake icon-xl text-primary"></i> &nbsp;Birthday:</span>
					<span class="text-muted">{{date('m/d/Y',strtotime($user->birthday))}}</span>
				</div>
				<div class="d-flex align-items-center justify-content-between">
					<span class="font-weight-bold mr-2">
					<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\General\Bookmark.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
					    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
					        <rect x="0" y="0" width="24" height="24"/>
					        <path d="M8,4 L16,4 C17.1045695,4 18,4.8954305 18,6 L18,17.726765 C18,18.2790497 17.5522847,18.726765 17,18.726765 C16.7498083,18.726765 16.5087052,18.6329798 16.3242754,18.4639191 L12.6757246,15.1194142 C12.2934034,14.7689531 11.7065966,14.7689531 11.3242754,15.1194142 L7.67572463,18.4639191 C7.26860564,18.8371115 6.63603827,18.8096086 6.26284586,18.4024896 C6.09378519,18.2180598 6,17.9769566 6,17.726765 L6,6 C6,4.8954305 6.8954305,4 8,4 Z" fill="#000000"/>
					    </g>
					</svg><!--end::Svg Icon--></span>
					Joined at:</span>
					<span class="text-muted">{{date('m/d/Y',strtotime($user->created_at))}}</span>
				</div>
			</div>
			<!--end::Contact-->
		</div>
		<!--end::Body-->
	</div>
	<!--end::Profile Card-->
</div>
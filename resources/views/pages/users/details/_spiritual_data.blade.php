<div class="tab-pane px-7" id="kt_user_edit_tab_4" role="tabpanel">
	<div class="card-body">
		<div class="row">
			@if(!isset($spirituals))
			<p><em>There is no data</em></p>
			@else			
			<div class="col-xl-12">
				<h2>Spiriual Health</h2>
				<br>

				<!-- Mood -->
				<div class="d-flex align-items-center flex-wrap mb-10">
					<!--begin::Symbol-->
					<div class="symbol symbol-50 symbol-light mr-5">
						<i class="icon-xl far fa-smile text-primary"></i>
					</div>
					<!--end::Symbol-->
					<!--begin::Text-->
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">Mood: </a>
					</div>
					<!--end::Text-->
					<div class="symbol symbol-light mr-5">
						<span class="symbol-label">
							<img src="/media/emoji/{{$spirituals->mood}}.jpg" class="h-100 align-self-center" alt="">
						</span>
					</div>
				</div>

				<!-- Frustrations -->
				<div class="d-flex align-items-center flex-wrap mb-10">
					<!--begin::Symbol-->
					<div class="symbol symbol-50 symbol-light mr-5">
						<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Home\Air-ballon.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
						    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						        <rect x="0" y="0" width="24" height="24"/>
						        <path d="M10.1573188,15.7101991 C10.7319317,15.871464 11.3373672,15.9576401 11.9626774,15.9576401 C12.5879876,15.9576401 13.1934231,15.871464 13.768036,15.7101991 L14.2784001,17.0884863 C14.2961491,17.1364191 14.3052407,17.1871941 14.3052407,17.2383863 C14.3052407,17.4741652 14.1165055,17.6653018 13.8836889,17.6653018 L12.805781,17.6381197 C12.8616756,18.8258731 13.2941654,19.508499 14.4169144,19.8875104 C14.8586529,20.0366301 15.0973861,20.5201716 14.95014,20.9675305 C14.8028938,21.4148895 14.3254274,21.6566602 13.8836889,21.5075406 C12.072317,20.8960676 11.1784281,19.5883144 11.1216188,17.6653018 L10.041666,17.6653018 C9.99111686,17.6653018 9.94097984,17.6560945 9.89364924,17.6381197 C9.67565622,17.5553322 9.56520732,17.309253 9.6469547,17.0884863 L10.1573188,15.7101991 Z" fill="#000000" fill-rule="nonzero"/>
						        <path d="M12,16 C8.13400675,16 5,12.8659932 5,9 C5,5.13400675 8.13400675,2 12,2 C15.8659932,2 19,5.13400675 19,9 C19,12.8659932 15.8659932,16 12,16 Z M8.81595773,8.80077353 C8.79067542,8.43921955 8.47708263,8.16661749 8.11552864,8.19189981 C7.75397465,8.21718213 7.4813726,8.53077492 7.50665492,8.89232891 C7.62279197,10.5531661 8.39667037,11.8635466 9.79502238,12.7671393 C10.099435,12.9638458 10.5056723,12.8765328 10.7023788,12.5721203 C10.8990854,12.2677077 10.8117724,11.8614704 10.5073598,11.6647638 C9.4559885,10.9853845 8.90327706,10.0494981 8.81595773,8.80077353 Z" fill="#000000" opacity="0.3"/>
						    </g>
						</svg><!--end::Svg Icon--></span>
					</div>
					<!--end::Symbol-->
					<!--begin::Text-->
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">Frustrations: </a>
					</div>
					<!--end::Text-->
					@if($spirituals->frustType == 0)
					<p class="lead ml-20">
						{{$spirituals->frust}}
					</p>
					@else 
					<audio controls>
					  <source src="/{{$spirituals->frust}}" type="audio/aac">
					  <source src="/{{$spirituals->frust}}" type="audio/mpeg">
					Your browser does not support the audio element.
					</audio>
					@endif
				</div>	

				<!-- Social -->
				<div class="d-flex align-items-center flex-wrap mb-10">
					<!--begin::Symbol-->
					<div class="symbol symbol-50 symbol-light mr-5">
						<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Home\Air-ballon.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
						    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						        <rect x="0" y="0" width="24" height="24"/>
						        <path d="M10.1573188,15.7101991 C10.7319317,15.871464 11.3373672,15.9576401 11.9626774,15.9576401 C12.5879876,15.9576401 13.1934231,15.871464 13.768036,15.7101991 L14.2784001,17.0884863 C14.2961491,17.1364191 14.3052407,17.1871941 14.3052407,17.2383863 C14.3052407,17.4741652 14.1165055,17.6653018 13.8836889,17.6653018 L12.805781,17.6381197 C12.8616756,18.8258731 13.2941654,19.508499 14.4169144,19.8875104 C14.8586529,20.0366301 15.0973861,20.5201716 14.95014,20.9675305 C14.8028938,21.4148895 14.3254274,21.6566602 13.8836889,21.5075406 C12.072317,20.8960676 11.1784281,19.5883144 11.1216188,17.6653018 L10.041666,17.6653018 C9.99111686,17.6653018 9.94097984,17.6560945 9.89364924,17.6381197 C9.67565622,17.5553322 9.56520732,17.309253 9.6469547,17.0884863 L10.1573188,15.7101991 Z" fill="#000000" fill-rule="nonzero"/>
						        <path d="M12,16 C8.13400675,16 5,12.8659932 5,9 C5,5.13400675 8.13400675,2 12,2 C15.8659932,2 19,5.13400675 19,9 C19,12.8659932 15.8659932,16 12,16 Z M8.81595773,8.80077353 C8.79067542,8.43921955 8.47708263,8.16661749 8.11552864,8.19189981 C7.75397465,8.21718213 7.4813726,8.53077492 7.50665492,8.89232891 C7.62279197,10.5531661 8.39667037,11.8635466 9.79502238,12.7671393 C10.099435,12.9638458 10.5056723,12.8765328 10.7023788,12.5721203 C10.8990854,12.2677077 10.8117724,11.8614704 10.5073598,11.6647638 C9.4559885,10.9853845 8.90327706,10.0494981 8.81595773,8.80077353 Z" fill="#000000" opacity="0.3"/>
						    </g>
						</svg><!--end::Svg Icon--></span>
					</div>
					<!--end::Symbol-->
					<!--begin::Text-->
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">Socializing (Friends, Family, Events): </a>
					</div>
					<!--end::Text-->
					@if($spirituals->socialType == 0)
					<p class="lead ml-20">
						{{$spirituals->social}}
					</p>
					@else 
					<audio controls>
					  <source src="/{{$spirituals->social}}" type="audio/aac">
					  <source src="/{{$spirituals->social}}" type="audio/mpeg">
					Your browser does not support the audio element.
					</audio>
					@endif
				</div>	


				<!-- Errand -->
				<div>
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">
							<i class="icon-xl fas fa-hand-holding-medical text-primary"></i>&nbsp; Errands/Chores Completed: 
						</a>
					</div>
					<br>
					<table class="table table-bordered">
					    <thead>
					        <tr>
					            <th scope="col">#</th>
					            <th scope="col">Healing</th>
					        </tr>
					    </thead>
					    <tbody>
					    	@foreach(json_Decode($spirituals->errand)  as $key => $item)
					      	<tr>
					            <th scope="row">{{ $key+1}}</th>
					            <td>{{$item->data}}</td>
					        </tr>
					        @endforeach
					    </tbody>
					</table>
					<br>

				</div>		



				<!-- Learn -->
				<div class="d-flex align-items-center flex-wrap mb-10">
					<!--begin::Symbol-->
					<div class="symbol symbol-50 symbol-light mr-5">
						<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Home\Air-ballon.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
						    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						        <rect x="0" y="0" width="24" height="24"/>
						        <path d="M10.1573188,15.7101991 C10.7319317,15.871464 11.3373672,15.9576401 11.9626774,15.9576401 C12.5879876,15.9576401 13.1934231,15.871464 13.768036,15.7101991 L14.2784001,17.0884863 C14.2961491,17.1364191 14.3052407,17.1871941 14.3052407,17.2383863 C14.3052407,17.4741652 14.1165055,17.6653018 13.8836889,17.6653018 L12.805781,17.6381197 C12.8616756,18.8258731 13.2941654,19.508499 14.4169144,19.8875104 C14.8586529,20.0366301 15.0973861,20.5201716 14.95014,20.9675305 C14.8028938,21.4148895 14.3254274,21.6566602 13.8836889,21.5075406 C12.072317,20.8960676 11.1784281,19.5883144 11.1216188,17.6653018 L10.041666,17.6653018 C9.99111686,17.6653018 9.94097984,17.6560945 9.89364924,17.6381197 C9.67565622,17.5553322 9.56520732,17.309253 9.6469547,17.0884863 L10.1573188,15.7101991 Z" fill="#000000" fill-rule="nonzero"/>
						        <path d="M12,16 C8.13400675,16 5,12.8659932 5,9 C5,5.13400675 8.13400675,2 12,2 C15.8659932,2 19,5.13400675 19,9 C19,12.8659932 15.8659932,16 12,16 Z M8.81595773,8.80077353 C8.79067542,8.43921955 8.47708263,8.16661749 8.11552864,8.19189981 C7.75397465,8.21718213 7.4813726,8.53077492 7.50665492,8.89232891 C7.62279197,10.5531661 8.39667037,11.8635466 9.79502238,12.7671393 C10.099435,12.9638458 10.5056723,12.8765328 10.7023788,12.5721203 C10.8990854,12.2677077 10.8117724,11.8614704 10.5073598,11.6647638 C9.4559885,10.9853845 8.90327706,10.0494981 8.81595773,8.80077353 Z" fill="#000000" opacity="0.3"/>
						    </g>
						</svg><!--end::Svg Icon--></span>
					</div>
					<!--end::Symbol-->
					<!--begin::Text-->
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">Something you learned: </a>
					</div>
					<!--end::Text-->
					@if($spirituals->learnType == 0)
					<p class="lead ml-20">
						{{$spirituals->learn}}
					</p>
					@else 
					<audio controls>
					  <source src="/{{$spirituals->learn}}" type="audio/aac">
					  <source src="/{{$spirituals->learn}}" type="audio/mpeg">
					Your browser does not support the audio element.
					</audio>
					@endif
				</div>	


				<!-- Mental Health -->
				<div>
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">
							<i class="icon-xl fas fa-hand-holding-medical text-primary"></i>&nbsp; Mental Health Treatments/Therapies: 
						</a>
					</div>
					<br>
					<table class="table table-bordered">
					    <thead>
					        <tr>
					            <th scope="col">#</th>
					            <th scope="col">Treatment</th>
					        </tr>
					    </thead>
					    <tbody>
					    	@foreach(json_Decode($spirituals->mental)  as $key => $item)
					      	<tr>
					            <th scope="row">{{ $key+1}}</th>
					            <td>{{$item->data}}</td>
					        </tr>
					        @endforeach
					    </tbody>
					</table>
					<br>

				</div>	



				<!-- Self Care -->
				<div>
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">
							<i class="icon-xl fas fa-hand-holding-medical text-primary"></i>&nbsp; Self-Care: 
						</a>
					</div>
					<br>
					<table class="table table-bordered">
					    <thead>
					        <tr>
					            <th scope="col">#</th>
					            <th scope="col">Self-Care</th>
					        </tr>
					    </thead>
					    <tbody>
					    	@foreach(json_Decode($spirituals->selfcare)  as $key => $item)
					      	<tr>
					            <th scope="row">{{ $key+1}}</th>
					            <td>{{$item->data}}</td>
					        </tr>
					        @endforeach
					    </tbody>
					</table>
					<br>

				</div>	




				<!-- Meditation -->
				<div>
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">
							<i class="icon-xl fas fa-hand-holding-medical text-primary"></i>&nbsp; Meditation: 
						</a>
					</div>
					<br>
					<table class="table table-bordered">
					    <thead>
					        <tr>
					            <th scope="col">#</th>
					            <th scope="col">Time</th>
					            <th scope="col">Length</th>
					        </tr>
					    </thead>
					    <tbody>
					    	@foreach(json_Decode($spirituals->meditation)  as $key => $item)
					      	<tr>
					            <th scope="row">{{ $key+1}}</th>
					            <td>{{$item->timeText}}</td>
					            <td>{{$item->length}}</td>
					        </tr>
					        @endforeach
					    </tbody>
					</table>
					<br>

				</div>	

				<!-- LoveBody -->
				<div class="d-flex align-items-center flex-wrap mb-10">
					<!--begin::Symbol-->
					<div class="symbol symbol-50 symbol-light mr-5">
						<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Home\Air-ballon.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
						    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						        <rect x="0" y="0" width="24" height="24"/>
						        <path d="M10.1573188,15.7101991 C10.7319317,15.871464 11.3373672,15.9576401 11.9626774,15.9576401 C12.5879876,15.9576401 13.1934231,15.871464 13.768036,15.7101991 L14.2784001,17.0884863 C14.2961491,17.1364191 14.3052407,17.1871941 14.3052407,17.2383863 C14.3052407,17.4741652 14.1165055,17.6653018 13.8836889,17.6653018 L12.805781,17.6381197 C12.8616756,18.8258731 13.2941654,19.508499 14.4169144,19.8875104 C14.8586529,20.0366301 15.0973861,20.5201716 14.95014,20.9675305 C14.8028938,21.4148895 14.3254274,21.6566602 13.8836889,21.5075406 C12.072317,20.8960676 11.1784281,19.5883144 11.1216188,17.6653018 L10.041666,17.6653018 C9.99111686,17.6653018 9.94097984,17.6560945 9.89364924,17.6381197 C9.67565622,17.5553322 9.56520732,17.309253 9.6469547,17.0884863 L10.1573188,15.7101991 Z" fill="#000000" fill-rule="nonzero"/>
						        <path d="M12,16 C8.13400675,16 5,12.8659932 5,9 C5,5.13400675 8.13400675,2 12,2 C15.8659932,2 19,5.13400675 19,9 C19,12.8659932 15.8659932,16 12,16 Z M8.81595773,8.80077353 C8.79067542,8.43921955 8.47708263,8.16661749 8.11552864,8.19189981 C7.75397465,8.21718213 7.4813726,8.53077492 7.50665492,8.89232891 C7.62279197,10.5531661 8.39667037,11.8635466 9.79502238,12.7671393 C10.099435,12.9638458 10.5056723,12.8765328 10.7023788,12.5721203 C10.8990854,12.2677077 10.8117724,11.8614704 10.5073598,11.6647638 C9.4559885,10.9853845 8.90327706,10.0494981 8.81595773,8.80077353 Z" fill="#000000" opacity="0.3"/>
						    </g>
						</svg><!--end::Svg Icon--></span>
					</div>
					<!--end::Symbol-->
					<!--begin::Text-->
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">Something you love about your body: </a>
					</div>
					<!--end::Text-->
					@if($spirituals->loveBodyType == 0)
					<p class="lead ml-20">
						{{$spirituals->loveBody}}
					</p>
					@else 
					<audio controls>
					  <source src="/{{$spirituals->loveBody}}" type="audio/aac">
					  <source src="/{{$spirituals->loveBody}}" type="audio/mpeg">
					Your browser does not support the audio element.
					</audio>
					@endif
				</div>		



				<!-- Successful Moment -->
				<div class="d-flex align-items-center flex-wrap mb-10">
					<!--begin::Symbol-->
					<div class="symbol symbol-50 symbol-light mr-5">
						<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Home\Air-ballon.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
						    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						        <rect x="0" y="0" width="24" height="24"/>
						        <path d="M10.1573188,15.7101991 C10.7319317,15.871464 11.3373672,15.9576401 11.9626774,15.9576401 C12.5879876,15.9576401 13.1934231,15.871464 13.768036,15.7101991 L14.2784001,17.0884863 C14.2961491,17.1364191 14.3052407,17.1871941 14.3052407,17.2383863 C14.3052407,17.4741652 14.1165055,17.6653018 13.8836889,17.6653018 L12.805781,17.6381197 C12.8616756,18.8258731 13.2941654,19.508499 14.4169144,19.8875104 C14.8586529,20.0366301 15.0973861,20.5201716 14.95014,20.9675305 C14.8028938,21.4148895 14.3254274,21.6566602 13.8836889,21.5075406 C12.072317,20.8960676 11.1784281,19.5883144 11.1216188,17.6653018 L10.041666,17.6653018 C9.99111686,17.6653018 9.94097984,17.6560945 9.89364924,17.6381197 C9.67565622,17.5553322 9.56520732,17.309253 9.6469547,17.0884863 L10.1573188,15.7101991 Z" fill="#000000" fill-rule="nonzero"/>
						        <path d="M12,16 C8.13400675,16 5,12.8659932 5,9 C5,5.13400675 8.13400675,2 12,2 C15.8659932,2 19,5.13400675 19,9 C19,12.8659932 15.8659932,16 12,16 Z M8.81595773,8.80077353 C8.79067542,8.43921955 8.47708263,8.16661749 8.11552864,8.19189981 C7.75397465,8.21718213 7.4813726,8.53077492 7.50665492,8.89232891 C7.62279197,10.5531661 8.39667037,11.8635466 9.79502238,12.7671393 C10.099435,12.9638458 10.5056723,12.8765328 10.7023788,12.5721203 C10.8990854,12.2677077 10.8117724,11.8614704 10.5073598,11.6647638 C9.4559885,10.9853845 8.90327706,10.0494981 8.81595773,8.80077353 Z" fill="#000000" opacity="0.3"/>
						    </g>
						</svg><!--end::Svg Icon--></span>
					</div>
					<!--end::Symbol-->
					<!--begin::Text-->
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">Successful Moments: </a>
					</div>
					<!--end::Text-->
					@if($spirituals->successMomentType == 0)
					<p class="lead ml-20">
						{{$spirituals->successMoment}}
					</p>
					@else 
					<audio controls>
					  <source src="/{{$spirituals->successMoment}}" type="audio/aac">
					  <source src="/{{$spirituals->successMoment}}" type="audio/mpeg">
					Your browser does not support the audio element.
					</audio>
					@endif
				</div>	



				<!-- Grateful -->
				<div class="d-flex align-items-center flex-wrap mb-10">
					<!--begin::Symbol-->
					<div class="symbol symbol-50 symbol-light mr-5">
						<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Home\Air-ballon.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
						    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						        <rect x="0" y="0" width="24" height="24"/>
						        <path d="M10.1573188,15.7101991 C10.7319317,15.871464 11.3373672,15.9576401 11.9626774,15.9576401 C12.5879876,15.9576401 13.1934231,15.871464 13.768036,15.7101991 L14.2784001,17.0884863 C14.2961491,17.1364191 14.3052407,17.1871941 14.3052407,17.2383863 C14.3052407,17.4741652 14.1165055,17.6653018 13.8836889,17.6653018 L12.805781,17.6381197 C12.8616756,18.8258731 13.2941654,19.508499 14.4169144,19.8875104 C14.8586529,20.0366301 15.0973861,20.5201716 14.95014,20.9675305 C14.8028938,21.4148895 14.3254274,21.6566602 13.8836889,21.5075406 C12.072317,20.8960676 11.1784281,19.5883144 11.1216188,17.6653018 L10.041666,17.6653018 C9.99111686,17.6653018 9.94097984,17.6560945 9.89364924,17.6381197 C9.67565622,17.5553322 9.56520732,17.309253 9.6469547,17.0884863 L10.1573188,15.7101991 Z" fill="#000000" fill-rule="nonzero"/>
						        <path d="M12,16 C8.13400675,16 5,12.8659932 5,9 C5,5.13400675 8.13400675,2 12,2 C15.8659932,2 19,5.13400675 19,9 C19,12.8659932 15.8659932,16 12,16 Z M8.81595773,8.80077353 C8.79067542,8.43921955 8.47708263,8.16661749 8.11552864,8.19189981 C7.75397465,8.21718213 7.4813726,8.53077492 7.50665492,8.89232891 C7.62279197,10.5531661 8.39667037,11.8635466 9.79502238,12.7671393 C10.099435,12.9638458 10.5056723,12.8765328 10.7023788,12.5721203 C10.8990854,12.2677077 10.8117724,11.8614704 10.5073598,11.6647638 C9.4559885,10.9853845 8.90327706,10.0494981 8.81595773,8.80077353 Z" fill="#000000" opacity="0.3"/>
						    </g>
						</svg><!--end::Svg Icon--></span>
					</div>
					<!--end::Symbol-->
					<!--begin::Text-->
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">What are you grateful for today: </a>
					</div>
					<!--end::Text-->
					@if($spirituals->gratefulType == 0)
					<p class="lead ml-20">
						{{$spirituals->grateful}}
					</p>
					@else 
					<audio controls>
					  <source src="/{{$spirituals->grateful}}" type="audio/aac">
					  <source src="/{{$spirituals->grateful}}" type="audio/mpeg">
					Your browser does not support the audio element.
					</audio>
					@endif
				</div>



				<!-- Forgive -->
				<div class="d-flex align-items-center flex-wrap mb-10">
					<!--begin::Symbol-->
					<div class="symbol symbol-50 symbol-light mr-5">
						<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Home\Air-ballon.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
						    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						        <rect x="0" y="0" width="24" height="24"/>
						        <path d="M10.1573188,15.7101991 C10.7319317,15.871464 11.3373672,15.9576401 11.9626774,15.9576401 C12.5879876,15.9576401 13.1934231,15.871464 13.768036,15.7101991 L14.2784001,17.0884863 C14.2961491,17.1364191 14.3052407,17.1871941 14.3052407,17.2383863 C14.3052407,17.4741652 14.1165055,17.6653018 13.8836889,17.6653018 L12.805781,17.6381197 C12.8616756,18.8258731 13.2941654,19.508499 14.4169144,19.8875104 C14.8586529,20.0366301 15.0973861,20.5201716 14.95014,20.9675305 C14.8028938,21.4148895 14.3254274,21.6566602 13.8836889,21.5075406 C12.072317,20.8960676 11.1784281,19.5883144 11.1216188,17.6653018 L10.041666,17.6653018 C9.99111686,17.6653018 9.94097984,17.6560945 9.89364924,17.6381197 C9.67565622,17.5553322 9.56520732,17.309253 9.6469547,17.0884863 L10.1573188,15.7101991 Z" fill="#000000" fill-rule="nonzero"/>
						        <path d="M12,16 C8.13400675,16 5,12.8659932 5,9 C5,5.13400675 8.13400675,2 12,2 C15.8659932,2 19,5.13400675 19,9 C19,12.8659932 15.8659932,16 12,16 Z M8.81595773,8.80077353 C8.79067542,8.43921955 8.47708263,8.16661749 8.11552864,8.19189981 C7.75397465,8.21718213 7.4813726,8.53077492 7.50665492,8.89232891 C7.62279197,10.5531661 8.39667037,11.8635466 9.79502238,12.7671393 C10.099435,12.9638458 10.5056723,12.8765328 10.7023788,12.5721203 C10.8990854,12.2677077 10.8117724,11.8614704 10.5073598,11.6647638 C9.4559885,10.9853845 8.90327706,10.0494981 8.81595773,8.80077353 Z" fill="#000000" opacity="0.3"/>
						    </g>
						</svg><!--end::Svg Icon--></span>
					</div>
					<!--end::Symbol-->
					<!--begin::Text-->
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">Something you can forgive: </a>
					</div>
					<!--end::Text-->
					@if($spirituals->forgiveType == 0)
					<p class="lead ml-20">
						{{$spirituals->forgive}}
					</p>
					@else 
					<audio controls>
					  <source src="/{{$spirituals->forgive}}" type="audio/aac">
					  <source src="/{{$spirituals->forgive}}" type="audio/mpeg">
					Your browser does not support the audio element.
					</audio>
					@endif
				</div>		



				<!-- Release -->
				<div class="d-flex align-items-center flex-wrap mb-10">
					<!--begin::Symbol-->
					<div class="symbol symbol-50 symbol-light mr-5">
						<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Home\Air-ballon.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
						    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						        <rect x="0" y="0" width="24" height="24"/>
						        <path d="M10.1573188,15.7101991 C10.7319317,15.871464 11.3373672,15.9576401 11.9626774,15.9576401 C12.5879876,15.9576401 13.1934231,15.871464 13.768036,15.7101991 L14.2784001,17.0884863 C14.2961491,17.1364191 14.3052407,17.1871941 14.3052407,17.2383863 C14.3052407,17.4741652 14.1165055,17.6653018 13.8836889,17.6653018 L12.805781,17.6381197 C12.8616756,18.8258731 13.2941654,19.508499 14.4169144,19.8875104 C14.8586529,20.0366301 15.0973861,20.5201716 14.95014,20.9675305 C14.8028938,21.4148895 14.3254274,21.6566602 13.8836889,21.5075406 C12.072317,20.8960676 11.1784281,19.5883144 11.1216188,17.6653018 L10.041666,17.6653018 C9.99111686,17.6653018 9.94097984,17.6560945 9.89364924,17.6381197 C9.67565622,17.5553322 9.56520732,17.309253 9.6469547,17.0884863 L10.1573188,15.7101991 Z" fill="#000000" fill-rule="nonzero"/>
						        <path d="M12,16 C8.13400675,16 5,12.8659932 5,9 C5,5.13400675 8.13400675,2 12,2 C15.8659932,2 19,5.13400675 19,9 C19,12.8659932 15.8659932,16 12,16 Z M8.81595773,8.80077353 C8.79067542,8.43921955 8.47708263,8.16661749 8.11552864,8.19189981 C7.75397465,8.21718213 7.4813726,8.53077492 7.50665492,8.89232891 C7.62279197,10.5531661 8.39667037,11.8635466 9.79502238,12.7671393 C10.099435,12.9638458 10.5056723,12.8765328 10.7023788,12.5721203 C10.8990854,12.2677077 10.8117724,11.8614704 10.5073598,11.6647638 C9.4559885,10.9853845 8.90327706,10.0494981 8.81595773,8.80077353 Z" fill="#000000" opacity="0.3"/>
						    </g>
						</svg><!--end::Svg Icon--></span>
					</div>
					<!--end::Symbol-->
					<!--begin::Text-->
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">Something you can release: </a>
					</div>
					<!--end::Text-->
					@if($spirituals->releaseType == 0)
					<p class="lead ml-20">
						{{$spirituals->release}}
					</p>
					@else 
					<audio controls>
					  <source src="/{{$spirituals->release}}" type="audio/aac">
					  <source src="/{{$spirituals->release}}" type="audio/mpeg">
					Your browser does not support the audio element.
					</audio>
					@endif
				</div>


				<!-- Goal -->
				<div class="d-flex align-items-center flex-wrap mb-10">
					<!--begin::Symbol-->
					<div class="symbol symbol-50 symbol-light mr-5">
						<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Home\Air-ballon.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
						    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						        <rect x="0" y="0" width="24" height="24"/>
						        <path d="M10.1573188,15.7101991 C10.7319317,15.871464 11.3373672,15.9576401 11.9626774,15.9576401 C12.5879876,15.9576401 13.1934231,15.871464 13.768036,15.7101991 L14.2784001,17.0884863 C14.2961491,17.1364191 14.3052407,17.1871941 14.3052407,17.2383863 C14.3052407,17.4741652 14.1165055,17.6653018 13.8836889,17.6653018 L12.805781,17.6381197 C12.8616756,18.8258731 13.2941654,19.508499 14.4169144,19.8875104 C14.8586529,20.0366301 15.0973861,20.5201716 14.95014,20.9675305 C14.8028938,21.4148895 14.3254274,21.6566602 13.8836889,21.5075406 C12.072317,20.8960676 11.1784281,19.5883144 11.1216188,17.6653018 L10.041666,17.6653018 C9.99111686,17.6653018 9.94097984,17.6560945 9.89364924,17.6381197 C9.67565622,17.5553322 9.56520732,17.309253 9.6469547,17.0884863 L10.1573188,15.7101991 Z" fill="#000000" fill-rule="nonzero"/>
						        <path d="M12,16 C8.13400675,16 5,12.8659932 5,9 C5,5.13400675 8.13400675,2 12,2 C15.8659932,2 19,5.13400675 19,9 C19,12.8659932 15.8659932,16 12,16 Z M8.81595773,8.80077353 C8.79067542,8.43921955 8.47708263,8.16661749 8.11552864,8.19189981 C7.75397465,8.21718213 7.4813726,8.53077492 7.50665492,8.89232891 C7.62279197,10.5531661 8.39667037,11.8635466 9.79502238,12.7671393 C10.099435,12.9638458 10.5056723,12.8765328 10.7023788,12.5721203 C10.8990854,12.2677077 10.8117724,11.8614704 10.5073598,11.6647638 C9.4559885,10.9853845 8.90327706,10.0494981 8.81595773,8.80077353 Z" fill="#000000" opacity="0.3"/>
						    </g>
						</svg><!--end::Svg Icon--></span>
					</div>
					<!--end::Symbol-->
					<!--begin::Text-->
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">One goal for tomorrow: </a>
					</div>
					<!--end::Text-->
					@if($spirituals->goalType == 0)
					<p class="lead ml-20">
						{{$spirituals->goal}}
					</p>
					@else 
					<audio controls>
					  <source src="/{{$spirituals->goal}}" type="audio/aac">
					  <source src="/{{$spirituals->goal}}" type="audio/mpeg">
					Your browser does not support the audio element.
					</audio>
					@endif
				</div>		


				<!-- Note -->
				<div class="d-flex align-items-center flex-wrap mb-10">
					<!--begin::Symbol-->
					<div class="symbol symbol-50 symbol-light mr-5">
						<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Home\Air-ballon.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
						    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						        <rect x="0" y="0" width="24" height="24"/>
						        <path d="M10.1573188,15.7101991 C10.7319317,15.871464 11.3373672,15.9576401 11.9626774,15.9576401 C12.5879876,15.9576401 13.1934231,15.871464 13.768036,15.7101991 L14.2784001,17.0884863 C14.2961491,17.1364191 14.3052407,17.1871941 14.3052407,17.2383863 C14.3052407,17.4741652 14.1165055,17.6653018 13.8836889,17.6653018 L12.805781,17.6381197 C12.8616756,18.8258731 13.2941654,19.508499 14.4169144,19.8875104 C14.8586529,20.0366301 15.0973861,20.5201716 14.95014,20.9675305 C14.8028938,21.4148895 14.3254274,21.6566602 13.8836889,21.5075406 C12.072317,20.8960676 11.1784281,19.5883144 11.1216188,17.6653018 L10.041666,17.6653018 C9.99111686,17.6653018 9.94097984,17.6560945 9.89364924,17.6381197 C9.67565622,17.5553322 9.56520732,17.309253 9.6469547,17.0884863 L10.1573188,15.7101991 Z" fill="#000000" fill-rule="nonzero"/>
						        <path d="M12,16 C8.13400675,16 5,12.8659932 5,9 C5,5.13400675 8.13400675,2 12,2 C15.8659932,2 19,5.13400675 19,9 C19,12.8659932 15.8659932,16 12,16 Z M8.81595773,8.80077353 C8.79067542,8.43921955 8.47708263,8.16661749 8.11552864,8.19189981 C7.75397465,8.21718213 7.4813726,8.53077492 7.50665492,8.89232891 C7.62279197,10.5531661 8.39667037,11.8635466 9.79502238,12.7671393 C10.099435,12.9638458 10.5056723,12.8765328 10.7023788,12.5721203 C10.8990854,12.2677077 10.8117724,11.8614704 10.5073598,11.6647638 C9.4559885,10.9853845 8.90327706,10.0494981 8.81595773,8.80077353 Z" fill="#000000" opacity="0.3"/>
						    </g>
						</svg><!--end::Svg Icon--></span>
					</div>
					<!--end::Symbol-->
					<!--begin::Text-->
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">Diary/Notes: </a>
					</div>
					<!--end::Text-->
					@if($spirituals->diaryType == 0)
					<p class="lead ml-20">
						{{$spirituals->diary}}
					</p>
					@else 
					<audio controls>
					  <source src="/{{$spirituals->diary}}" type="audio/aac">
					  <source src="/{{$spirituals->diary}}" type="audio/mpeg">
					Your browser does not support the audio element.
					</audio>
					@endif
				</div>		

			</div>
			@endif
		</div>
	</div>
</div>

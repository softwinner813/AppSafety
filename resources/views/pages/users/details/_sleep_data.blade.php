<div class="tab-pane px-7" id="kt_user_edit_tab_2" role="tabpanel">
	<div class="card-body">
		<div class="row">
			<!-- <div class="col-xl-2"></div> -->
			@if(!isset($sleeps))
			<p><em>There is no data</em></p>
			@else
			<div class="col-xl-12">

				<!-- BED TIME -->
				<div class="d-flex align-items-center flex-wrap mb-10">
					<!--begin::Symbol-->
					<div class="symbol symbol-50 symbol-light mr-5">
						<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Home\Bed.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
					    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
					        <rect x="0" y="0" width="24" height="24"/>
					        <path d="M4,22 L2,22 C2,19.2385763 4.23857625,18 7,18 L17,18 C19.7614237,18 22,19.2385763 22,22 L20,22 C20,20.3431458 18.6568542,20 17,20 L7,20 C5.34314575,20 4,20.3431458 4,22 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
					        <rect fill="#000000" x="1" y="14" width="22" height="6" rx="1"/>
					        <path d="M13,13 L11,13 L11,12 C11,11.4477153 10.5522847,11 10,11 L6,11 C5.44771525,11 5,11.4477153 5,12 L5,13 L4,13 C3.44771525,13 3,12.5522847 3,12 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,12 C21,12.5522847 20.5522847,13 20,13 L19,13 L19,12 C19,11.4477153 18.5522847,11 18,11 L14,11 C13.4477153,11 13,11.4477153 13,12 L13,13 Z" fill="#000000" opacity="0.3"/>
					    </g>
					</svg><!--end::Svg Icon--></span>
					</div>
					<!--end::Symbol-->
					<!--begin::Text-->
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">Hour I Went To Bed: </a>
					</div>
					<!--end::Text-->
					<span class="label label-xl label-light label-inline my-lg-0 my-2 text-dark-50 font-weight-bolder">{{$sleeps->bedtime}}</span>
				</div>


				<!-- WOKE TIME -->
				<div class="d-flex align-items-center flex-wrap mb-10">
					<!--begin::Symbol-->
					<div class="symbol symbol-50 symbol-light mr-5">
						<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Home\Alarm-clock.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
						    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						        <rect x="0" y="0" width="24" height="24"/>
						        <path d="M7.14319965,19.3575259 C7.67122143,19.7615175 8.25104409,20.1012165 8.87097532,20.3649307 L7.89205065,22.0604779 C7.61590828,22.5387706 7.00431787,22.7026457 6.52602525,22.4265033 C6.04773263,22.150361 5.88385747,21.5387706 6.15999985,21.0604779 L7.14319965,19.3575259 Z M15.1367085,20.3616573 C15.756345,20.0972995 16.3358198,19.7569961 16.8634386,19.3524415 L17.8320512,21.0301278 C18.1081936,21.5084204 17.9443184,22.1200108 17.4660258,22.3961532 C16.9877332,22.6722956 16.3761428,22.5084204 16.1000004,22.0301278 L15.1367085,20.3616573 Z" fill="#000000"/>
						        <path d="M12,21 C7.581722,21 4,17.418278 4,13 C4,8.581722 7.581722,5 12,5 C16.418278,5 20,8.581722 20,13 C20,17.418278 16.418278,21 12,21 Z M19.068812,3.25407593 L20.8181344,5.00339833 C21.4039208,5.58918477 21.4039208,6.53893224 20.8181344,7.12471868 C20.2323479,7.71050512 19.2826005,7.71050512 18.696814,7.12471868 L16.9474916,5.37539627 C16.3617052,4.78960984 16.3617052,3.83986237 16.9474916,3.25407593 C17.5332781,2.66828949 18.4830255,2.66828949 19.068812,3.25407593 Z M5.29862906,2.88207799 C5.8844155,2.29629155 6.83416297,2.29629155 7.41994941,2.88207799 C8.00573585,3.46786443 8.00573585,4.4176119 7.41994941,5.00339833 L5.29862906,7.12471868 C4.71284263,7.71050512 3.76309516,7.71050512 3.17730872,7.12471868 C2.59152228,6.53893224 2.59152228,5.58918477 3.17730872,5.00339833 L5.29862906,2.88207799 Z" fill="#000000" opacity="0.3"/>
						        <path d="M11.9630156,7.5 L12.0475062,7.5 C12.3043819,7.5 12.5194647,7.69464724 12.5450248,7.95024814 L13,12.5 L16.2480695,14.3560397 C16.403857,14.4450611 16.5,14.6107328 16.5,14.7901613 L16.5,15 C16.5,15.2109164 16.3290185,15.3818979 16.1181021,15.3818979 C16.0841582,15.3818979 16.0503659,15.3773725 16.0176181,15.3684413 L11.3986612,14.1087258 C11.1672824,14.0456225 11.0132986,13.8271186 11.0316926,13.5879956 L11.4644883,7.96165175 C11.4845267,7.70115317 11.7017474,7.5 11.9630156,7.5 Z" fill="#000000"/>
						    </g>
						</svg><!--end::Svg Icon--></span>
					</div>
					<!--end::Symbol-->
					<!--begin::Text-->
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">Hour I Woke Up: </a>
					</div>
					<!--end::Text-->
					<span class="label label-xl label-light label-inline my-lg-0 my-2 text-dark-50 font-weight-bolder">{{$sleeps->waketime}}</span>
				</div>


				<!-- Dream -->
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
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">Dream: </a>
					</div>
					<!--end::Text-->
					@if($sleeps->dream_type == 0)
					<p class="lead">
						{{$sleeps->dream_subject}}
					</p>
					@else 
					<audio controls>
					  <source src="/{{$sleeps->dream_subject}}" type="audio/aac">
					  <source src="/{{$sleeps->dream_subject}}" type="audio/mpeg">
						Your browser does not support the audio element.
					</audio>
					@endif
				</div>

				<!-- Quality of Sleep -->
				<div class="d-flex align-items-center flex-wrap mb-10">
					<!--begin::Symbol-->
					<div class="symbol symbol-50 symbol-light mr-5">
						<i class="icon-xl far fa-smile text-primary"></i>
					</div>
					<!--end::Symbol-->
					<!--begin::Text-->
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">Quality Of Sleep: </a>
					</div>
					<!--end::Text-->
					<div class="symbol symbol-light mr-5">
						<span class="symbol-label">
							<img src="/media/emoji/{{$sleeps->sleepQ}}.jpg" class="h-100 align-self-center" alt="">
						</span>
					</div>
				</div>

				<!-- Naps -->
				<div class="d-flex flex-column flex-grow-1 mr-2">
					<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">
					<i class="icon-xl far fa-smile text-primary"></i>&nbsp; Naps: </a>
				</div>
				<table class="table table-bordered">
				    <thead>
				        <tr>
				            <th scope="col">#</th>
				            <th scope="col">TIME</th>
				        </tr>
				    </thead>
				    <tbody>
				    	@foreach(json_Decode($sleeps->naps)  as $key => $item)
				        <tr>
				            <th scope="row">{{ $key}}</th>
				            <td>{{$item}}</td>
				        </tr>
				        @endforeach
				    </tbody>
				</table>

			</div>
			<hr>
			@endif

		</div>
	</div>
</div>

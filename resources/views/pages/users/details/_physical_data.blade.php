<div class="tab-pane px-7" id="kt_user_edit_tab_3" role="tabpanel">
	<div class="card-body">
		<div class="row">
			<!-- <div class="col-xl-2"></div> -->
			@if(!isset($physicals) )
			<p><em>There is no data</em></p>
			@else
			<div class="col-xl-12">
				<h2>Physical Health</h2>
				<br>

				<!-- Medications -->
				<div>
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">
							<i class="icon-xl fas fa-briefcase-medical text-primary"></i>&nbsp; Medications / Supplements: 
						</a>
					</div>
					<br>
					<table class="table table-bordered">
					    <thead>
					        <tr>
					            <th scope="col">#</th>
					            <th scope="col">Medicine</th>
					            <th scope="col">Dose</th>
					            <th scope="col">Amount</th>
					        </tr>
					    </thead>
					    <tbody>
					    	@foreach(json_Decode($physicals->medication)  as $key => $item)
					        <tr>
					            <th scope="row">{{ $key+1}}</th>
					            <td>{{$item->medicine}}</td>
					            <td>{{$item->dose}}</td>
					            <td>{{$item->num}}</td>
					        </tr>
					        @endforeach
					    </tbody>
					</table>
					<br>

				</div>


				<!-- Symptoms -->
				<div>
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">
							<i class="icon-xl fas fa-hand-holding-medical text-primary"></i>&nbsp; Symptoms: 
						</a>
					</div>
					<br>
					<table class="table table-bordered">
					    <thead>
					        <tr>
					            <th scope="col">#</th>
					            <th scope="col">Type</th>
					            <th scope="col">Symptom</th>
					            <th scope="col">Time</th>
					        </tr>
					    </thead>
					    <tbody>
					    	@foreach(json_Decode($physicals->symptom)  as $key => $item)
					      	<tr>
					            <th scope="row">{{ $key+1}}</th>
					            <td>
					            	@if($item->type == 0)
					            	<span class="label label-xl label-danger label-pill label-inline mr-2">NEW</span>
					            	@else
					            	<span class="label label-xl label-primary label-pill label-inline mr-2">RECURRING</span>
					            	@endif
					            </td>
					            <td>{{$item->subject}}</td>
					            <td>{{$item->time}}</td>
					        </tr>
					        @endforeach
					    </tbody>
					</table>
					<br>

				</div>		


				<!-- Treatment -->
				<div>
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">
							<i class="icon-xl fas fa-hand-holding-medical text-primary"></i>&nbsp; Medical and Healing Appointments and Therapies: 
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
					    	@foreach(json_Decode($physicals->treatment)  as $key => $item)
					      	<tr>
					            <th scope="row">{{ $key+1}}</th>
					            <td>{{$item->subject}}</td>
					        </tr>
					        @endforeach
					    </tbody>
					</table>
					<br>

				</div>	


				<!-- Bowel -->
				<div>
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">
							<i class="icon-xl fas fa-toilet text-primary"></i>&nbsp; Bowel Movements: 
						</a>
					</div>
					<br>
					<div class="row">
						<div class="col-md-3">
							<b class="text-center">Bowel Type</b>
							<img height="200" width="auto" src="/media/bowel/web-stool-type{{$physicals->bowel_type + 1}}.png">
						</div>
						<div class="col-md-3">
							<b class="text-center">Bowel Color</b>
							<?php $colors = array('#120e02','#9f9421','#7a6f05','#a1731b','#272101','#b80a2d','#583b13','#7b4e0d','#a59702','#542e01','#908b61');?>
							<div style="background-color: {{$colors[$physicals->bowel_color]}}; width: 150px; height: 150px;"></div>
						</div>
					</div>
					
					<br>

				</div>							
				
				<!-- Tongue -->
				<div>
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">
							<i class="icon-xl far fa-grin-tongue text-primary"></i>&nbsp; Tongue Condition: 
						</a>
					</div>
					<br>
					@if($physicals->tongue_image == null)
					<p>No Data</p>
					@else
					<div class="symbol symbol-100 symbol-xxl-150 mr-5 align-self-start align-self-xxl-center">
						<div class="symbol-label" style="background-image:url('/{{$physicals->tongue_image}}')"></div>
					</div>
					@endif
					<br>
				</div>


				<!-- WEIGHT -->
				<div class="d-flex align-items-center flex-wrap mt-5 mb-10">
					<!--begin::Symbol-->
					<div class="symbol symbol-50 symbol-light mr-5">
						<i class="icon-xl fas fa-weight text-primary"></i>&nbsp; 

					</div>
					<!--end::Symbol-->
					<!--begin::Text-->
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">Weight: </a>
					</div>
					<!--end::Text-->
					<span class="label label-xl label-light label-inline my-lg-0 my-2 text-dark-50 font-weight-bolder">{{$physicals->weight}}</span>
				</div>


				<!-- Day of Menstrual Cycle -->
				<div class="d-flex align-items-center flex-wrap mt-5 mb-10">
					<!--begin::Symbol-->
					<div class="symbol symbol-50 symbol-light mr-5">
						<i class="icon-xl fas fa-recycle text-primary"></i>&nbsp; 

					</div>
					<!--end::Symbol-->
					<!--begin::Text-->
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">Day of Menstrual Cycle: </a>
					</div>
					<!--end::Text-->
					<span class="label label-xl label-light label-inline my-lg-0 my-2 text-dark-50 font-weight-bolder">{{$physicals->menstrual}}</span>
				</div>

				<!-- Body Temperature -->
				<div>
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">
							<i class="icon-xl fas fa-temperature-low text-primary"></i>&nbsp; Body Temperature: 
						</a>
					</div>
					<br>
					<table class="table table-bordered">
					    <thead>
					        <tr>
					            <th scope="col">#</th>
					            <th scope="col">Temperature</th>
					            <th scope="col">Time</th>
					        </tr>
					    </thead>
					    <tbody>
					    	@foreach(json_Decode($physicals->bodyT)  as $key => $item)
					        <tr>
					            <th scope="row">{{ $key+1}}</th>
					            <td>{{$item->temp}}</td>
					            <td>{{$item->timeText}}</td>
					        </tr>
					        @endforeach
					    </tbody>
					</table>
					<br>

				</div>

				<!-- Blood Pressure -->
				<div>
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">
							<i class="icon-xl fas fa-compress-arrows-alt text-primary"></i>&nbsp; Blood Pressure: 
						</a>
					</div>
					<br>
					<table class="table table-bordered">
					    <thead>
					        <tr>
					            <th scope="col">#</th>
					            <th scope="col">Blood Pressure</th>
					            <th scope="col">Time</th>
					        </tr>
					    </thead>
					    <tbody>
					    	@foreach(json_Decode($physicals->bloodP)  as $key => $item)
					        <tr>
					            <th scope="row">{{ $key+1}}</th>
					            <td>{{$item->val}}</td>
					            <td>{{$item->timeText}}</td>
					        </tr>
					        @endforeach
					    </tbody>
					</table>
					<br>

				</div>		


				<!-- Blood Sugar -->
				<div>
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">
							<i class="icon-xl fas fa-tint text-primary"></i>&nbsp; Blood Sugar: 
						</a>
					</div>
					<br>
					<table class="table table-bordered">
					    <thead>
					        <tr>
					            <th scope="col">#</th>
					            <th scope="col">Blood Sugar</th>
					            <th scope="col">Time</th>
					        </tr>
					    </thead>
					    <tbody>
					    	@foreach(json_Decode($physicals->bloodS)  as $key => $item)
					        <tr>
					            <th scope="row">{{ $key+1}}</th>
					            <td>{{$item->val}}</td>
					            <td>{{$item->timeText}}</td>
					        </tr>
					        @endforeach
					    </tbody>
					</table>
					<br>

				</div>	


				<!-- Body Alkalinity -->
				<div>
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">
							<i class="icon-xl fas fa-tachometer-alt text-primary"></i>&nbsp; Body Alkalinity/Acidity: 
						</a>
					</div>
					<br>
					<table class="table table-bordered">
					    <thead>
					        <tr>
					            <th scope="col">#</th>
					            <th scope="col">Alkalinity/Acidity</th>
					            <th scope="col">Time</th>
					        </tr>
					    </thead>
					    <tbody>
					    	@foreach(json_Decode($physicals->bodyAcidity)  as $key => $item)
					        <tr>
					            <th scope="row">{{ $key+1}}</th>
					            <td>{{$item->val}}</td>
					            <td>{{$item->timeText}}</td>
					        </tr>
					        @endforeach
					    </tbody>
					</table>
					<br>

				</div>	


				<!-- Pain Level -->
				<div class="d-flex align-items-center flex-wrap mt-5 mb-10">
					<div class="symbol symbol-50 symbol-light mr-5">
						<i class="icon-xl far fa-sad-tear text-primary"></i>&nbsp; 
					</div>
					<div class=" mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">Pain Level: </a>
					</div>
					<div class="d-flex flex-column flex-grow-1">
						<div class="progress  mr-2">
						    <div class="progress-bar progress-bar-striped" role="progressbar" style="width: {{$physicals->pain * 10}}%" aria-valuenow="{{$physicals->pain * 10}}" aria-valuemin="0" aria-valuemax="100">
						    	
						    </div>
						</div>
					</div>
					
					
					<span class="label label-xl label-primary label-inline my-lg-0 my-2  font-weight-bolder">{{$physicals->pain}}</span>
				</div>


				<!-- Energy Level -->
				<div class="d-flex align-items-center flex-wrap mt-5 mb-10">
					<div class="symbol symbol-50 symbol-light mr-5">
						<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Code\CMD.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
					    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
					        <rect x="0" y="0" width="24" height="24"/>
					        <path d="M9,15 L7.5,15 C6.67157288,15 6,15.6715729 6,16.5 C6,17.3284271 6.67157288,18 7.5,18 C8.32842712,18 9,17.3284271 9,16.5 L9,15 Z M9,15 L9,9 L15,9 L15,15 L9,15 Z M15,16.5 C15,17.3284271 15.6715729,18 16.5,18 C17.3284271,18 18,17.3284271 18,16.5 C18,15.6715729 17.3284271,15 16.5,15 L15,15 L15,16.5 Z M16.5,9 C17.3284271,9 18,8.32842712 18,7.5 C18,6.67157288 17.3284271,6 16.5,6 C15.6715729,6 15,6.67157288 15,7.5 L15,9 L16.5,9 Z M9,7.5 C9,6.67157288 8.32842712,6 7.5,6 C6.67157288,6 6,6.67157288 6,7.5 C6,8.32842712 6.67157288,9 7.5,9 L9,9 L9,7.5 Z M11,13 L13,13 L13,11 L11,11 L11,13 Z M13,11 L13,7.5 C13,5.56700338 14.5670034,4 16.5,4 C18.4329966,4 20,5.56700338 20,7.5 C20,9.43299662 18.4329966,11 16.5,11 L13,11 Z M16.5,13 C18.4329966,13 20,14.5670034 20,16.5 C20,18.4329966 18.4329966,20 16.5,20 C14.5670034,20 13,18.4329966 13,16.5 L13,13 L16.5,13 Z M11,16.5 C11,18.4329966 9.43299662,20 7.5,20 C5.56700338,20 4,18.4329966 4,16.5 C4,14.5670034 5.56700338,13 7.5,13 L11,13 L11,16.5 Z M7.5,11 C5.56700338,11 4,9.43299662 4,7.5 C4,5.56700338 5.56700338,4 7.5,4 C9.43299662,4 11,5.56700338 11,7.5 L11,11 L7.5,11 Z" fill="#000000" fill-rule="nonzero"/>
					    </g>
					</svg><!--end::Svg Icon--></span> 
					</div>
					<div class=" mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">Energy Level: </a>
					</div>
					<div class="d-flex flex-column flex-grow-1">
						<div class="progress  mr-2">
						    <div class="progress-bar bg-success progress-bar-striped" role="progressbar" style="width: {{$physicals->energy * 10}}%" aria-valuenow="{{$physicals->energy * 10}}" aria-valuemin="0" aria-valuemax="100">
						    	
						    </div>
						</div>
					</div>
					
					
					<span class="label label-xl label-success label-inline my-lg-0 my-2  font-weight-bolder">{{$physicals->energy}}</span>
				</div>

				<!-- Stress Level -->
				<div class="d-flex align-items-center flex-wrap mt-5 mb-10">
					<div class="symbol symbol-50 symbol-light mr-5">
						<i class="icon-xl far fa-grin-squint-tears text-primary"></i>&nbsp; 
					</div>
					<div class=" mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">Stress Level: </a>
					</div>
					<div class="d-flex flex-column flex-grow-1">
						<div class="progress  mr-2">
						    <div class="progress-bar bg-danger progress-bar-striped" role="progressbar" style="width: {{$physicals->stress * 10}}%" aria-valuenow="{{$physicals->stress * 10}}" aria-valuemin="0" aria-valuemax="100">
						    	
						    </div>
						</div>
					</div>
					
					
					<span class="label label-xl label-danger label-inline my-lg-0 my-2  font-weight-bolder">{{$physicals->stress}}</span>
				</div>


				<!-- Exercise -->
				<div>
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">
							<i class="icon-xl fas fa-running text-primary"></i>&nbsp; Exercise / Movement: 
						</a>
					</div>
					<br>
					<table class="table table-bordered">
					    <thead>
					        <tr>
					            <th scope="col">#</th>
					            <th scope="col">Exercise/Movement</th>
					            <th scope="col">Time</th>
					        </tr>
					    </thead>
					    <tbody>
					    	@foreach(json_Decode($physicals->exercise)  as $key => $item)
					        <tr>
					            <th scope="row">{{ $key+1}}</th>
					            <td>{{$item->val}}</td>
					            <td>{{$item->timeText}}</td>
					        </tr>
					        @endforeach
					    </tbody>
					</table>
					<br>

				</div>		
			</div>
			@endif
		</div>
	</div>
</div>

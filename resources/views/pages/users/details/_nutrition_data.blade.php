<div class="tab-pane show active px-7" id="kt_user_edit_tab_1" role="tabpanel">
	<div class="card-body">
		<div class="row">
			<!-- <div class="col-xl-2"></div> -->
			<div class="col-xl-12">
				<!-- Breakfast -->
				<div class="card card-custom" data-card="true" id="kt_card_1">
					<div class="card-header">
						<div class="card-title">
							<h3 class="card-label">Breakfast</h3>
						</div>
						<div class="card-toolbar">
							<a href="#" class="btn btn-icon btn-sm btn-hover-light-primary mr-1" data-card-tool="toggle" data-toggle="tooltip" data-placement="top" title="" data-original-title="Toggle Card">
								<i class="ki ki-arrow-down icon-nm"></i>
							</a>
						</div>
					</div>
					<div class="card-body" kt-hidden-height="357" style="">
						<table class="table table-bordered">
						    <thead>
						        <tr>
						            <th scope="col">#</th>
						            <th scope="col">Food</th>
						            <th scope="col">Calories</th>
						        </tr>
						    </thead>
						    <tbody>
						    	<?php $breakfastCal = 0; $breakIndex = 0;?>
						    	@foreach($nutritions->where("type", 0) as $item)
						    	<?php $breakfastCal+= $item->cal; $breakIndex++?>
						        <tr>
						            <th scope="row">{{ $breakIndex}}</th>
						            <td>{{$item->food}}</td>
						            <td>{{$item->cal}}</td>
						        </tr>
						        @endforeach
						        <tr>
						            <th scope="row"></th>
						            <th>Total</th>
						            <th>{{$breakfastCal}}</th>
						        </tr>
						     	
						    </tbody>
						</table>
					</div>
				</div>
				<br>

				<!-- Lunch -->
				<div class="card card-custom" data-card="true" id="kt_card_1">
					<div class="card-header">
						<div class="card-title">
							<h3 class="card-label">Lunch</h3>
						</div>
						<div class="card-toolbar">
							<a href="#" class="btn btn-icon btn-sm btn-hover-light-primary mr-1" data-card-tool="toggle" data-toggle="tooltip" data-placement="top" title="" data-original-title="Toggle Card">
								<i class="ki ki-arrow-down icon-nm"></i>
							</a>
						</div>
					</div>
					<div class="card-body" kt-hidden-height="357" style="">
						<table class="table table-bordered">
						    <thead>
						        <tr>
						            <th scope="col">#</th>
						            <th scope="col">Food</th>
						            <th scope="col">Calories</th>
						        </tr>
						    </thead>
						    <tbody>
						    	<?php $lunchCal = 0; $lunchIndex = 0;?>
						    	@foreach($nutritions->where("type", 1) as  $item)
						    	<?php $lunchCal+= $item->cal; $lunchIndex++; ?>
						        <tr>
						            <th scope="row">{{ $lunchIndex}}</th>
						            <td>{{$item->food}}</td>
						            <td>{{$item->cal}}</td>
						        </tr>
						        @endforeach
					     	 	<tr>
						            <th scope="row"></th>
						            <th>Total</th>
						            <th>{{$lunchCal}}</th>
						        </tr>
						    </tbody>
						</table>
					</div>
				</div>
				<br>

				<!-- Dinner -->
				<div class="card card-custom" data-card="true" id="kt_card_1">
					<div class="card-header">
						<div class="card-title">
							<h3 class="card-label">Dinner</h3>
						</div>
						<div class="card-toolbar">
							<a href="#" class="btn btn-icon btn-sm btn-hover-light-primary mr-1" data-card-tool="toggle" data-toggle="tooltip" data-placement="top" title="" data-original-title="Toggle Card">
								<i class="ki ki-arrow-down icon-nm"></i>
							</a>
						</div>
					</div>
					<div class="card-body" kt-hidden-height="357" style="">
						<table class="table table-bordered">
						    <thead>
						        <tr>
						            <th scope="col">#</th>
						            <th scope="col">Food</th>
						            <th scope="col">Calories</th>
						        </tr>
						    </thead>
						    <tbody>
						    	<?php $dinnerCal = 0; $dinnerIndex=0;?>
						    	@foreach($nutritions->where("type", 2) as  $item)
						    	<?php $dinnerCal+= $item->cal; $dinnerIndex++;?>
						        <tr>
						            <th scope="row">{{$dinnerIndex}}</th>
						            <td>{{$item->food}}</td>
						            <td>{{$item->cal}}</td>
						        </tr>
						        @endforeach
					  			<tr>
						            <th scope="row"></th>
						            <th>Total</th>
						            <th>{{$dinnerCal}}</th>
						        </tr>	     	
						    </tbody>
						</table>
					</div>
					
				</div>
				<br>


				<!-- Dessert -->
				<div class="card card-custom" data-card="true" id="kt_card_1">
					<div class="card-header">
						<div class="card-title">
							<h3 class="card-label">Dessert</h3>
						</div>
						<div class="card-toolbar">
							<a href="#" class="btn btn-icon btn-sm btn-hover-light-primary mr-1" data-card-tool="toggle" data-toggle="tooltip" data-placement="top" title="" data-original-title="Toggle Card">
								<i class="ki ki-arrow-down icon-nm"></i>
							</a>
						</div>
					</div>
					<div class="card-body" kt-hidden-height="357" style="">
						<table class="table table-bordered">
						    <thead>
						        <tr>
						            <th scope="col">#</th>
						            <th scope="col">Food</th>
						            <th scope="col">Calories</th>
						        </tr>
						    </thead>
						    <tbody>
						    	<?php $dessertCal = 0;  $dessertIndex = 0;?>
						    	@foreach($nutritions->where("type", 3) as  $item)
						    	<?php $dessertCal+= $item->cal;  $dessertIndex++;?>
						        <tr>
						            <th scope="row">{{$dessertIndex}}</th>
						            <td>{{$item->food}}</td>
						            <td>{{$item->cal}}</td>
						        </tr>
						        @endforeach
					           <tr>
						            <th scope="row"></th>
						            <th>Total</th>
						            <th>{{$dessertCal}}</th>
						        </tr>
						     	
						    </tbody>
						</table>
					</div>
				</div>
				<br>


				<!-- Snacks -->
				<div class="card card-custom" data-card="true" id="kt_card_1">
					<div class="card-header">
						<div class="card-title">
							<h3 class="card-label">Snacks</h3>
						</div>
						<div class="card-toolbar">
							<a href="#" class="btn btn-icon btn-sm btn-hover-light-primary mr-1" data-card-tool="toggle" data-toggle="tooltip" data-placement="top" title="" data-original-title="Toggle Card">
								<i class="ki ki-arrow-down icon-nm"></i>
							</a>
						</div>
					</div>
					<div class="card-body" kt-hidden-height="357" style="">
						<table class="table table-bordered">
						    <thead>
						        <tr>
						            <th scope="col">#</th>
						            <th scope="col">Food</th>
						            <th scope="col">Calories</th>
						        </tr>
						    </thead>
						    <tbody>
						    	<?php $snaksCal = 0; $snackIndex=0;?>
						    	@foreach($nutritions->where("type", 4) as  $item)
						    	<?php $snaksCal+= $item->cal; $snackIndex++;?>
						        <tr>
						            <th scope="row">{{$snackIndex}}</th>
						            <td>{{$item->food}}</td>
						            <td>{{$item->cal}}</td>
						        </tr>
						        @endforeach
					           <tr>
						            <th scope="row"></th>
						            <th>Total</th>
						            <th>{{$snaksCal}}</th>
						        </tr>
						     	
						    </tbody>
						</table>
					</div>
				</div>
				<br>

				<!-- Fluids -->
				<div class="card card-custom" data-card="true" id="kt_card_1">
					<div class="card-header">
						<div class="card-title">
							<h3 class="card-label">Fluids</h3>
						</div>
						<div class="card-toolbar">
							<a href="#" class="btn btn-icon btn-sm btn-hover-light-primary mr-1" data-card-tool="toggle" data-toggle="tooltip" data-placement="top" title="" data-original-title="Toggle Card">
								<i class="ki ki-arrow-down icon-nm"></i>
							</a>
						</div>
					</div>
					<div class="card-body" kt-hidden-height="357" style="">
				    	<?php $fuilds = $nutritions->where('type', 5); $fluidCal = 0; $fluidIndex = 0;?>
						<table class="table table-bordered">
						    <thead>
						        <tr>
						            <th scope="col">#</th>
						            <th scope="col">Food</th>
						            <th scope="col">Calories</th>
						        </tr>
						    </thead>
						    <tbody>
						    	<?php ?>
						    	@foreach($fuilds as $item)
						    	<?php $fluidCal+= $item->cal; $fluidIndex++;?>
						        <tr>
						            <th scope="row">{{$fluidIndex}}</th>
						            <td>{{$item->food}}</td>
						            <td>{{$item->cal}}</td>
						        </tr>
						        @endforeach
					           <tr>
						            <th scope="row"></th>
						            <th>Total</th>
						            <th>{{$fluidCal}}</th>
						        </tr>
						     	
						    </tbody>
						</table>
						
					</div>
				</div>
				<br>
			</div>
		</div>
	</div>
	<div class="card-footer pb-0">
		<b>Total Calories: {{$breakfastCal + $lunchCal + $dinnerCal + $dessertCal + $snaksCal + $fluidCal}}</b>
	</div>
</div>

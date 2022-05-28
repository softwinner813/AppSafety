@include('pages.users.details._tab_header')

<div class="card-body">
	<form class="form" id="kt_form">
		<div class="tab-content">
			@include('pages.users.details._nutrition_data')

			@include('pages.users.details._sleep_data')

			@include('pages.users.details._physical_data')

			@include('pages.users.details._spiritual_data')
		</div>
	</form>
</div>
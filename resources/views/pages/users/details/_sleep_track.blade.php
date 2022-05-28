<!--begin::Tab-->
<div class="tab-pane px-7" id="kt_user_edit_tab_2" role="tabpanel">
	<!--begin::Body-->
	<div class="card-body">
		<!--begin::Row-->
		<div class="row">
			<div class="col-xl-2"></div>
			<div class="col-xl-7">
				<div class="d-flex align-items-center flex-grow-1">
					<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
						<div class="d-flex flex-column align-items-cente py-2 w-75">
							<a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Breakfast:</a>
						</div>
						@if($user->SleepTrack->breakfast == 1)
						<span class="switch">
							<label>
								<input type="checkbox" checked="checked" name="select">
								<span></span>
							</label>
						</span>
						@else 
						<span class="switch">
							<label>
								<input type="checkbox" name="select">
								<span></span>
							</label>
						</span>
						@endif
					</div>
				</div>
			</div>
		</div>
		<!--end::Row-->
	</div>
	<!--end::Body-->
	<!--begin::Footer-->
	<div class="card-footer pb-0">
		<!-- <div class="row">
			<div class="col-xl-2"></div>
			<div class="col-xl-7">
				<div class="row">
					<div class="col-3"></div>
					<div class="col-9">
						<a href="#" class="btn btn-light-primary font-weight-bold">Save changes</a>
						<a href="#" class="btn btn-clean font-weight-bold">Cancel</a>
					</div>
				</div>
			</div>
		</div> -->
	</div>
	<!--end::Footer-->
</div>
<!--end::Tab-->
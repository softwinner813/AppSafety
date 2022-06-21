
				<div class="col-md-2 col-xs-3"  style="height: calc(100% + 20px); overflow-y: auto;">
					<div class="col-md-12 mt-10">
						<h4 class="tool-title">TOOLBOX &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a title="setting" class="btn btn-sm btn-icon btn-bg-transparent btn-icon-primary btn-hover-primary" href="javascript;">
								<i class="flaticon2-gear"></i>
							</a>
						</h4>
						<div class="sidebar_group">
						    <div class="menu-fields">

						    	<!-- FontSize  -->
						      	<div class="menu-item">
						      		<div class="d-flex align-items-center">
						      			<i class="fas fa-text-height" style="color: #333; "></i>&nbsp;&nbsp;&nbsp;  <strong>Font Size</strong>
							      		&nbsp;&nbsp;
							      		<select id="font-size" class="form-control form-control-sm" style="width: 60px;">
											<option value="10">10</option>
											<option value="12">12</option>
											<option value="16">16</option>
											<option value="18">18</option>
											<option value="24" selected>24</option>
											<option value="32">32</option>
											<option value="48">48</option>
											<option value="64">64</option>
											<option value="72">72</option>
											<option value="108">108</option>
											<!-- FDSFDS -->
										</select>
						      		</div>
						      	</div>

						    	<!-- Colors -->
						    	<div class="d-flex justify-content-between" style="padding-left: 16px;" >
				      				<button class="color-tool active" style="background-color: #212121;"></button>
									<button class="color-tool" style="background-color: red;"></button>
									<button class="color-tool" style="background-color: blue;"></button>
									<button class="color-tool" style="background-color: green;"></button>
									<button class="color-tool" style="background-color: yellow;"></button>
						    	</div>


						    	<!-- Standard Fields -->
						    	<div id="standard-field">
							    	<hr>
							    	<h6 style="font-weight: bold;">Standard Fields</h6>
							    	<!-- Draw Signature -->
							      	<div class="menu-item py-0">
							      		<div class="d-flex align-items-center">
									    	<i class="fas fa-pencil-alt" style="color: #333; "></i>&nbsp;&nbsp;&nbsp;&nbsp; 
									    	<div class="btn-group btn-group-sm" style="width: 100%;">
												<div type="button" class="btn btn-light btn-sm" style="border: 1px solid #c1c1c1;"><img src="" id="selected_sign" height="20"></div>
												<button type="button" class="btn btn-light btn-sm addSignBtn" id="addSignBtnTitle"  data-toggle="modal" data-target="#signModal">Add Signature</button>
											  	<button type="button" class="btn btn-secondary btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											        <span class="sr-only"></span>
										    	</button>
									    		<div class="dropdown-menu " id="sign-dropdown">
										      		<div class="dropdown-item">
										      			<button id="addSignBtn" class=" addSignBtn btn-outline-primary btn btn-sm" data-toggle="modal" data-target="#signModal"><i class="fas fa-pencil-alt"></i> Add Signature</button>
										      		</div>
										    	</div>
											</div>
							      		</div>
									</div>

			                        @auth
									<!-- Text Signature -->
							      	<div class="menu-item" onclick="enableAddText(event, `{{Auth::user()->name}}`, true);">
							      		<div class="d-flex align-items-center">
							      			<i class="icon-xl la la-signature" style="color: #333; "></i>&nbsp;&nbsp; <strong> Text Sign</strong>
							      		</div>
							      	</div>
							      	@else
							      	<div class="menu-item" onclick="enableAddText(event, `Full Name`, true);">
							      		<div class="d-flex align-items-center">
							      			<i class="icon-xl la la-signature" style="color: #333; "></i>&nbsp;&nbsp; <strong> Text Sign</strong>
							      		</div>
							      	</div>
							      	@endauth
							      	<!-- Date Signature -->
							      	<div class="menu-item" onclick="enableAddText(event, `{{date('m/d/Y')}}`);">
							      		<div class="d-flex align-items-center">
							      			<i class="far fa-calendar" style="color: #333; "></i>&nbsp;&nbsp;&nbsp;&nbsp; <strong>Date Signed</strong>
							      		</div>
							      	</div>

							      	@auth
							      	<!-- Name Signature -->
							      	<div class="menu-item" onclick="enableAddText(event, `{{Auth::user()->name}}`);">
							      		<div class="d-flex align-items-center">
							      			<i class="fas fa-user" style="color: #333; "></i>&nbsp;&nbsp;&nbsp;&nbsp;  <strong>Name</strong>
							      		</div>
							      	</div>
							      	@else
							      	<div class="menu-item" onclick="enableAddText(event, `Full Name`, true);">
							      		<div class="d-flex align-items-center">
							      			<i class="fas fa-user" style="color: #333; "></i>&nbsp;&nbsp; <strong> Text Sign</strong>
							      		</div>
							      	</div>
							      	@endauth

							      	@auth
							      	<!-- Company Signature -->
							      	<div class="menu-item" onclick="enableAddText(event, `{{Auth::user()->name}}`);">
							      		<div class="d-flex align-items-center">
							      			<i class="fas fa-building" style="color: #333; "></i>&nbsp;&nbsp;&nbsp;&nbsp;  <strong>Company</strong>
							      		</div>
							      	</div>
							      	@else
							      	<div class="menu-item" onclick="enableAddText(event, `Company Name`, true);">
							      		<div class="d-flex align-items-center">
							      			<i class="fas fa-building" style="color: #333; "></i>&nbsp;&nbsp; <strong> Company</strong>
							      		</div>
							      	</div>
							      	@endauth

							      	<!-- Text  -->
							      	<div class="menu-item" onclick="enableAddText(event, `Some Text`);">
							      		<div class="d-flex align-items-center">
							      			<i class="fas fa-font" style="color: #333; "></i>&nbsp;&nbsp;&nbsp;  <strong>Text</strong>
							      		</div>
							      	</div>

							      	<!-- Arraw  -->
							      	<div class="menu-item" onclick="enableAddArrow(event);">
							      		<div class="d-flex align-items-center">
							      			<i class="fas fa-arrow-left" style="color: #333; "></i>&nbsp;&nbsp;&nbsp;  <strong>Arraw</strong>
							      		</div>
							      	</div>
						    	</div>
						      	
						      	<!-- Fill-Form-Fields -->
						      	<div id="fill-form-field" style="display: none;">
							      	<hr>
							    	<h6 style="font-weight: bold;">Fill Form Fields</h6>
							    	<!-- Text Signature -->
							      	<div class="menu-item" onclick="enableAddText(event, `Full Name`, true);">
							      		<div class="d-flex align-items-center">
							      			<i class="icon-xl la la-signature" style="color: #333; "></i>&nbsp;&nbsp; <strong> Signature</strong>
							      		</div>
							      	</div>

							      	<!-- Date Signature -->
							      	<div class="menu-item" onclick="enableAddText(event, `MM/DD/YYYY`);">
							      		<div class="d-flex align-items-center">
							      			<i class="far fa-calendar" style="color: #333; "></i>&nbsp;&nbsp;&nbsp;&nbsp; <strong>Date Signed</strong>
							      		</div>
							      	</div>

							      	<!-- Text  -->
							      	<div class="menu-item" onclick="enableAddText(event, `Some Text`);">
							      		<div class="d-flex align-items-center">
							      			<i class="fas fa-font" style="color: #333; "></i>&nbsp;&nbsp;&nbsp;&nbsp;  <strong>Text</strong>
							      		</div>
							      	</div>
						      	</div>
									
						    </div>
						</div>
					</div>
				</div>

<!-- Sign Modal -->
<div class="modal fade" id="signModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Create New Signature</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
					<div class="example mb-10">
						<div class="example-preview p-3">
							<ul class="nav nav-tabs" id="myTab1" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="home-tab-1" data-toggle="tab" href="#home-1">
										<span class="nav-icon">
											<i class="fas fa-pencil-alt"></i>
										</span>
										<span class="nav-text">Draw</span>
									</a>
								</li>
								<!-- <li class="nav-item">
									<a class="nav-link" id="profile-tab-1" data-toggle="tab" href="#profile-1" aria-controls="profile">
										<span class="nav-icon">
											<i class="fas fa-font"></i>
										</span>
										<span class="nav-text">Type</span>
									</a>
								</li> -->
							</ul>
							<div class="tab-content mt-5" id="myTabContent1">
								<div class="tab-pane fade show active" id="home-1" role="tabpanel" aria-labelledby="home-tab-1">

	        				<canvas id="drawCanvans" width="400"	></canvas>
									
									<div class="form-group row">
										<div class="col-6 col-form-label">
											<div class="radio-inline">
												<label class="radio radio-accent radio-dark">
												<input type="radio" name="radios18" checked="checked" onchange="changeColor('#0d0d0d');" />
												<span></span></label>
												<label class="radio radio-accent radio-primary">
												<input type="radio" name="radios18"  onchange="changeColor('#2f53b0');"/>
												<span></span></label>
												<label class="radio radio-accent radio-danger">
												<input type="radio" name="radios18" onchange="changeColor('#bf0a0a');"/>
												<span></span></label>
											</div>
										</div>
										<div class="col-6">
											<button class="btn btn-secondary btn-sm float-right ml-2" id="clear"><i class="fas fa-trash-alt"></i> Clear</button>
											<button class="btn btn-secondary btn-sm float-right" id="undo">
												<span class="svg-icon svg-icon-secondary svg-icon-sm"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Communication\Reply.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											        <rect x="0" y="0" width="24" height="24"/>
											        <path d="M21.4451171,17.7910156 C21.4451171,16.9707031 21.6208984,13.7333984 19.0671874,11.1650391 C17.3484374,9.43652344 14.7761718,9.13671875 11.6999999,9 L11.6999999,4.69307548 C11.6999999,4.27886191 11.3642135,3.94307548 10.9499999,3.94307548 C10.7636897,3.94307548 10.584049,4.01242035 10.4460626,4.13760526 L3.30599678,10.6152626 C2.99921905,10.8935795 2.976147,11.3678924 3.2544639,11.6746702 C3.26907199,11.6907721 3.28437331,11.7062312 3.30032452,11.7210037 L10.4403903,18.333467 C10.7442966,18.6149166 11.2188212,18.596712 11.5002708,18.2928057 C11.628669,18.1541628 11.6999999,17.9721616 11.6999999,17.7831961 L11.6999999,13.5 C13.6531249,13.5537109 15.0443703,13.6779456 16.3083984,14.0800781 C18.1284272,14.6590944 19.5349747,16.3018455 20.5280411,19.0083314 L20.5280247,19.0083374 C20.6363903,19.3036749 20.9175496,19.5 21.2321404,19.5 L21.4499999,19.5 C21.4499999,19.0068359 21.4451171,18.2255859 21.4451171,17.7910156 Z" fill="#000000" fill-rule="nonzero"/>
											    </g>
											</svg><!--end::Svg Icon--></span>
 										Undo</button>

										</div>
									</div>
								</div>
								<!-- <div class="tab-pane fade" id="profile-1" role="tabpanel" aria-labelledby="profile-tab-1">
									
								</div> -->
							</div>
						</div>
					</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
	        <button type="submit" class="btn btn-primary" id="saveSignBtn"><i class="fas fa-save"></i>&nbsp;CREATE</button>
	      </div>
    	</form>
    </div>
  </div>
</div>
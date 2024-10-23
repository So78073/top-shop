<div class="am-mainpanel">
	<div class="am-pagebody">

		<div class="card">
				  	<div class="card-body p-4">
					  	<h5 class="card-title">Createn New Section</h5>
					  	<hr/>
					    <div class="row">
					   		<div class="col-lg-12">
								<div class="col-md-12">
									<div id="smartwizard">
										<ul class="nav">
											<li class="nav-item">
												<a class="nav-link" href="#step-1">	<strong>Step 1</strong> 
													<br></a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="#step-2">	<strong>Step 2</strong> 
													<br></a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="#step-3">	<strong>Step 3</strong> 
													<br></a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="#step-4">	<strong>Step 4</strong> 
													<br></a>
											</li>
										</ul>
										<div class="tab-content">
											<form class="" id="wizrdForm">
												<input type="hidden" id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
												<div style="min-height: 500px !important;" id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
													<div class="p-5">
														<h4>Step 1</h4>
														<h5 class="pt-2 pb-4">Basics Setup to start configuration of your new section.</h5>
														<div class="row">
															<div class="col-sm">
																<label class="pb-2 text-white" for="sectionname">Section Name<br/>
																	<small>Set the section name, use only letters.</small>
																</label>
																<input type="text" id="sectionname" name="sectionname" class="form-control">
															</div>
															<div class="col-sm">
																<label class="pb-2 text-white" for="sectionicone">Icon<br/>
																	<small>Choose section Icon, this will applied to aside menu.</small>
																</label>
																<select class="iconselect select-single" id="sectionicone" name="sectionicone" tabindex="-1" aria-hidden="true">
																</select>
															</div>
															<div class="col-sm">
																<label class="pb-2 text-white" for="sectionstyle">Section Style<br/>
																<small>Style Table or Style Products with images.</small></label>
																<select class="select2" name="sectionstyle">
																	<option value="1">Tables style</option>
																	<option value="2">Cards style</option>
																</select>
															</div>
															<div class="col-sm">
																<label class="pb-2 text-white" for="resell">Selling Type<br/>
																<small>Choose if products will be able to be selled multiple times.</small></label>
																<select class="select2" name="resell">
																	<option value="0">One Time Sell</option>
																	<option value="1">Multi Sells</option>
																</select>
															</div>	
														</div>
														<div class="row">
															<div class="col-md-12 pt-3">
																<div class="form-group">
																	<label class="text-white">Set your section message, this message will appear in the top of the section</label>
																	<textarea class="sectionmessage" id="sectionmessage" name="sectionmessage"></textarea>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div style="min-height: 500px !important;" id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">
													<div class="p-5" style="height:500px; position: relative;" id="s2c">
														<h4>Step 2</h4>
														<h5 class="pt-2 pb-4">Setup the database table fields for the new section.</h5>
														<div class="row">
															<div class="col-md-12 mb-3">
																<button type="button" id="" class="btn btn-outline-success btn-sm float-end" data-api="addfield">Add New <i class="bx bx-plus"></i></button>
															</div>
															<div class="col-sm">
																<label class="pb-2 text-white" for="fieldsnames">Field name</label>
																<input type="text" id="fieldsnames" name="fields[0][fieldsNames][]" class="form-control">
															</div>
															<div class="col-sm">
																<label class="pb-2 text-white" for="fieldstypes">Field Type</label>
																<select class="form-select" id="fieldstypes" name="fields[0][fieldTypes][]" tabindex="-1" aria-hidden="true">
																	<option value="int">Integer</option>
																	<option value="varchar">Varchar</option>
																	<option value="date">Date</option>
																	<option value="datetime">DateTime</option>
																	<option value="longtext">Long text</option>
																</select>
															</div>
															<div class="col-sm">
																<label class="pb-2 text-white" for="fieldssize">Field Size</label>
																<input type="number" id="fieldssize" name="fields[0][fieldSsize][]" class="form-control">
															</div>
															<div class="col-sm">
																<label class="pb-2 text-white" for="fieldsempty">Empty by Default</label>
																<div class="input-group">
																	<select class="form-select" id="fieldsempty" name="fields[0][fieldsempty][]" tabindex="-1" aria-hidden="true">
																		<option value="1">No</option>
																		<option value="0">Yes</option>
																	</select>
																	
																</div>
															</div>
														</div>
														<div id="fieldrecept"  style="max-height: 250px overflow: scroll !important;">
														</div>
													</div>
												</div>
												<div style="min-height: 500px !important;" id="step-3" style="min-height: 500px;" class="tab-pane" role="tabpanel" aria-labelledby="step-3">
													<div class="p-5" style="height:500px; position: relative;" id="s3c">
														<h4>Step 3</h4>
														<h5 class="pt-2 pb-4">Setup the form input fields for creation products.</h5>
														<div id="s3">

														</div>
														<div id="defaults">
															
														</div>
														<div id="fieldrecept"  style="max-height: 250px overflow: scroll !important;">
														</div>

													</div>
												</div>
												<div style="min-height: 500px !important;" id="step-4" style="min-height: 500px;" class="tab-pane" role="tabpanel" aria-labelledby="step-4">
													<div class="p-5" style="height:500px; position: relative;" id="s4c">
														<h3>Step 4 Content</h3>
														<h5 class="pt-2 pb-4">Setup how the section and items will be shown.</h5>
														<table class="table table-bordered">
															<thead>
																<tr id="thTable">
																</tr>
															</thead>
															<tbody>
																<tr id="tbodyTable">
																</tr>
																<tr id="tblabels">
																</tr>
															</tbody>
														</table>
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>
						   	</div>
					   	</div>
				  	</div>
			  	</div>
	</div><!-- am-pagebody -->
</div><!-- am-mainpanel -->
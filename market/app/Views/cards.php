<?php

	function doSelect($field, $allcards){
		$doArray = [];
		foreach ($allcards as $key => $value) {
			if($value[$field] !== "" && $value[$field] !== "N/A"){
				if($field == 'number'){
					$number = substr($value[$field],0,6);
					$doArray[] = $number;	
				}
				else {
					$doArray[] = $value[$field];	
				}
			}
		}
		$doArrayUnic = array_unique($doArray);
		foreach ($doArrayUnic as $ke => $valu) {
			if($valu !== 'N/A' &&  $valu !== ''){
				echo '<option value="'.esc($valu).'">'.ucfirst(esc($valu)).'</option>';
			}
			
		}
	}

?>
	<div class="am-mainpanel">
		<div class="am-pagebody">
			<div class="card">
				<div id="filtrDiv" class="card-body">
					<form id="Filter">
						<div class="row">
							<div class="col-md-6">
								<div class="row">
									<div class="form-group mt-2 col-md-4">
										<label class="form-label">Search By Base</label>
										<select class="form-control select2" name="base" id="base">
											<option selected value="all">All Bases</option>
											<?php	
												echo doSelect('base', $allcards);
											?>
										</select>
										<small class="base text-red"></small>
									</div>
									<div class="form-group mt-2 col-md-4">
										<label class="form-label">Search By Countries</label>
										<select class="form-control select2" name="country" id="country" data-api="GetState">
											<option selected value="all">All Countries</option>
											<?php
												echo doSelect('country', $allcards);
											?>
										</select>

										<small class="country text-red"></small>
									</div>
									<div class="form-group mt-2 col-md-4">
										<label class="form-label">Search by state</label>
										<select class="form-control select2" name="state" id="state" disabled data-api="GetCitys">
											<option selected value="all">Select Country first</option>

										</select>
										<small class="state text-red"></small>
									</div>
									<div class="form-group mt-2 col-md-4">
										<label class="form-label">Search by City</label>
										<select class="form-control select2" name="city" id="city" disabled>
											<option selected value="all">Select State first</option>
										</select>
										<small class="city text-red"></small>
									</div>
									<div class="form-group mt-2 col-md-4">
										<label class="form-label">Search by Type</label>
										<select class="form-control select2" name="type" id="type">
											<option selected value="all">All</option>
											<?php
												echo doSelect('type', $allcards);
											?>
										</select>
										<small class="type text-red"></small>
									</div>
									<div class="form-group mt-2 col-md-4">
										<label class="form-label">Search by Brands</label>
										<select class="form-control select2" name="scheme" id="scheme">
											<option selected value="all">All</option>
											<?php
												echo doSelect('scheme', $allcards);
											?>
										</select>
										<small class="scheme text-red"></small>
									</div>
									<div class="form-group mt-2 col-md-4">
										<label class="form-label">Search By ZIP</label>
										<select class="form-control" name="zip" id="zip">
											<option selected value="all">All</option>
											<?php
												echo doSelect('zip', $allcards);
											?>
										</select>
										<small class="zip text-red"></small>
									</div>
									<div class="form-group mt-2 col-md-4">
										<label class="form-label">Search By Bank Name</label>
										<select class="form-control" name="bank" id="bank">
											<option selected value="all">All</option>
											<?php
												echo doSelect('bank', $allcards);
											?>
										</select>
										<small class="bank text-red"></small>
									</div>
									<div class="form-group mt-2 col-md-4">
										<label class="form-label">Refundable/Non Refundable</label>
										<select class="form-control" name="refun" id="refun">
											<option selected value="all">All</option>
											<option value="1">Yes</option>
											<option value="0">No</option>
										</select>
										<small class="refun text-red"></small>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group mt-2">
									<label class="form-label">Search By Price Range</label>
									<div class="d-grid">
										<div class="btn btn-light">
											<input type="text" class="form-range" name="pricerange" id="pricerange">
										</div>
										<small class="pricerange text-red"></small>
									</div>
								</div>
								<div class="form-group mt-2">
									<label class="form-label">Search By BIN</label>
									<textarea class="form-control" name="number" id="number" style="min-height:100px"></textarea>
									<small class="number text-red"></small>
								</div>	
							</div>
							<div class="col-md-12">
								<div class="d-grid">
									<div class="form-group d-grid mt-3">
										<button type="button" data-api="dosearche" class="btn btn-teal"><i class="bx bx-search"></i> Search</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="card mt-1 mb-1 d-none">
				<div class="card-body" id="addtocartdiv">
				</div>
			</div>

			<div class="card">
				<div class="card-body">
					<table id="TabelStock" class="table table-striped table-bordered" style="width:100%">
						<thead>
							<tr>
							    <th style="max-width: 1% !important; vertical-align: middle !important;">
							    	<?php  /**if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '1'){**/ ?>
							    		<!--<input type="checkbox" id="selectall" data-api="toggleSelect">-->
							    	<?php /**} 
							    	else {**/ ?>
							    	    <input type="checkbox" id="chekall"> 
							    	<?php /** } **/ ?>
							    </th>
						    	<th>Base</th>
						    	<th>BIN</th>
						    	<th>Exp</th>
						    	<th>Level</th>
						    	<th>Bank/Countries</th>
						    	<th>Address/Phone/DOB/Email/SSN</th>
						    	<th>City</th>
						    	<th>State</th>
						    	<th>Zip</th>
						    	<th>Refundable</th>
					     		<th style="max-width: 10% !important; vertical-align: middle !important;"  data-priority="1"> Price</th>   
							<?php
							    if(session()->get("suser_groupe") == "9"){ ?>
							    <th style="max-width: 10% !important; vertical-align: middle !important;" data-priority="1">Tools</th>    
							<?php }
							    else { ?>
							    <th style="max-width: 10% !important; vertical-align: middle !important;" data-priority="1">Add To Cart</th>     
							<?php } ?>
							</tr>
						</thead>
					</table>
					<input type="hidden" id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
				</div>
			</div>		
		</div>
	</div>

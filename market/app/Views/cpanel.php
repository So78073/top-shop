<?php

	/**function doSelect($field, $allcards){
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
	}**/

?>
	<div class="am-mainpanel">
		<div class="am-pagebody">
			
			<div class="card mt-1 mb-1 d-none addtocartmain">
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
							    	    <input type="checkbox" id="chekall-cpanel"> 
							    	<?php /** } **/ ?>
							    </th>
						    	<th>Host</th>
						    	<th>Protocol</th>
						    	<th>TLD</th>
						    	<th>Hoster</th>
						    	<th>Country</th>
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

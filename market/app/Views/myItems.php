<div class="am-mainpanel">
	<div class="am-pagebody">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12 mb-3">
		    			<div class="btn-group float-end">
			    			<a href="<?php echo base_url() ?>/myItems/downloadccToday" class="btn btn-success btn-sm"><i class="bx bx-download"></i>Download Today</a>
			    			<a href="<?php echo base_url() ?>/myItems/downloadAll" class="btn btn-info btn-sm"><i class="bx bx-download"></i>Download All</a>
			    		</div>
			    	</div>
				   	<div class="col-md-12">
	                    <table id="TabelStock" class="table table-hover" style="width:100%">
							<thead>
								<tr>
								    <th>ID</th>
								    <th>Type</th>
								    <th>Get Details</th>
								    <th>Details</th>
								    <th>Check</th>
								    <th style="max-width: 10% !important;">Purchase date</th> 
								</tr>
							</thead>
						</table>
						<input type="hidden" id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
				   	</div>
			   </div>
			</div>
		</div>
	</div><!-- am-pagebody -->
</div><!-- am-mainpanel -->

<div class="am-mainpanel">
	<div class="am-pagebody">
		<div class="card mb-2">
			<div class="card-body">
				<div class="pd-0 mg-t-0">
				    <ul class=" bg-purple nav nav-pills nav-pills-for-dark flex-column flex-md-row" role="tablist">
				        <?php echo $activesections; ?>
				    </ul>
				</div>
			</div>
		</div>	
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12" id="prgs">
					</div>
					<div class="col-md-12 mb-3">
						<div class="btn-group float-start" id="checkplace">

						</div>
		    			<div class="btn-group float-end">
			    			<a href="<?php echo base_url() ?>/myorders/downloadccToday" class="btn btn-success btn-sm"><i class="bx bx-download"></i> Download Today</a>
			    			<a href="<?php echo base_url() ?>/myorders/downloadAll" class="btn btn-info btn-sm"><i class="bx bx-download"></i> Download All</a>
			    			<a href="javascript:void(0)" class="btn btn-danger btn-sm" data-api="InitemptyItems"><i class="bx bx-trash"></i> Delete All</a>
			    		</div>
			    	</div>
				   	<div class="col-md-12">
	                    <?php echo $tableheaderHtml ?>
						<input type="hidden" id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
				   	</div>
			   </div>
			</div>
		</div>
	</div><!-- am-pagebody -->
</div><!-- am-mainpanel -->


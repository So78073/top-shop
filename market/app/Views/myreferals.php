<div class="am-mainpanel">
	<div class="am-pagebody">
		<div class="row mb-3">
			<div class="col-md-3">
				<div class="card">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div>
								<p class="mb-0">Total of your referals</p>
								<h4 class="my-1"><?php echo str_pad(session()->get("suser_referals_count"), 5, '0', STR_PAD_LEFT)  ?></h4>
							</div>
							<div class="ms-auto font-35 text-white"><i class="bx bx-user-voice"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div class="card">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div>
								<p class="mb-0">Your Referal Link</p>
								<h6 class="my-1 text-danger"><?php echo base_url().'/signup?r='.session()->get("suser_referal_code") ?></h6>
							</div>
							<div class="ms-auto font-35 text-white"><i class="bx bx-link"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 mt-2 mb-3">
				<div class="card border-top border-0 border-2 border-danger radius-10">
					<div class="card-body">
						<table id="TabelStock" class="table table-striped table-bordered" style="width:100%">
							<thead>
								<tr>
							    	<th>User</th>
							    	<th>Date</th>
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
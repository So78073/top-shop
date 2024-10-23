<div class="am-mainpanel">
	<div class="am-pagebody">
		<div class="row">
			<div class="col-lg-8">
				<div class="card">
					<div class="card-body">
						<table id="TabelStock" class="table table-hover" style="width:100%">
							<thead>
								<tr>
									<th>Item</th>
									<th>Type</th>
									<th>Price</th>
									<th>Remove</th>  
								</tr>
							</thead>
						</table>
					</div>
				</div>
						
			</div>
			<div class="col-md-4">
				<div class="card">
					<div class="card-body">
						<div class="card radius-10 bg-danger bg-gradient">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div>
										<h5 class="mb-0 text-white">Total</h5>
										<h4 class="my-1 text-white" id="sum"><?php echo $total ?></h4>
									</div>
									
								</div>
							</div>
						</div>
						<div class="d-grid gap-2">
							<input type="hidden" id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
							<button type="button" class="btn btn-success"  data-api="buyinit">Checkout <span class="lni lni-checkmark"></span></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div><!-- am-pagebody -->
</div><!-- am-mainpanel -->


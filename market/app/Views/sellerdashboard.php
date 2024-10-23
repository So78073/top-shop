<div class="am-mainpanel">
	<div class="am-pagebody">
		<div class="card mb-4">
			<div class="card-body">
				<div class="pd-0 mg-t-0">
				    <ul class=" bg-purple nav nav-pills nav-pills-for-dark flex-column flex-md-row" role="tablist">
				    	<?php echo $navigation ?>
				    </ul>
				</div>
			</div>
		</div>
		<div class="card mb-2">
			<div class="card-body">
				<div class="row ">
					<div class="col-md-3 ">
						<div class="card mb-0">
							<div class="card-body bg-indigo">
								<div class="d-flex align-items-center text-white">
									<div>
										<p class="mb-0">My Income</p>
										<h4 class="my-1" id="ttstok">$<?php echo number_format(session()->get('suser_seller_balance'), 2, '.', '') ?></h4>
									</div>
									<div class="ms-auto text-white" style="font-size: 35px;"><i class="bx bx-dollar"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="card mb-0">
							<div class="card-body bg-info">
								<div class="d-flex align-items-center text-white">
									<div>
										<p class="mb-0">Total <?php echo $statistics['sectionLable'] ?> in Stock</p>
										<h4 class="my-1" id="ttstok"><?php echo $statistics['stok'] ?></h4>
									</div>
									<div class="ms-auto text-white" style="font-size: 35px;"><i class="bx bx-package"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="card mb-0">
							<div class="card-body bg-success">
								<div class="d-flex align-items-center text-white">
									<div>
										<p class="mb-0">Total <?php echo $statistics['sectionLable'] ?> Selled</p>
										<h4 class="my-1" id="ttstok"><?php echo $statistics['selled'] ?></h4>
									</div>
									<div class="ms-auto text-white" style="font-size: 35px;"><i class="bx bx-check"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="card mb-0">
							<div class="card-body bg-danger">
								<div class="d-flex align-items-center text-white">
									<div>
										<p class="mb-0">Total <?php echo $statistics['sectionLable'] ?> Refunded</p>
										<h4 class="my-1" id="ttstok"><?php echo $statistics['refunded'] ?></h4>
									</div>
									<div class="ms-auto text-white" style="font-size: 35px;"><i class="bx bx-reply"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card mb-2">
			<?php echo $tools; ?>
		</div>
		<div class="card">
			<div class="card-body">
				<div class="row">
				   	<div class="col-md-12">
	                    <?php echo $TableHeaders ?>
						<input type="hidden" id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
				   	</div>
			   </div>
			</div>
		</div>
	</div><!-- am-pagebody -->
</div><!-- am-mainpanel -->

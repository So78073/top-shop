	<div class="am-mainpanel">
		<div class="am-pagebody">
			<div class="card">
				<div class="card-body">
					<div class="row">
						<input type="hidden"  id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
						<div class="col-md-12">
							<div class="row d-flex justify-content-center shadow-2">
								<div class="col-md-5 text-center lead pd-30 bg-primary tx-white mg-b-0">
									<h4>Welcome <?php echo ucfirst(session()->get('suser_username')); ?></h4>
									<h6 class="pt-2">Your account has not been activated yet.</h6>
									<h6 class="pt-2">Please not that you have to deposit at lease $<?php echo $settings[0]['mindepo'] ?> to activate your account.</h6>
									<h6 class="pt-2">The deposits will be added to your balance and you can use it to buy cards.</h6>
									<div class="d-grid pt-2">
										<button type="button" class="btn btn-success" data-api="depoinit">Deposit now</button>
									</div>
								</div>
							</div>
			   			</div>
			   		</div>
			   	</div>
		    </div>
		</div>
	</div>

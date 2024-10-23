<div class="am-mainpanel">
	<div class="am-pagebody">
		<div class="row">
			<div class="col-md-4">
				<div class="card bg-success text-white">
					<div class="card-body">
						<h3><span class="bx bx-users"></span> Total Users <?php echo $nbusers ?></h3>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card bg-warning text-white">
					<div class="card-body">
						<h3><span class="bx bx-handshake"></span> Total Sellers <?php echo $nsellers ?></h3>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card bg-info text-white">
					<div class="card-body">
						<h3><span class="bx bx-support"></span> Total Support <?php echo $nsupport ?></h3>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12" id="DivTabelStock"> 
						<table id="TabelStock" class="table table-condensed table-bordered table-hover table-striped table-bordered dataTable no-footer">
							<thead>
								<tr>
									<th><input type="checkbox" id="selectall" data-api="toggleSelect"> #ID</th>
									<th>Username</th>
									<th>Email</th>
									<th>Groupe</th>
									<th>Status</th>
									<th>Balance</th>
									<th>Seller Balance</th>
									<th>Seller Fees</th>
									<th>Referals</th>
									<th>Rate</th>
									<th>Signup Date</th>
									<th>Last Login Date</th>
									<th>Tools</th>
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
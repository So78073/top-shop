<div class="am-mainpanel">
	<div class="am-pagebody">

<div class="card "> 
    <div class="card-body">
    	<div class="row">
    		<div class="col-md-3">
    			<div class="card radius-10 border-top border-0 border-2 border-success bg-success text-white">
    				<div class="card-body">
    					<div class="d-flex align-items-center">
    					    
        						<div>
        							<p class="mb-0 text-white">Total Deposits All Time</p>
        							<h4 class="my-1">$<?php echo number_format($totdep ,2, '.', '' );?></h4>
        						</div>
        						<div class="ms-auto font-35 text-white" style="font-size:35px"><i class="bx bx-bitcoin"></i>
        						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    		<div class="col-md-3">
    		    
    			<div class="card radius-10 border-top border-0 border-2 border-warning bg-warning text-white">
    				<div class="card-body">
    				    <a href="<?php base_url() ?>/tikets">
    					<div class="d-flex align-items-center">
    						<div>
    							<p class="mb-0 text-white">Open Ticket</p>
    							<h4 class="my-1 text-white"><?php echo $tikets;?></h4>
    						</div>
    						<div class="ms-auto font-35 text-white" style="font-size:35px">
    						    <i class="bx bx-comment-add"></i>
    						</div>
    					</div>
    					</a>
    				</div>
    			</div>
    			</a>
    		</div>
    		<div class="col-md-3">
    			<div class="card radius-10 border-top border-0 border-2 border-danger bg-danger text-white">
    				<div class="card-body">
    				    <a href="<?php base_url() ?>/historywithdraws">
    					<div class="d-flex align-items-center">
    						<div>
    							<p class="mb-0 text-white">Withdraw Requests</p>
    							<h4 class="my-1 text-white"><?php echo $withdraws;?></h4>
    						</div>
    						<div class="ms-auto font-35 text-white" style="font-size:35px"><i class="bx bx-wind"></i>
    						</div>
    					</div>
    					</a>
    				</div>
    			</div>
    		</div>
    		<div class="col-md-3">
    			<div class="card radius-10 border-top border-0 border-2 border-info bg-info text-white">
    				<div class="card-body">
    				    <a href="<?php base_url() ?>/sellerrequests">
    					<div class="d-flex align-items-center">
    						<div>
    							<p class="mb-0 text-white">Seller Requests</p>
    							<h4 class="my-1 text-white"><?php echo $sellerrequests ;?></h4>
    						</div>
    						<div class="ms-auto font-35 text-white" style="font-size:35px"><i class="bx bx-network-chart"></i>
    						</div>
    					</div>
    					</a>
    				</div>
    			</div>
    		</div>
    	</div> 
    	<div class="row mt-3">
    		<div class="col-md-4">
    			<div class="card mb-5 mb-lg-0">
    				<div class="card-header bg-light py-3">
    					<h5 class="card-title text-white text-uppercase text-center" style="color:#000 !important">Total Income All Time</h5>
    					<h6 class="card-price text-white text-center"><span class="term" style="color:#000 !important">$<?php echo number_format($totalrev, 2, '.', '') ?></span></h6>
    				</div>
    				<div class="card-body">
    					<ul class="list-group list-group-flush">
    						<?php
    
    							foreach($sections as $key => $val){
    								echo '<li class="list-group-item bg-transparent">
    										<i class=" bx '.$val['sectionicon'].' me-2 font-18"></i>
    										<span class="">'.$val['sectionlable'].'</span>
    										<span class="float-end">Total income : <i class="text-success">'.'<b>$'.number_format($val["sectionrevenue"], 2, '.', '').'</b></i></span>
    									</li>';
    							}
    						?>
    					</ul>
    				</div>
    			</div>
    			<div class="card">
    				<div class="card-body">
    					<h6>Top sellers</h6>
    					<table class="table table-bordered table-hover">
    						<thead>
    							<tr>
    								<th>User Name</th>
    								<th>Balance</th>
    								<th>Last Login</th>
    							</tr>
    						</thead>
    						<tbody>
    							<?php
    								foreach ($topsellers as $keyseller => $valueseller) {
    									$ndate = new \DateTime($valueseller['last_login_date']);
    									echo '<tr><td>'.ucfirst($valueseller['username']).'</td>
    										  <td>$'.number_format($valueseller['seller_balance'], 2, '.', '').'</td>
    										  <td>'.$ndate->format('d/m/Y H:i:s').'</td></tr>
    									';
    								}
    							?>
    						</tbody>
    					</table>
    				</div>
    			</div>
    		</div>
    		<div class="col-md-8">
    			<div class="card">
    				<div class="card-body">
    					<div class="chart-container1">
    						<canvas id="chart2"></canvas>
    					</div>
    				</div>
    			</div>
    			<div class="card">
    				<div class="card-body">
    					<h5 class="card-title">New Bases</h5>
	    				<table id="TabelStock" class="table table-striped table-bordered" style="width:100%">
							<thead>
								<tr>
							    	<th>Base name</th>
							    	<th>Contains</th>
							    	<th>Seller</th>
						     		<th data-priority="1">Tools</th>  
								</tr>
							</thead>
						</table>
    				</div>
    			</div>

    		</div>
    		<div class="col-md-6">
    			
    			</div>
    		</div>
    	</div>
    </div>

	</div><!-- signin-box -->
</div><!-- am-signin-wrapper -->

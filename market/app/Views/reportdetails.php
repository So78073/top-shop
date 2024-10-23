<div class="am-mainpanel">
	<div class="am-pagebody">
	    
	    <div clas="card">
	        <div class="container-fluid">
				<div class="page-titles">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url() ?>disputes">Disputes</a></li>
						<li class="breadcrumb-item active">
							<a href="javascript:void(0)">Dispute ID #<?php echo $report['id'] ?> <?php $cumdate = new \DateTime($report['reportdate']); echo $cumdate->format('d/m/Y H:i:s') ?> </a>
						</li>
					</ol>
                </div>
				<div class="row">
					<div class="col-md-8">
						<div class="card">
							<div class="card-header bg-info text-white">
                                <div id="checkbtn" class="">Item <?php echo strtoupper($report['rtype']) ?> ID #<?php echo $report['itemid'] ?></b></div>
                            </div>
                            <div class="card-body" style="overflow:hidden !important ;">
                        		<div class="row">
                        			<div class="col-md-12">
                    					<div id="DZ_W_TimeLine" class="widget-timeline dz-scroll ps ps--active-y" style="height: 500px; overflow:hidden;">
                    						<div class="row px-0 mx-0 py-0 my-0">
                    							<div class="col-md-6 px-0 mx-0 py-0 my-0">
                    								<div class="timeline" id="reportimeline">
											            <?php foreach ($reportdetails as $key => $value) { ?>
											            	<div class="card bg-gray-200">
											            		<div class="card-body">
											            			<?php
											            				if($value['user_groupe'] == '9' || $value['user_groupe'] == '4'){
											            					echo '<p class="card-title text-danger"><b>Support</b></p>';
											            				}
											            				else {
											            					echo '<p class="card-title"><b>'.ucfirst($value['username']).'</b></p>';
											            				}
											            			?>										 
											            			<p class="card-subtitle"><?php $sdate = new \DateTime($value['messagedate']); echo $sdate->format('d/m/Y H:s:i')  ?></p>
											            			<p class="card-text"><?php echo $value['message'] ?></p>
											            		</div>
											            	</div>
				                                    	<?php  } ?>
				                                    </div>
						                        </div>
						                    </div>
				                        </div>
                        			</div>
                        		</div>
                        		<div class="row">
                        			<div class="col-md-12" id="chatdiv">
                        				<div class="card-footer">
								            <?php if($report['status'] == '0'){ ?>
										    <form id="chatform">
												<div class="chat-footer d-flex align-items-center"  style="left:0 !important; height:230px">
													<div class="flex-grow-1 pe-2">
													    <span class="textArea text-danger"></span>
														<div class="input-group">
															<textarea id="chattext" name="chattext"  class="form-control" placeholder="Only those characters are allowed (a-z A-Z 0-9 _ - * $ % . ! ? : , ; / \)" style="max-height:170px; height:150px"></textarea>
															<input type="hidden" name="id" value="<?php echo $report['id'] ?>">
														</div>
														
														<button style="display:block !important; width:100%; height:45px; margin-top:15px" class="btn btn-success btn-sm" type="button" data-api="zchat-<?php echo $report['id'] ?>">Send <i class="bx bx-send"></i></button>
													</div>
												</div>
											</form>
											<?php } ?>
								        </div>
                    					
                        			</div>
                        		</div>
		                    </div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="card">
							<div class="card-body">
								<h6>Product informations</h6>
								<p class="mb-1"><small><b>Product ID:</b> #<?php echo $report['itemid'] ?></small></p>
								<p class="mb-1"><small><b>Price:</b> $<?php echo $report['itemprice'] ?></small></p>
								<p class="mb-1"><small><b>Purshase Date:</b> <?php $bdate = new \DateTime($report['buydate']);  echo $bdate->format('d/m/Y H:i:s'); ?></small></p>
								<p class="mb-1"><small><b>Reported Date:</b> <?php $rdate = new \DateTime($report['reportdate']);  echo $rdate->format('d/m/Y H:i:s'); ?></small></p>
								<hr class="mt-3">
								<?php if($report['status'] == '3'){ ?>
									<div class="text-center mt-5">
										<h6 class="mt-3">REPORT CLOSED</h6><br>
									</div>
								<?php } else if($report['status'] == '1'){ ?>
									<div class="text-center mt-5">
										<h6 class="mt-3 badge badge-success badge-xl">ITEM REFUNDED <i class="mdi mdi-check"></i></h6><br>
									</div>
								<?php } else if($report['status'] == '2'){ ?>
									<div class="text-center mt-5">
										<h6 class="mt-3 badge badge-success badge-xl">ITEM REPLACED <i class="mdi mdi-check"></i></h6><br>
									</div>
								<?php } else { ?>
									<div class="text-center mt-5" id="statuspannel">
										<div id="clock"></div>
                    					<?php if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '4'){ ?>
                    						<div class="btn-group mt-3" id="buttonscontrols">
	                    						<button class="btn btn-danger btn-xxs disputcontrols" onclick="closedispute();">Close 
	                    							<span class="mdi mdi-close"></span></button>
	                    						<button class="btn btn-primary btn-xxs disputcontrols" onclick="manerefund();">Refund <span class="mdi mdi-forward"></span></button>
	                    					</div>
                    					<?php } else if(session()->get('suser_groupe') == '1' && session()->get('suser_id') == $report["sellerid"]){ ?>
                    						<div class="btn-group mt-3" id="buttonscontrols">
	                    						<button class="btn btn-primary btn-xxs disputcontrols" onclick="manerefund();">Refund <span class="mdi mdi-forward"></span></button>
                    						</div>
                    					<?php } else if(session()->get('suser_id') == $report["buyerid"]){ ?>
                    						<div class="btn-group mt-3" id="buttonscontrols">
                    							<button class="btn btn-danger btn-xxs disputcontrols" onclick="closedispute();">Close <span class="mdi mdi-close"></span></button>
                    						</div>
                    					<?php } ?>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
            </div>
	    </div>
	</div><!-- am-pagebody -->
</div><!-- am-mainpanel -->

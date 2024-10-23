<div class="am-mainpanel">
	<div class="am-pagebody">
		<div class="row">
			<div class="col-md-6">
				<div class="card radius-10 border-top border-0 border-2 border-danger">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div>
								<p class="mb-0">My Balance</p>
								<h4 class="my-1">$<?php echo number_format($results[0]['balance'], 2, '.', '') ?></h4>
							</div>
							<div class="ms-auto font-35 text-white"><i class="bx bx-wallet"></i>
							</div>
						</div>
					</div>
				</div>

				<div class="card">
					<div class="card-body">
						<form id="persoform">		  
            				<div class="col-md-12">
            					<label class="form-label">Email</label>
            					<div class="input-group">
            						<input type="email" class="form-control" name="email" id="email" value="<?php echo $results[0]['email'] ?>">
            						<a href="javascript:;" class="input-group-text bg-transparent"><i class="bx bx-mail-send"></i></a></div>
            					<span class="text-danger email"></span>
            				</div>
            				<br/>
            				<?php if(session()->get('suser_groupe') == '1'){ ?>
            				<div class="col-md-12">
            					<label class="form-label">BTC Address</label>
            					<div class="input-group">
            					<input type="text" class="form-control" name="btcaddress" id="btcaddress" value="<?php echo $results[0]['btcaddress'] ?>">
            					<a href="javascript:;" class="input-group-text bg-transparent"><i class="bx bx-bitcoin"></i></a></div>
            					<span class="text-danger btcaddress" placeholder="@EvilCoder"></span>
            				</div>
            				<?php } ?>
            				<br/>
            				<?php if($settings[0]['telegram'] == '1'){ ?>
            				<div class="col-md-12">
            					<label class="form-label">Telegram</label>
            					<div class="input-group">
            					<input type="text" class="form-control" name="telegram" id="telegram" value="<?php echo $results[0]['telegram'] ?>">
            					<a href="javascript:;" class="input-group-text bg-transparent"><i class="bx bx-send"></i></a></div>
            					<span class="text-danger telegram" placeholder="@EvilCoder"></span>
            				</div>
            				<?php } ?>
            				<br/>
            				<?php if($settings[0]['icq'] == '1'){ ?>
        					<div class="col-md-12">
            					<label class="form-label">ICQ</label>
            					<div class="input-group">
            					<input type="text" class="form-control" name="icq" id="icq" value="<?php echo $results[0]['icq'] ?>" placeholder="@EvilCoder">
            					<a href="javascript:;" class="input-group-text bg-transparent"><i class="bx bx-shape-polygon"></i></a></div>
            					<span class="text-danger icq"></span>
            				</div>
            				<?php } ?>
            				<?php if($settings[0]['jaber'] == '1'){ ?>
        					<div class="col-md-12">
            					<label class="form-label">Jabber</label>
            					<div class="input-group">
            					<input type="text" class="form-control" name="jaber" id="jaber" value="<?php echo $results[0]['jaber'] ?>" placeholder="name@organisation.org">
            					<a href="javascript:;" class="input-group-text bg-transparent"><i class="bx bx-badge"></i></a></div>
            					<span class="text-danger jaber"></span>
            				</div>
            				<?php } ?>
            				<input type="hidden"  id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
            				<div class="ms-auto d-grid mt-3">
            					<button type="button" class="btn btn-primary btn-block" data-api="updateperso">Save <span class="fa fa-save"></span></buttin>
        					</div>

        				</form>
					</div>
				</div>

			</div>
			<div class="col-md-6">
				<div class="card">
					<div class="card-body">
						<?php if($settings[0]['invitecode'] == "1"){ ?>
        				<h6 class="mt-3 mb-3">Your Invite code : <?php echo session()->get("suser_invitecode") ?></h6>
            			<?php } ?>
            			<?php if($settings[0]['refsys'] == "1"){ ?>
            				<h6 class="mt-3 mb-3">Your Referal link : <?php echo base_url().'/signup?r='.session()->get("suser_referal_code") ?></h6>
            				<p>Refer to your friends to our website and get bonus of <?php echo session()->get('suser_referals_rate') ?>% from deposits</p>
            				<a class="" href="<?php echo base_url()?>/myreferals">You can see the details of your referal<span class="text-danger"> Here</span> .</a>
            			<?php } ?>
					</div>
				</div>

				<div class="card mt-3">
					<div class="card-body">
						<form id="pwdform" class="row">
	            			<h5 class="mt-3 mb-3">Update Password</h5>
	            				<div class="col-md-12">
	            					<label class="form-label">Actual Password</label>
	            					<div class="input-group">
	            					<input type="password" class="form-control" name="password" id="password">
	            					<a href="javascript:;" class="input-group-text bg-transparent"><i class="bx bx-cookie"></i></a></div>
	            					<span class="text-danger password"></span>
	            				</div>
	            				<input type="hidden"  id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
	            				<div class="col-md-12">
	            					<label class="form-label">New Password</label>
	            					<div class="input-group">
	            					<input type="password" class="form-control" name="npassword" id="npassword">
	            					<a href="javascript:;" class="input-group-text bg-transparent"><i class="bx bx-transfer-alt"></i></a></div>
	            					<span class="text-danger npassword"></span>
	            				</div>

	        					<div class="col-md-12">
	            					<label class="form-label">Repeat Password</label>
	            					<div class="input-group">
	            					<input type="password" class="form-control" name="rpassword" id="rpassword">
	            					<a href="javascript:;" class="input-group-text bg-transparent"><i class="bx bx-transfer-alt"></i></a></div>
	            					<span class="text-danger rpassword"></span>
	            				</div>
	            				<br>		

	            				<div class="ms-auto d-grid mt-3">
	            					<button type="button" class="btn btn-primary btn-block" data-api="updatepass">Save <span class="fa fa-save"></span></buttin>
	        					</div>

	        			</form>
					</div>
				</div>
				<input type="hidden" id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">


			</div>
		</div>

	</div><!-- am-pagebody -->
</div><!-- am-mainpanel -->
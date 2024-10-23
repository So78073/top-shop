<div class="am-mainpanel">
	<div class="am-pagebody">
		<div class="card "> 
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-2 col-sm-12 ">
								<input type="hidden" id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
								<ul class="list-group widget-list-group bg-danger nav" id="v-pills-tab" role="tablist" aria-orientation="vertical">

									<li role="presentation" class="list-group-item rounded-top-0 bg-success  px-0 py-0" aria-selected="false" tabindex="-1">
									  	<a class="text-white active d-block px-2 py-2" onclick="initselect();" href="javascript:void(0);" data-bs-toggle="pill" data-bs-target="#globalSettings" role="tab" aria-controls="globalSettings" aria-selected="true"><i class="bx bx-globe font-18 align-middle me-1"></i>Global Settings</a>
									</li>
									<li role="presentation" class="list-group-item  px-0 py-0">
									  	<a class="text-white d-block px-2 py-2" href="javascript:void(0);"  onclick="initselect();"  data-bs-toggle="pill" data-bs-target="#registrationSettings" role="tab" aria-controls="registrationSettings"><i class="bx bx-message-alt-add font-18 align-middle me-1"></i>Registration Settings</a>
									</li>
									<li role="presentation" class="list-group-item  px-0 py-0">
									  	<a class="text-white d-block px-2 py-2" href="javascript:void(0);"  data-bs-toggle="pill" data-bs-target="#sectionsSettings" role="tab" aria-controls="sectionsSettings"><i class="bx bx-unite font-18 align-middle me-1"></i>Sections Settings</a>
									</li>
									<li role="presentation" class="list-group-item  px-0 py-0">
									  	<a class="text-white d-block px-2 py-2" href="#" onclick="initselect();" data-bs-toggle="pill" data-bs-target="#referalsystemSettings" role="tab" aria-controls="referalsystemSettings"><i class="bx bx-like font-18 align-middle me-1"></i>Referal system Settings</a>
									</li>
									<li class="list-group-item  px-0 py-0" role="presentation">
									  	<a class="text-white d-block px-2 py-2" href="#" onclick="initselect();" data-bs-toggle="pill" data-bs-target="#sellersystemSettings" role="tab" aria-controls="sellersystemSettings"><i class="bx bx-network-chart font-18 align-middle me-1"></i>Sellers system Settings</a>
									</li>
									<li class="list-group-item  px-0 py-0" role="presentation">
									  	<a class="text-white d-block px-2 py-2" href="#" onclick="initselect();" data-bs-toggle="pill" data-bs-target="#cardscheckerSettings" role="tab" aria-controls="cardscheckerSettings"><i class="bx bx-barcode-reader font-18 align-middle me-1"></i>Card Checkers Settings</a>
									</li>
									<li class="list-group-item px-0 py-0" role="presentation">
									  	<a class="text-white d-block px-2 py-2" href="#" onclick="initselect();" data-bs-toggle="pill" data-bs-target="#payementsSettings" role="tab" aria-controls="payementsSettings"><i class="bx bx-wallet-alt font-18 align-middle me-1"></i>Getway Payements</a>
									</li>
									<li class="list-group-item rounded-bottom-0  px-0 py-0" role="presentation">
									  	<a class="text-white d-block px-2 py-2" href="#" onclick="initselect();" data-bs-toggle="pill" data-bs-target="#telegramSettings" role="tab" aria-controls="telegramSettings"><i class="bx bx-wallet-alt font-18 align-middle me-1"></i>Notifications</a>
									</li>
								</ul>	
							</div>
							<div class="col-md-10 col-sm-12">
								<div class="tab-content border-top border-0 border-2 border-danger " id="v-pills-tabContent">
									<div class="tab-pane fade show active" id="globalSettings" role="tabpanel" aria-labelledby="globalSettings-tab" tabindex="0">
										<form id="globalSettingsForm" enctype="multipart/form-data">
											<div class="card">
												<div class="card-body">
													<h5 class="card-title">Site settings</h5>
													<div class="row">
														<div class="form-group col-md-12 pt-3">
															<label >Site Name</label>
															<input type="text" class="form-control" name="sitename" id="sitename" value="<?php echo SETTINGS["sitename"] ?>">
															<small class="sitename text-danger"></small>
														</div>	
															<div class="form-group col-md-6 pt-3">
															<label >Site Logo</label>
															<input type="file" class="form-control" name="logo" id="logo" value="<?php echo SETTINGS["logo"] ?>">
															<small class="logo text-danger"></small>
														</div>
														<div class="form-group col-md-6 pt-3">
															<label >Default users Logo</label>
															<input type="file" class="form-control" name="siteuserslogo" id="siteuserslogo" value="<?php echo SETTINGS["siteuserslogo"] ?>">
															<small class="siteuserslogo text-danger"></small>
														</div>
														<div class="form-group col-md-12 pt-3">
															<label >Site Custom Header Tags</label>
															<textarea class="form-control" name="sitemeta" style="min-height: 250px;" id="sitemeta"><?php echo SETTINGS["sitemeta"] ?></textarea>
															<small class="sitemeta text-danger"></small>
														</div>
														<div class="form-group col-md-12 pt-3">
															<label >Site Custom Javascript Scripts/Plugins</label>
															<textarea class="form-control" name="sitejava" style="min-height: 250px;" id="sitejava"><?php echo SETTINGS["sitejava"] ?></textarea>
															<small class="sitejava text-danger"></small>
														</div>
														<div class="form-group pt-3">
															<button type="button" class="btn btn-success float-end" data-api="saveGlobale"><span class="fa fa-save"></span> Save</button>
														</div>							
													</div>
												</div>
											</div>
										</form>
									</div>
									<!--Registration settings-->
									<div class="tab-pane fade" id="registrationSettings" role="tabpanel" aria-labelledby="registrationSettings-tab" tabindex="1">
										<form id="RegsettingsForm">
											<div class="card">
												<div class="card-body">
													<h5 class="card-title">Registration settings</h5>
													<div class="row">
														<div class="form-group col-md-6 pt-3">
															<label >Registration</label>
															<select class="form-control select2" name="openreg" id="openreg">
																<?php
																	switch(SETTINGS["openreg"]){
																		case '1':
																			echo '<option value="1" selected>Activated</option>
																			<option value="0">Deactivated</option>';
																		break;
																		case '0':
																			echo '<option value="1">Activated</option>
																			<option value="0" selected>Deactivated</option>';
																		break;
																	}
																?>
															</select>
															<small class="openreg text-danger"></small>
														</div>
														<div class="form-group col-md-6 pt-3">
															<label >Registration only with invite code</label>
															<select class="form-control select2" name="invitecode" id="invitecode">
																<?php
																	switch(SETTINGS["invitecode"]){
																		case '1':
																			echo '<option value="1" selected>Activated</option>
																			<option value="0">Deactivated</option>';
																		break;
																		case '0':
																			echo '<option value="1">Activated</option>
																			<option value="0" selected>Deactivated</option>';
																		break;
																	}
																?>
															</select>
															<small class="invitecode text-danger"></small>
														</div>
														<div class="form-group col-md-6 pt-3">
															<label >Show Telegram field in registration</label>
															<select class="form-control select2" name="telegram" id="telegram">
																<?php
																	switch(SETTINGS["telegram"]){
																		case '1':
																			echo '<option value="1" selected>Activated</option>
																			<option value="0">Deactivated</option>';
																		break;
																		case '0':
																			echo '<option value="1">Activated</option>
																			<option value="0" selected>Deactivated</option>';
																		break;
																	}
																?>
															</select>
															<small class="telegram text-danger"></small>
														</div>
														<div class="form-group col-md-6 pt-3">
															<label >Require Telegram in registration</label>
															<select class="form-control select2" name="rtelegram" id="rtelegram">
																<?php
																	switch(SETTINGS["rtelegram"]){
																		case '1':
																			echo '<option value="1" selected>Activated</option>
																			<option value="0">Deactivated</option>';
																		break;
																		case '0':
																			echo '<option value="1">Activated</option>
																			<option value="0" selected>Deactivated</option>';
																		break;
																	}
																?>
															</select>
															<small class="rtelegram text-danger"></small>
														</div>
														<div class="form-group col-md-6 pt-3">
															<label >Show ICQ field in registration</label>
															<select class="form-control select2" name="icq" id="icq">
																<?php
																	switch(SETTINGS["icq"]){
																		case '1':
																			echo '<option value="1" selected>Activated</option>
																			<option value="0">Deactivated</option>';
																		break;
																		case '0':
																			echo '<option value="1">Activated</option>
																			<option value="0" selected>Deactivated</option>';
																		break;
																	}
																?>
															</select>
															<small class="icq text-danger"></small>
														</div>
														<div class="form-group col-md-6 pt-3">
															<label >Require ICQ in registration</label>
															<select class="form-control select2" name="ricq" id="ricq">
																<?php
																	switch(SETTINGS["ricq"]){
																		case '1':
																			echo '<option value="1" selected>Activated</option>
																			<option value="0">Deactivated</option>';
																		break;
																		case '0':
																			echo '<option value="1">Activated</option>
																			<option value="0" selected>Deactivated</option>';
																		break;
																	}
																?>
															</select>
															<small class="ricq text-danger"></small>
														</div>
														<div class="form-group col-md-6 pt-3">
															<label >Show Jaber field in registration</label>
															<select class="form-control select2" name="jaber" id="jaber">
																<?php
																	switch(SETTINGS["jaber"]){
																		case '1':
																			echo '<option value="1" selected>Activated</option>
																			<option value="0">Deactivated</option>';
																		break;
																		case '0':
																			echo '<option value="1">Activated</option>
																			<option value="0" selected>Deactivated</option>';
																		break;
																	}
																?>
															</select>
															<small class="jaber text-danger"></small>
														</div>
														<div class="form-group col-md-6 pt-3">
															<label >Require Jaber in registration</label>
															<select class="form-control select2" name="rjaber" id="rjaber">
																<?php
																	switch(SETTINGS["rjaber"]){
																		case '1':
																			echo '<option value="1" selected>Activated</option>
																			<option value="0">Deactivated</option>';
																		break;
																		case '0':
																			echo '<option value="1">Activated</option>
																			<option value="0" selected>Deactivated</option>';
																		break;
																	}
																?>
															</select>
															<small class="rjaber text-danger"></small>
														</div>
														<div class="form-group pt-3">
															<button type="button" class="btn btn-success float-end" data-api="savemysettings-reg"><span class="fa fa-save"></span> Save</button>
														</div>				
													</div>
												</div>
											</div>
										</form>
									</div>
									<!--Sections settings-->
									<div class="tab-pane fade" id="sectionsSettings" role="tabpanel" aria-labelledby="sectionsSettings-tab" tabindex="1">
										<div class="card">
											<div class="card-body">
												<h5 class="card-title">Sections settings</h5>
												<div class="row">
													<div class="col-md-12">
														<table id="TabelStock" class="table table-striped table-bordered dataTable" style="width:100%">
															<thead>
																<tr>
																	<th>ID</th>
																	<th>Section Name</th>
																	<th>Section Status</th>
																	<th>Maintenance Mode</th>
																	<th>Allow Sellers</th>
																	<th>Items</th>
																	<th>Icon</th>
																	<th>Revenu</th>
																	<th>Manage</th>
																</tr>
															</thead>
														</table>
													</div>				
												</div>
											</div>
										</div>
									</div>
									<!--Referal system settings-->
									<div class="tab-pane fade" id="referalsystemSettings" role="tabpanel" aria-labelledby="referalsystemSettings-tab" tabindex="2">
										<form id="RefettingsForm">
											<div class="card">
												<div class="card-body">
													<h5 class="card-title">Referal system settings</h5>
													<div class="row">
														<div class="form-group col-md-6 pt-3">
															<label >Referal system</label>
															<select class="form-control select2" name="refsys" id="refsys">
																<?php
																	switch(SETTINGS["refsys"]){
																		case '1':
																			echo '<option value="1" selected>Activated</option>
																			<option value="0">Deactivated</option>';
																		break;
																		case '0':
																			echo '<option value="1">Activated</option>
																			<option value="0" selected>Deactivated</option>';
																		break;
																	}
																?>
															</select>
															<small class="refsys text-danger"></small>
														</div>	
														<div class="form-group col-md-6 pt-3">
															<label >Referal system rate %</label>
															<input type="number" class="form-control" name="refrate" id="refrate" value="<?php echo SETTINGS["refrate"] ?>">
															<small class="refrate text-danger"></small>
														</div>	
														<div class="form-group pt-3">
															<button type="button" class="btn btn-success float-end" data-api="savemysettings-ref"><span class="fa fa-save"></span> Save</button>
														</div>						
													</div>
												</div>
											</div>
										</form>
									</div>
									<!--Sellers system settings-->
									<div class="tab-pane fade" id="sellersystemSettings" role="tabpanel" aria-labelledby="sellersystemSettings-tab" tabindex="2">
										<form id="sellsettingsForm">
											<div class="card">
												<div class="card-body">
													<h5 class="card-title">Sellers system settings</h5>
													<div class="row">
														<div class="form-group col-md-6 pt-3">
															<label >Sellers system</label>
															<select class="form-control select2" name="sellersystem" id="sellersystem">
																<?php
																	switch(SETTINGS["sellersystem"]){
																		case '1':
																			echo '<option value="1" selected>Activated</option>
																			<option value="0">Deactivated</option>';
																		break;
																		case '0':
																			echo '<option value="1">Activated</option>
																			<option value="0" selected>Deactivated</option>';
																		break;
																	}
																?>
															</select>
															<small class="sellersystem text-danger"></small>
														</div>	
														<div class="form-group col-md-6 pt-3">
															<label >Sellers system fees</label>
															<input type="number" class="form-control" name="sellerate" id="sellerate" value="<?php echo SETTINGS["sellerate"] ?>">
															<small class="refrate text-danger"></small>
														</div>	
														<div class="form-group pt-3">
															<button type="button" class="btn btn-success float-end" data-api="savemysettings-sell"><span class="fa fa-save"></span> Save</button>
														</div>						
													</div>
												</div>
											</div>
										</form>
									</div>
									<!--Cards Checkers settings-->
									<div class="tab-pane fade" id="cardscheckerSettings" role="tabpanel" aria-labelledby="cardscheckerSettings-tab" tabindex="2">
										<form id="checkersettingsForm">
											<div class="card">
												<div class="card-body">
													<h5 class="card-title">Cards Checkers</h5>
													<div class="row">
														<div class="form-group col-md-6 pt-3">
															<label >CC Checker system</label>
															<select class="form-control select2" name="ccchecker" id="ccchecker">
																<?php
																	switch(SETTINGS["ccchecker"]){
																		case '1':
																			echo '<option value="1" selected>Activated</option>
																			<option value="0">Deactivated</option>';
																		break;
																		case '0':
																			echo '<option value="1">Activated</option>
																			<option value="0" selected>Deactivated</option>';
																		break;
																	}
																?>
															</select>
															<small class="ccchecker text-danger"></small>
														</div>
														<div class="form-group col-md-6 pt-3">
															<label >CC Checker to use</label>
															<select class="form-control select2" name="checkerused" id="checkerused" data-api="setchecker">
																<?php
																	switch(SETTINGS["checkerused"]){
																		case '1':
																			echo '<option value="1" selected>Luxchecker.pw</option>
																			<option value="2">chk.cards</option>';
																		break;
																		case '2':
																			echo '<option value="1" >Luxchecker.pw</option>
																			<option value="2" selected>chk.cards</option>';
																		break;
																		case '3':
																			echo '<option value="1" >Luxchecker.pw</option>
																			<option value="2" >chk.cards</option>';
																		break;
																	}
																?>
															</select>
															<small class="checkerused text-danger"></small>
														</div>
														<div class="form-group col-md-6 pt-3">
															<label >CC Check Timeout in seconds</label>
															<input type="number" class="form-control" name="ccchecktimeout" id="ccchecktimeout" value="<?php echo SETTINGS["ccchecktimeout"] ?>">
															<small class="ccchecktimeout text-danger"></small>
														</div>
														<div class="form-group col-md-6 pt-3">
															<label >CC Check Cost</label>
															<input type="number" class="form-control" name="cccheckercost" id="cccheckercost" value="<?php echo SETTINGS["cccheckercost"] ?>">
															<small class="cccheckercost text-danger"></small>
														</div>
														<div class="form-group col-md-6 pt-3">
															<label >Approve CC Bases before upload</label>
															<select class="form-control select2" name="baseapproved" id="baseapproved">
																<?php
																	switch(SETTINGS["baseapproved"]){
																		case '1':
																			echo '<option value="1" selected>Activated</option>
																			<option value="0">Deactivated</option>';
																		break;
																		case '0':
																			echo '<option value="1">Activated</option>
																			<option value="0" selected>Deactivated</option>';
																		break;
																	}
																?>
															</select>
															<small class="baseapproved text-danger"></small>
														</div>						
													</div>
													<h5 class="card-title mt-5">Checkers API Settings</h5>
													<div class="row">
														<div class="form-group col-md-6 pt-3 luxo">
															<label >Luxchecker.pw User Name</label>
															<input type="text" class="form-control" name="luxorcheckeruser" id="luxorcheckeruser" value="<?php echo SETTINGS["luxorcheckeruser"] ?>">
															<small class="luxorcheckeruser text-danger"></small>
														</div>
														<div class="form-group col-md-6 pt-3 luxo">
															<label >Luxchecker.pw API Key</label>
															<input type="text" class="form-control" name="luxorchecjerapi" id="luxorchecjerapi" value="<?php echo SETTINGS["luxorchecjerapi"] ?>">
															<small class="luxorchecjerapi text-danger"></small>
														</div>
														<div class="form-group col-md-12 pt-3 chk">
															<label >Chk.cards API Key</label>
															<input type="text" class="form-control" name="ccdotcheckapi" disabled id="ccdotcheckapi" value="<?php echo SETTINGS["ccdotcheckapi"] ?>">
															<small class="ccdotcheckapi text-danger"></small>
														</div>						
													</div>
													<div class="form-group col-md-12 pt-3">
														<button type="button" class="btn btn-success float-end" data-api="savemysettings-checkers"><span class="fa fa-save"></span> Save</button>
													</div>
												</div>
											</div>
										</form>
									</div>
									<!--Getway Payements settings-->
									<div class="tab-pane fade" id="payementsSettings" role="tabpanel" aria-labelledby="payementsSettings-tab" tabindex="2">
										<div class="card">
											<form id="getwayssettingsForm">
												<div class="card-body">
													<h5 class="card-title">Payement Getway</h5>
													<div class="row">
														<div class="form-group col-md-6 pt-3">
															<label >Choose payement Getway</label>
															<select class="form-control select2" name="payementgetway" id="payementgetway" data-api="setgetway" >
																<?php
																	switch(SETTINGS["payementgetway"]){
																		case '1':
																			echo '<option value="1" selected>Nowpayements.io</option>';
																			
																		break;
																		/**case '2':
																		<option value="2">Coinpayements.net (Accept only BTC)</option>
																			<option value="3">Blockonomics.co</option>';


																			echo '<option value="1" >Nowpayements.io</option>
																			<option value="2" selected>Coinpayements.net (Accept only BTC)</option>
																			<option value="3">Blockonomics.co</option>';

																		break;
																		case '3':
																			echo '<option value="1" >Nowpayements.io</option>
																			<option value="2">Coinpayements.net (Accept only BTC)</option>
																			<option value="3" selected>Blockonomics.co</option>';
																		break;**/
																	}
																?>
															</select>
															<small class="payementgetway text-danger"></small>
														</div>
														<div class="form-group col-md-6 pt-3">	
															<label >Minimum Deposit</label>
															<input type="text" class="form-control" name="mindepo" id="mindepo" value="<?php echo SETTINGS["mindepo"] ?>">
															<small class="mindepo text-danger"></small>
														</div>					
													</div>
													<div class="row"  id="nowp">
														<hr class="mt-5">
														<h5 class="card-title mt-3">Nowpayements.io Settings</h5>
														<div class="form-group col-md-6 pt-3">
															<label >Nowpayements.io API Key</label>
															<input type="text" class="form-control" name="nowpayementapikey" id="nowpayementapikey" value="<?php echo SETTINGS["nowpayementapikey"] ?>">
															<small class="nowpayementapikey text-danger"></small>
														</div>
														<div class="form-group col-md-6 pt-3">
															<label >Accept Coins</label>
															<select class="form-control select2" name="nowpaymentaccept[]" id="nowpaymentaccept" multiple="multiple">
																<?php
																	$seetingsCoins = explode(',', SETTINGS['nowpaymentaccept']);

																	foreach($coins as $coinkey => $coinval){
																		if(in_array($coinval, $seetingsCoins)){
																			echo '<option value="'.$coinval.'" selected>'.strtoupper($coinval).'</option>';
																		}
																		else {
																			echo '<option value="'.$coinval.'">'.strtoupper($coinval).'</option>';
																		}
																	}

																?>
															</select>
															<small class="nowpaymentaccept text-danger"></small>
														</div>
													</div>
													<div class="row" id="coinp">
														<hr class="mt-5">
														<h5 class="card-title mt-3">Coinpayements.net Settings</h5>
														<div class="form-group col-md-6 pt-3">
															<label >Coinpayements.net Merchen ID</label>
															<input type="text" class="form-control" name="coinpayementmerchen" id="coinpayementmerchen" value="<?php echo SETTINGS["coinpayementmerchen"] ?>">
															<small class="coinpayementmerchen text-danger"></small>
														</div>
														<div class="form-group col-md-6 pt-3">
															<label >Coinpayements.net IPN Secret</label>
															<input type="text" class="form-control" name="coinpayementipn" id="coinpayementipn" value="<?php echo SETTINGS["coinpayementipn"] ?>">
															<small class="coinpayementipn text-danger"></small>
														</div>
														<div class="form-group col-md-6 pt-3">
															<label >Coinpayements.net API Key</label>
															<input type="text" class="form-control" name="coinpayementapi" id="coinpayementapi" value="<?php echo SETTINGS["coinpayementapi"] ?>">
															<small class="coinpayementapi text-danger"></small>
														</div>	
														<div class="form-group col-md-6 pt-3">
															<label >Coinpayements.net Secret Key</label>
															<input type="text" class="form-control" name="coinpayementsecret" id="coinpayementsecret" value="<?php echo SETTINGS["coinpayementsecret"] ?>">
															<small class="coinpayementsecret text-danger"></small>
													</div>
													</div>
													<div class="row" id="blokp">
														<hr class="mt-5">
														<h5 class="card-title mt-3">Blockonomics.co Settings (Accept Only BTC with Blockchain wallet)</h5>
														<div class="form-group col-md-12 pt-3">
															<label >Blockonomics.co API Key</label>
															<input type="text" class="form-control" name="blockonomicsapi" id="blockonomicsapi" value="<?php echo SETTINGS["blockonomicsapi"] ?>">
															<small class="blockonomicsapi text-danger"></small>
														</div>							
													</div>
													<div class="row">
														<div class="form-group col-md-12 pt-3">
															<button type="button" class="btn btn-success float-end" data-api="savemysettings-getways"><span class="fa fa-save"></span> Save</button>
														</div>
													</div>
												</div>
											</form>
										</div>
									</div>
									<!--Telegram system settings-->
									<div class="tab-pane fade" id="telegramSettings" role="tabpanel" aria-labelledby="telegramSettings-tab" tabindex="2">
										<form id="TelegramForm">
											<div class="card">
												<div class="card-body">
													<h5 class="card-title">Telegram Channel/Groupe Notifications</h5>
													<div class="row">
														<div class="form-group col-md-4 pt-3">
															<label >Notifications</label>
															<select class="form-control select2" name="telenotif" id="telenotif">
																<?php
																	switch(SETTINGS["telenotif"]){
																		case '1':
																			echo '<option value="1" selected>Activated</option>
																			<option value="0">Deactivated</option>';
																		break;
																		case '0':
																			echo '<option value="1">Activated</option>
																			<option value="0" selected>Deactivated</option>';
																		break;
																	}
																?>
															</select>
															<small class="telenotif text-danger"></small>
														</div>	
														<div class="form-group col-md-4 pt-3">
															<label >Bot API Key</label>
															<input type="text" class="form-control" name="telebot" id="telebot" value="<?php echo SETTINGS["telebot"] ?>">
															<small class="telebot text-danger"></small>
														</div>
														<div class="form-group col-md-4 pt-3">
															<label >Chat ID</label>
															<input type="text" class="form-control" name="chatid" id="chatid" value="<?php echo SETTINGS["chatid"] ?>">
															<small class="chatid text-danger"></small>
														</div>
														<div class="form-group col-md-4 pt-3">
															<label >Telegram Group/Channel Link</label>
															<input type="text" class="form-control" name="chatlink" id="chatlink" value="<?php echo SETTINGS["chatlink"] ?>">
															<small class="chatid text-danger"></small>
														</div>
														<div class="form-group pt-3">
															<button type="button" class="btn btn-success float-end" data-api="savemysettings-telegram"><span class="fa fa-save"></span> Save</button>
														</div>						
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> 
			</div>
		</div>
	</div><!-- am-pagebody -->
</div><!-- am-mainpanel -->












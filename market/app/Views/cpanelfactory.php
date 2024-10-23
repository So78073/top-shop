<div class="am-mainpanel">
	<div class="am-pagebody">
		<div class="row">
			<div class="col-md-8">
				<div class="card">
					<div class="card-body">
						<form id="addForm">
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label class="pb-2">Set the price by item</label>
										<input type="number" name="price" id="price" min="1" class="form-control" placeholder="10" value="10">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="pb-2">Select your Formta</label>
										<select class="form-control select2" style="width:100%" id="delimiter">
											<option value="|">|</option>
											<option value="/">/</option>
											<option value="\">\</option>
											<option value="-">-</option>
											<option value="#">#</option>
											<option value=":">:</option>
											<option value="::">::</option>
											<option value=",">,</option>
										</select>
									</div>	
								</div>
								<div class="col-md-12" id="cardsinfodiv">
									<label class="pb-2">Insert your cpanel list</label>
									<textarea class="form-control" id="cpanels" style="min-height:450px; max-height: 100vh;"></textarea>
								</div>
								<div class="col-md-12 d-none tablediv">
									<div id="formatatalbeldiv">
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>		
				<input type="hidden" id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
			</div>
			<div class="col-md-4">
				<div class="card">
					<div class="card-body controls">
						<div class="form-group d-grid">
							<button type="button" class="btn btn-danger active" data-api="startFormatcpanels">Format Cpanels List <span class="bx bx-upload"></span></button>
						</div>
					</div>
				</div>
				<div id="cardlog" class="card mt-2 d-none">
					<div class="card-body" >
						<div class="mt-2">
							<div class="form-group d-grid">
								<button id="copyinval" class="btn btn-info" data-api="copyInvalid-this">Copy Invalid  Cpanels List <span class="bx bx-copy"></span></button>
							</div>
							<div id="log" style="height: 450px  !important; overflow: scroll !important;">
							</div>
						</div>
					</div>
				</div>
				<input type="hidden" id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
			</div>
		</div>
	</div><!-- am-pagebody -->
</div><!-- am-mainpanel -->
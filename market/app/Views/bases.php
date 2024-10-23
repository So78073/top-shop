<div class="am-mainpanel">
	<div class="am-pagebody">
		<div class="card "> 
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">						
						<div class="col-6 mb-3" id="DivTabelStock">
							<?php if(session()->get("suser_groupe") == '9' || session()->get("suser_groupe") == '1' && $sellersactivate == '1' && SETTINGS['sellersystem'] == '1'){ ?>
								<a href="<?php echo base_url() ?>/factory" id="add" class="btn btn-primary btn-sm"><i class="bx bx-upload"></i> Upload new Bases</a>
							<?php } ?>
						</div>
						<div class="col-md-12 mt-3">
							<table id="TabelStock" class="table table-striped table-bordered" style="width:100%">
								<thead>
									<tr>
									    <th><input type="checkbox" id="chekall"> </th>
								    	<th>Base name</th>
								    	<th>Status</th>
								    	<th>Sell Progress</th>
								    	<th>Contains</th>
							     		<th data-priority="1">Tools</th>  
									</tr>
								</thead>
							</table>
							<input type="hidden" id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
						</div>
					</div>
				</div> 
			</div>
		</div>
	</div><!-- am-pagebody -->
</div><!-- am-mainpanel -->

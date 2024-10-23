<div class="am-mainpanel">
	<div class="am-pagebody">
      	<div class="card">
		  <div class="card-body p-4">
			    <div class="row">
				   	<div class="col-lg-12">
                        <table id="TabelStock" class="table table-striped table-bordered" style="width:100%">
							<thead>
								<tr>
								    <th>ID</th>
								    <th>Username</th>
								    <th>Info</th>
								    <th>Date</th>
								    <th style="max-width: 10% !important;">Tools</th> 
								</tr>
							</thead>
						</table>
				   </div>
			   </div>
		  	</div>
		  	<input type="hidden" id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
		</div>
	</div><!-- am-pagebody -->
</div><!-- am-mainpanel -->
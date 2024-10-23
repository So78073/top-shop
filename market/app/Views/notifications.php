<div class="am-mainpanel">
	<div class="am-pagebody">
		<div class="card">
			<div class="card-body">
				<table id="TabelStock" class="table table-striped table-bordered" style="width:100%">
					<thead>
						<tr>
						    <th>Date</th>
						    <th>Notification</th>
						    <th>Tool</th>
						</tr>
					</thead>
				</table>
				<input type="hidden" id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
			</div>
		</div>
	</div><!-- am-pagebody -->
</div><!-- am-mainpanel -->
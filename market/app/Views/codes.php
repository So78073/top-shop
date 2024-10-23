<div class="am-mainpanel">
	<div class="am-pagebody">
		<div class="card">
			<div class="card-body">
				<button type="button" class="btn btn-success btn-sm float-end m-2" data-api="initcreatelog"><i class="bx bx-upload"></i> Create new Voucher Code</button>
				<table id="TabelStock" class="table table-striped table-bordered" style="width:100%">
	                <thead>
	                    <tr>
	                        <th style="width:5% !important;">ID</th>
	                        <th style="width:15% !important;">Code</th>
	                        <th style="width:15% !important;">Value</th>
	                        <th style="width:5% !important;">Status</th>
	                        <th style="width:15% !important;">Generated Date</th>
	                        <th style="width:15% !important;">Used By </th>
	                        <th style="width:15% !important;">Used Date</th>
	                        <th style="width:15% !important;">Tools</th>
	                    </tr>
	                </thead>
	            </table>
				<input type="hidden" id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
			</div>
		</div>
	</div><!-- am-pagebody -->
</div><!-- am-mainpanel -->
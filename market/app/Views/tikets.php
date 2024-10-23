<div class="am-mainpanel">
	<div class="am-pagebody">
		<div class="card mb-2">
			<div class="card-body">
				<div class="alert pd-20 bg-indigo tx-white mg-b-0">
					<ul>
						<li>Read before opening your ticket.</li>
						<li>Tickets about approved cards will be ignored, flooding tickets about approved cards will get you banned.</li>
						<li>If you want to become a supplier on our store open a ticket with your jabber or telegram.</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="card mb-2">
			<div class="card-body">
				<button type="button" class="btn btn-primary btn-sm float-end" data-api="initcreatelog"><i class="bx bx-upload"></i> Create new Ticket</button>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<table id="TabelStock" class="table table-hover" style="width:100%">
	                <thead>
	                    <tr>
	                        <th style="width:5% !important;">ID</th>
	                        <th style="width:15% !important;">Subject</th>
	                        <th style="width:25% !important;">Description</th>
	                        <th style="width:5% !important;">Status</th>
	                        <th style="width:15% !important;">Date</th>
	                        <?php if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '2'){ ?>
	                        <th style="width:15% !important;">User Name</th>
	                        <th style="width:5% !important;">User ID</th>
	                        <?php } ?>
	                        <th style="width:15% !important;">Tool</th>
	                    </tr>
	                </thead>
	            </table>
	            <input type="hidden" id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
			</div>
		</div>
	</div><!-- am-pagebody -->
</div><!-- am-mainpanel -->
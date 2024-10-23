<div class="am-mainpanel">
	<div class="am-pagebody">
		<div class="card mb-2">
			<div class="card-body">
				<div class="alert pd-20 bg-indigo tx-white mg-b-0">
					<ul>
						<li>Find all your deposits history here.</li>
						<li>Press the button status to check your deposit.</li>
						<li>Your deposit will be add automaticaly to your balance after 2 network confirmations.</li>
						<li>Please make sure to send the exact amount to the exact address with the exact currency</li>
						<li>Do not send twice to the same address, if you want to deposit more repeat the process from beginning but never send twice to the same address since it will be lost</li>
						<li>If your deposit status is WAITING,CONFIRMING,or SENDING click the button to reload it and your balance will be added</li>
						<li>We do not refund your deposit outside of the store, you can use your balance on store only</li>
					</ul>
				</div>
			</div>
		</div>
        <div class="card">
        	<div class="card-body">
        		<table id="TabelStock" class="table table-hover" style="width:100%">
	                <thead>
	                    <tr>
	                        <th style="width:15% !important;">Date</th>
	                        <?php if(session()->get('suser_groupe') == '9'){ ?>
	                        <th style="width:15% !important;">Username</th>
	                        <th style="width:5% !important;">User ID</th>
	                        <?php } ?>
	                        <th style="width:15% !important;">Amount $</th>
	                        <th style="width:15% !important;">Currency</th>
	                        <th style="width:15% !important;">Address</th>
	                        <th style="width:15% !important;">Status</th>
	                    </tr>
	                </thead>
	            </table>
	            <input type="hidden" id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
        	</div>
        </div>
	</div>
</div>
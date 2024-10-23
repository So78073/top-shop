<div class="am-mainpanel">
	<div class="am-pagebody">
		<div class="card mb-2">
			<div class="card-body">
				<div class="alert pd-20 bg-indigo tx-white mg-b-0">
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<div class="default-tab">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active bg-info" data-toggle="tab" href="#opens" onclick="geAllDisputes('open')"><i class="mdi mdi-alarm-light pr-2"></i> Opens Disputes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link bg-info" data-toggle="tab" href="#refundments" onclick="geAllDisputes('refunded')"><i class="mdi mdi-format-rotate-90 pr-2"></i> Refunded</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link bg-info" data-toggle="tab" href="#closement" onclick="geAllDisputes('closed')"><i class="mdi mdi-close pr-2"></i> Closed</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="opens" role="tabpanel">
                            <div class="pt-4">
                                <table id="open" class="display dataTable" style="width: 100%;" role="grid" aria-describedby="Users_Table">
		                            <thead>
		                                <tr>
					                        <th>ID</th>
					                        <th>Subject</th>
					                        <th>Status</th>
					                        <th>Date</th>
					                        <th>Actions</th>
		                                </tr>
		                            </thead>
		                        </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="replacements">
                        	<div class="pt-4">
                                <table id="replaced" class="display dataTable"  style="width: 100%;" role="grid" aria-describedby="Users_Table">
		                            <thead>
		                                <tr>
					                        <th>ID</th>
					                        <th>Subject</th>
					                        <th>Status</th>
					                        <th>Date</th>
					                        <th>Actions</th>
		                                </tr>
		                            </thead>
		                        </table>
	                    	</div>
                        </div>
                        <div class="tab-pane fade" id="refundments">
                        	<div class="pt-4">
                                <table id="refunded" class="display dataTable"  style="width: 100%;" role="grid" aria-describedby="Users_Table">
		                            <thead>
		                                <tr>
					                        <th>ID</th>
					                        <th>Subject</th>
					                        <th>Status</th>
					                        <th>Date</th>
					                        <th>Actions</th>
		                                </tr>
		                            </thead>
		                        </table>
	                    	</div>
                        </div>
                        <div class="tab-pane fade" id="closement">
                        	<div class="pt-4">
                                <table id="closed" class="display dataTable"  style="width: 100%;" role="grid" aria-describedby="Users_Table">
		                            <thead>
		                                <tr>
					                        <th>ID</th>
					                        <th>Subject</th>
					                        <th>Status</th>
					                        <th>Date</th>
					                        <th>Actions</th>
		                                </tr>
		                            </thead>
		                        </table>
	                    	</div>
                        </div>
                    </div>
                </div>
	            <input type="hidden" id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
			</div>
		</div>
	</div><!-- am-pagebody -->
</div><!-- am-mainpanel -->
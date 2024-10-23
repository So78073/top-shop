	<div class="am-mainpanel">
		<div class="am-pagebody">
			<div class="card">
				<div class="card-body">
					<center><h6 class="text">Welcome, <span style="color:green"> <?php echo ucfirst(session()->get('suser_username')) ?>👌</span></h6></center>
				</div>
			</div>
			<?php if(session()->get("suser_groupe") == '9'){ ?>
			<div class="row">
				<div class="col-md-12">
					<div class="ms-auto">
						<div class="btn-group float-end">
							<form id="addForm">
								<div class="input-group">
									<button type="button" class="btn btn-info btn-sm" data-api="initcreateNews">Add New Post <i class="bx bx-plus"></i></button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
			<hr/>
			<div class="row">
				<input type="hidden"  id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
				<div class="col-md-12" id="news">
	   			</div>
	   		</div>

		</div>
	</div>

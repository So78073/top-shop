<div class="am-mainpanel">
	<div class="am-pagebody">
		<div class="card">
			<div class="card-body">
				<?php echo $sectionmessage ?>
		  	</div>
		</div>
		<?php if($sectiontype == '1'){ ?>
			<div class="card">
				<div class="card-body">
					<table id="TabelStock" class="table table-striped table-bordered" style="width:100%">
						<thead>
							<tr>
								<?php 
								foreach ($DataTable as $key => $row) {
								echo '<th>'.ucfirst($row).'</th>';
								}
								?>
								<?php if(session()->get('suser_groupe') == '9'){
								echo '<th>Manage</th>';
								}
								else {
								echo '<th>Add To Cart</th>';
								}
								?>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		<?php } else { ?>
			<div id="productsCards" class="card-deck">

			</div>
		<?php } ?>
		<input type="hidden" id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
	</div><!-- am-pagebody -->
</div><!-- am-mainpanel -->
<div class="am-mainpanel">
	<div class="am-pagebody">
		<div class="card mb-2">
			<div class="card-body">
				<h5 class="float-start">List of cards in base: <?php echo $baseName ?></h5>
				<a class="btn btn-success float-end text-white" href="<?php echo base_url() ?>/baselist/downloadbases?base=<?php echo $baseName ?>">Download All</a>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<input type="hidden" id="baseName" value="<?php echo $baseName ?>">
				<table id="Tabellists" class="table table-hover" style="width:100%">
	                <thead>
	                    <tr>
	                        <th>ID</th>
	                        <th>Data</th>
	                    </tr>
	                </thead>
	            </table>
	            <input type="hidden" id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
			</div>
		</div>
	</div><!-- am-pagebody -->
</div><!-- am-mainpanel -->
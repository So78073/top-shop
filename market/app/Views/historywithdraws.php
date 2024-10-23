<div class="am-mainpanel">
	<div class="am-pagebody">
		<table id="TabelStock" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Some</th>
                    <th>Wallet</th>
                    <th>Status</th>
                    <?php
                    	if(session()->get('suser_groupe') == '9'){
                    		echo '<th>Tools</th>';
                    	}
                    ?>
                </tr>
            </thead>
        </table>
        <input type="hidden" id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
	</div><!-- am-pagebody -->
</div><!-- am-mainpanel -->

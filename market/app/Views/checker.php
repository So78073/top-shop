
<div class="am-mainpanel">
	<div class="am-pagebody">
		<div class="row">
		    <form id="addCont" class="p-0 m-0"  enctype="multipart/form-data">
    			<div class="col-lg-12">
    			    <div class="row">
    			        
        			        <div class="form-groupe col-6 pb-2">
                                <label class="form-label">Input your delimiter ex:(|)</label>
                                <input type="text" class="form-control" id="delim">
                            </div>
                            <div class="form-groupe col-6 pb-2">
                                <label class="form-label">Select Proxy Type (Leave None for no proxy)</label>
                                <select id="proxytype" name="proxytype" class="form-select">
                                    <option value="none">None</option>
                                    <option value="http">HTTP</option>
                                    <option value="https">HTTPS</option>
                                    <option value="socks4">SOCKS4</option>
                                    <option value="socks5">SOCKS5</option>
                                </select>
                            </div>
                            <div class="form-groupe col-6 pb-2" id="cr">
                                <label class="form-label">Input your Cards</label>
                                <textarea class="form-control" name="cards" id="cards" style="min-height:300px"></textarea>
                            </div>
                            <div class="col-md-6 border border-danger bg-white pb-2 mb-3 table-responsive d-none" id="rz">
                                
                            </div>
                            <div class="form-groupe col-3 pb-2">
                                <label class="form-label">Input your Proxy</label>
                                <textarea class="form-control" name="proxy" id="proxy" style="min-height:300px"></textarea>
                            </div>
                        
                            <div class="form-groupe col-3 pb-2">
                                <label class="form-label">Results</label>
                                <textarea class="form-control" id="resu" style="min-height:300px"></textarea>
                            </div>
                            <div class="form-groupe col-12 pb-2">
                                <div class="d-grid" id="btns">
                                    <button type="button" class="btn btn-info" data-api="checkerformatter">Format</button>       
                                </div>
                            </div>
                        
    			    </div>
                        
    			</div>
    			<input type="hidden"  id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
			</form>
		</div>
	</div><!-- am-pagebody -->
</div><!-- am-mainpanel -->


<div class="am-mainpanel">
	<div class="am-pagebody">
	    
	    <div clas="card">
	        <div class="card-header card-header-default justify-content-between bg-dark">
	            <p style="margin-bottom:0 !important; line-height: 24px">Subject : <?php echo esc($results[0]['subject']) ?></p>
	            <?php if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '3'){ ?>
	            <div class="card-option tx-24">
                    <a href="javascript:void(0);" title="Resolve" data-api="updatesTikettatus-<?php echo $results[0]['id']  ?>|1" class="btn btn-success btn-sm">Resolved</a>
                    <a href="javascript:void(0);" title="Close" data-api="updatesTikettatus-<?php echo $results[0]['id']  ?>|2" class="btn btn-danger btn-sm">Close</a>
                </div>
                <?php  } ?>
	        </div>
	        <div class="card-body bg-white chatconntainer" style="overflow-y:scroll; padding:25px 35px 0 3px !important; max-height:450px;">
	            <?php
				    if(session()->get('suser_id') == $results[0]['userid']){
                        $classs="chat-content-leftside";
                        $textclass= "chat-left-msg";
                        $textend = '';
                    }
                    else {
                       $classs="chat-content-rightside"; 
                       $textclass= "chat-right-msg";
                       $textend = 'text-end';
                    }
				?>
			    <div  id="ps-mid" class="chatcontents">
				    <div class="<?php echo $classs ?>">
						<div class="d-flex">
							<div class="flex-grow-1 ms-2">
								<p class="mb-0 chat-time <?php echo $textend ?>"><b><?php echo esc(ucfirst($results[0]['username'])) ?></b> ,<?php $repdate = new \DateTime($results[0]['date']); echo $repdate->format('H:i:s d/m') ?></p>
								<p class="<?php echo $textclass.' '.$textend ?>"><?php echo nl2br(esc(ucfirst($results[0]['description']))) ?></p>
							</div>
						</div>
					</div>
                    <?php 
                        if(count($resultsMytiket) > 0){
                            foreach($resultsMytiket as $key => $value){
                                if(session()->get('suser_id') == $value['responseuserid']){
                                    $class="chat-content-leftside";
                                    $textclass= "chat-left-msg";
                                    $textend = '';
                                }
                                else {
                                    $class="chat-content-rightside"; 
                                    $textclass= "chat-right-msg";
                                    $textend = 'text-end';
                                } 
                    ?>
                            
                        <div class="<?php echo $class ?>">
						    <div class="d-flex">
								<div class="flex-grow-1 ms-2">
									<p class="mb-0 chat-time <?php echo $textend ?>"><?php if($value['responseusergroupe'] == '9'){ echo '<span class="text-danger"><b>Support</b></span>'; } else {  echo '<b>'.ucfirst(esc($value['responseusername'])).'</b>'; } ?> ,<?php $repdate = new \DateTime($results[0]['date']); echo $repdate->format('H:i:s d/m') ?></p>
									<p class="<?php echo $textclass.' '.$textend ?>"><?php echo nl2br(ucfirst(esc($value['responses']))) ?></p>
								</div>
							</div>
						</div>
                    <?php    }
                        }   
                    
                    ?>
                    <input type="hidden" id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
			    </div>
				
	        </div>
	        <div class="card-footer">
	            <?php if($results[0]['status'] == '0'){ ?>
			    <form id="msgsupport">
					<div class="chat-footer d-flex align-items-center"  style="left:0 !important; height:230px">
						<div class="flex-grow-1 pe-2">
						    <span class="textArea text-danger"></span>
							<div class="input-group">
								<textarea id="msg" name="msg"  class="form-control" placeholder="Only those characters are allowed (a-z A-Z 0-9 _ - * $ % . ! ? : , ; / \)" style="max-height:170px; height:150px"></textarea>
								<input type="hidden" name="id" value="<?php echo $results[0]['id'] ?>">
							</div>
							
							<button style="display:block !important; width:100%; height:45px; margin-top:15px" class="btn btn-success btn-sm" type="submit">Send <i class="bx bx-send"></i></button>
						</div>
					</div>
				</form>
				<?php } ?>
	        </div>
	    </div>
	</div><!-- am-pagebody -->
</div><!-- am-mainpanel -->
<script>
    const chatContainer = document.querySelector('.chatconntainer');
    chatContainer.scrollTop = chatContainer.scrollHeight;
</script>

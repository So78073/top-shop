  <div class="am-signin-wrapper">
      <div class="am-signin-box">
        <div class="row no-gutters">
          <div class="col-lg-5">
            <div>
              <img src="<?php echo base_url()?>/assets/images/logo/<?php echo SETTINGS["logo"] ?>" width="180" alt="" />
              <hr>
              <p>Already have an account? <br> <a href="<?php echo base_url()?>/login">Login</a></p>
            </div>
          </div>
          <div class="col-lg-7">
			<div class="form-body">
				<form id="signupForm" class="row g-3">
					<div class="col-sm-12">
						<label for="email" class="form-label">Email</label>
						<div class="input-group">
							<input type="email" class="form-control border-end-0" id="email" placeholder="hacksaw@email.com" name="email"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-envelope'></i></a>
						</div>
					</div>
					<div class="col-sm-12">
						<label for="username" class="form-label">Username</label>
						<div class="input-group">
							<input type="text" class="form-control border-end-0" id="username" placeholder="username" name="username"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-user'></i></a>
						</div>
					</div>
					<div class="col-sm-12">
						<label for="password" class="form-label">Password</label>
						<div class="input-group" id="show_hide_password">
							<input type="password" class="form-control border-end-0" id="password"  placeholder="password" name="password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
						</div>
					</div>
					<div class="col-sm-12">
						<label for="rpassword" class="form-label">Confirm Password</label>
						<div class="input-group" id="show_hide_rpassword">
							<input type="password" class="form-control border-end-0" id="rpassword" placeholder="confirm password" name="rpassword"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
						</div>
					</div>
					<input type="hidden" id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
					<?php
						if($settings[0]["invitecode"] == "1"){?>
					<div class="col-sm-12">
						<label for="icode" class="form-label">Invite Code</label>
						<div class="input-group">
							<input type="text" class="form-control border-end-0" id="icode" placeholder="3f2493c85558b170621ad735d4a418da" name="icode"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='lni lni-customer'></i></a>
						</div>
					</div>		
					<?php } ?>
					<?php
						if($settings[0]["icq"] == "1"){?>
					<div class="col-sm-12">
						<label for="icq" class="form-label">ICQ</label>
						<div class="input-group">
							<input type="text" class="form-control border-end-0" id="icq" placeholder="@ICQCarder" name="icq"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-shape-polygon'></i></a>
						</div>
					</div>		
					<?php } ?>
					<?php
						if($settings[0]["telegram"] == "1"){?>
					<div class="col-sm-12">
						<label for="telegram" class="form-label">Telegram</label>
						<div class="input-group">
							<input type="text" class="form-control border-end-0" id="telegram" placeholder="@Carder" name="telegram"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='lni lni-telegram-original'></i></a>
						</div>
					</div>		
					<?php } ?>
					<?php
						if($settings[0]["jaber"] == "1"){?>
					<div class="col-sm-12">
						<label for="jaber" class="form-label">Jaber</label>
						<div class="input-group">
							<input type="text" class="form-control border-end-0" id="jaber" placeholder="user@organisation.org" name="jaber"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-message-rounded-minus'></i></a>
						</div>
					</div>		
					<?php } ?>
					
					<div class="col-sm-12">
						<label for="captcha"><?php echo $captcha["part1"].' + '.$captcha["part2"] ?></label>
						<div class="input-group">
							<input type="text" class="form-control border-end-0" id="captcha" name="captcha" placeholder="">
							<a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-braille'></i></a>
						</div>		                                   
                        <div  class="captcha">
                        </div>
					</div>		

					<div class="col-12">
						<div class="d-grid">
							<button type="button" class="btn btn-light" data-api="signup"><i class='bx bx-user'></i>Sign up</button>
						</div>
					</div>
				</form>
			</div>
          </div><!-- col-7 -->
        </div><!-- row -->
      </div><!-- signin-box -->
    </div><!-- am-signin-wrapper -->
	</div>
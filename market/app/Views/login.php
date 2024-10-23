   <div class="am-signin-wrapper">
      <div class="am-signin-box">
        <div class="row no-gutters">
          <div class="col-lg-5">
            <div>
              <img src="<?php echo base_url()?>/assets/images/logo/<?php echo SETTINGS["logo"] ?>" width="180" alt="" />
              <hr>
              <?php if($settings[0]["openreg"] == "1"){  ?>
              <p>Don't have an account? <br> <a href="<?php echo base_url()?>/signup">Create one</a></p>
              <?php }  ?>
            </div>
          </div>
          <div class="col-lg-7">
			<form id="loginForm" class="row g-3">
				<div class="col-sm-12">
					<label for="username" class="form-label">Username</label>
					<div class="input-group">
						<input type="text" class="form-control border-end-0" id="username" placeholder="username" name="username"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-user'></i></a>
					</div>
				</div>
				<input type="hidden" id="crtoken" name="<?php echo csrf_token();  ?>" value="<?php echo csrf_hash();  ?>">
				<div class="col-12">
					<label for="password" class="form-label">Password</label>
					<div class="input-group" id="show_hide_password">
						<input type="password" class="form-control border-end-0" id="password" name="password" placeholder="password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
					</div>
				</div>
				<div class="col-sm-12">

					<label for="captcha" class="form-label"><?php echo $captcha["part1"].' + '.$captcha["part2"] ?></label>
					<div class="input-group">
						<input type="text" class="form-control border-end-0" id="captcha" placeholder="" name="captcha"> 
						<a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-braille'></i></a>
					</div>
					<div  class="captcha">
            		</div>
				</div>
				<div class="col-md-6">
				</div>
				<div class="col-12">
					<div class="d-grid">
						<button type="button" class="btn btn-light" data-api="login"><i class="bx bxs-lock-open"></i>Sign in</button>
					</div>
				</div>
			</form>
          </div><!-- col-7 -->
        </div><!-- row -->
      </div><!-- signin-box -->
    </div><!-- am-signin-wrapper -->
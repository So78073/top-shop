	<div class="am-header">
      <div class="am-header-left">
        <a id="naviconLeft" href="" class="am-navicon d-none d-lg-flex"><i class="icon ion-navicon-round"></i></a>
        <a id="naviconLeftMobile" href="" class="am-navicon d-lg-none"><i class="icon ion-navicon-round"></i></a>
        <a href="<?php base_url() ?>" class="am-logo">
            <img style="max-width:108px" src="<?php echo base_url()?>/assets/images/logo/<?php echo SETTINGS["logo"] ?>"  alt="" />
        </a>
      </div><!-- am-header-left -->

      <div class="am-header-right">
        <?php if(session()->get('suser_groupe') == '1'){ ?>
        <div class="dropdown dropdown-profile">
          <a href="javascript:void(0);" data-api="depoinit" class="nav-link nav-link-profile">
            <span class="hidden-xs-down" data-api="withdrawinit">Sales: <b class="text-success">$<?php echo number_format(session()->get('suser_seller_balance'),2, '.', '' );?></b> <i class="fa fa-minus mg-l-3"></i></span>
          </a>
        </div>
        <?php } ?>
        <div class="dropdown dropdown-profile">
          <a href="javascript:void(0);" data-api="depoinit" class="nav-link nav-link-profile">
            <span class="logged-name"><span class="hidden-xs-down">Funds: <b><span id="ubalance">$<?php echo number_format(session()->get('suser_balance'), 2 ,'.', '');?></span></b></span> <i class="fa fa-plus mg-l-3"></i></span>
          </a>
        </div><!-- dropdown -->
      	<div class="dropdown dropdown-notification">
          <a href="javascript:void(0);" class="nav-link pd-x-7 pos-relative" data-toggle="dropdown">
            <i class="icon ion-ios-cart tx-24"></i>
            <span id="carticon"></span>
            <!-- start: if statement -->
            
            <?php if($nbitemscart > 0){ ?>
            	<span class="square-8 bg-danger pos-absolute t-15 r-0 rounded-circle" id="nbcrt"></span>
            <?php } ?>
            
            <!-- end: if statement -->
          </a>
          <div class="dropdown-menu wd-300 pd-0-force">
            <div class="dropdown-menu-header">
                My Cart
                <span id="clearme">
				    <?php if($nbitemscart > 0){
							echo '<a href="javascript:void(0);" class="" data-api="clearCart" id="clearer">Remove All</a>';	
					}?> 
            </span>             
            </div><!-- d-flex -->

            <div class="media-list">
              <!-- loop starts here -->
              <div id="cartContents">
              <?php  echo $cartInnerHtml; ?>
              
              <!-- loop ends here -->
              </div>
            </div><!-- media-list -->
          </div><!-- dropdown-menu -->
        </div><!-- dropdown -->
        <div class="dropdown dropdown-notification">
          <a href="" data-api="offnotif"  class="nav-link pd-x-7 pos-relative" data-toggle="dropdown">
            <i class="icon ion-ios-bell-outline tx-24"></i>
            <!-- start: if statement -->
            <?php if(session()->get("suser_notifications_nb") > 0){ ?>
            <span class="square-8 bg-danger pos-absolute t-15 r-0 rounded-circle" id="nbnt"></span>
            <?php } ?>
            <!-- end: if statement -->
          </a>
          <div class="dropdown-menu wd-300 pd-0-force">
            <div class="dropdown-menu-header">
              Notifications
              <a href="<?php echo base_url() ?>/notifications" data-api="offnotif">See All</a>
            </div><!-- d-flex -->

            <div class="media-list">
              <!-- loop starts here -->
              <?php	
				if(DATANOTIFS[0] !== NULL){
						foreach (DATANOTIFS as $key => $value) { ?>
				<a href="<?php echo $value["url"] ?>" class="media-list-link read">
                    <div class="media pd-x-20 pd-y-15">
                      <i class="icon ion-ios-bell-outline tx-24"></i>
                      <div class="media-body">
                        <p class="tx-13 mg-b-0"><strong class="tx-medium"><?php echo $value["subject"] ?></strong> <?php echo $value["text"] ?></strong></p>
                        <span class="tx-12"><?php echo $value["date"] ?></span>
                      </div>
                    </div><!-- media -->
                </a>
				<?php	
					}
				}?>
              <div class="media-list-footer">
                <a href="" class="tx-12"><i class="fa fa-angle-down mg-r-5"></i> Close</a>
              </div>
            </div><!-- media-list -->
          </div><!-- dropdown-menu -->
        </div><!-- dropdown -->
        
        <div class="dropdown dropdown-profile">
          <a href="" class="nav-link nav-link-profile" data-toggle="dropdown">
            <img src="<?php echo base_url() ?>/assets/images/avatars/avatar-2.png" class="wd-32 rounded-circle" alt="">
            <span class="logged-name"><span class="hidden-xs-down"><?php echo esc(ucfirst(session()->get('suser_username'))) ?></span> <i class="fa fa-angle-down mg-l-3"></i></span>
          </a>
          <div class="dropdown-menu wd-200">
            <ul class="list-unstyled user-profile-nav">
              <li><a href="<?php echo base_url() ?>/profile"><i class="icon ion-ios-person-outline"></i> Edit Profile</a></li>
              <li><a href="<?php echo base_url() ?>/logout"><i class="icon ion-power"></i> Sign Out</a></li>
            </ul>
          </div><!-- dropdown-menu -->
        </div><!-- dropdown -->
      </div><!-- am-header-right -->
    </div><!-- am-header -->
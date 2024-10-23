<div class="am-sideleft">
    <div class="tab-content">
        <div id="mainMenu" class="tab-pane active">
            <ul class="nav am-sideleft-menu">
                <li class="nav-item">
                    <a href="<?php echo base_url() ?>" class="nav-link">
                        <i class="bx bx-home"></i>
                        <span><b>Home</b></span>
                    </a>
                </li>
                <?php if(session()->get("suser_groupe") == "9"){?>
                <li class="nav-item">
                    <a href="" class="nav-link with-sub">
                        <i class="bx bx-git-compare"></i>
                        <span><b>Site Settings</b></span>
                    </a>
                    <ul class="nav-sub">
                        <li class="nav-item"><a href="<?php echo base_url() ?>/history" class="nav-link">Payments</a>
                        </li>
                        <li class="nav-item"><a href="<?php echo base_url() ?>/Settings" class="nav-link">Global
                                settings</a></li>
                        <li class="nav-item"><a href="<?php echo base_url() ?>/users" class="nav-link">Users manager</a>
                        </li>
                        <li class="nav-item"><a href="<?php echo base_url() ?>/sellerrequests" class="nav-link">Sellers
                                Request</a></li>
                        <li class="nav-item"><a href="<?php echo base_url() ?>/historywithdraws"
                                class="nav-link">Withdraws Requests</a></li>
                        <li class="nav-item"><a href="<?php echo base_url() ?>/wizzard" class="nav-link">Sections
                                Wizard</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url() ?>/admindashboard" class="nav-link">
                        <i class="bx bx-carousel"></i>
                        <span><b>Admin Dashboard</b></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url() ?>/sellerdashboard" class="nav-link">
                        <i class="bx bx-bar-chart-square"></i>
                        <span><b>Seller Dashboard</b></span>
                    </a>
                </li>
                <?php if(SETTINGS['voucher'] == '1' && session()->get("suser_groupe") == '9'){ ?>
                <li class="nav-item">
                    <a href="<?php echo base_url() ?>/codes" class="nav-link">
                        <i class="bx bx-barcode-reader"></i>
                        <span><b>Voucher</b></span>
                    </a>
                </li>
                <?php }  ?>
                <?php }?>
                <?php if(session()->get('suser_groupe') !== '9'){ ?>
                <li class="nav-item">
                    <a href="" class="nav-link with-sub">
                        <i class="bx bx-dollar-circle"></i>
                        <span><b>Funds</b></span>
                    </a>
                    <ul class="nav-sub">
                        <li class="nav-item"><a href="javascript:void(0);" data-api="depoinit" class="nav-link">Add Funds</a></li>
                        <li class="nav-item"><a href="<?php echo base_url() ?>/history" class="nav-link">History</a></li>
                    </ul>
                </li>
                <?php if(session()->get('suser_groupe') == '1'){ ?>
                <li class="nav-item">
                    <a href="" class="nav-link with-sub">
                        <i class="bx bx-bar-chart-square"></i>
                        <span><b>Seller Menu</b></span>
                    </a>
                    <ul class="nav-sub">
                        <li class="nav-item"> 
                            <a href="<?php echo base_url() ?>/sellerdashboard" class="nav-link">Seller Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0);" class="nav-link" data-api="withdrawinit">
                                Sales <b style="color:red">$<?php echo number_format(session()->get('suser_seller_balance'),2, '.', '' );?> <i class="bx bx-minus"></i></b>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0);" class="nav-link">
                                Withdraw In hold <b style="color:red">$<?php echo number_format(session()->get('suser_withdrawinhold'),2, '.', '' );?></b>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url() ?>/historywithdraws" class="nav-link">Withdraws History</a>
                        </li>
                    </ul>
                </li>
                <?php } ?>
                <li class="nav-item">
                    <a href="<?php echo base_url() ?>/myorders" class="nav-link">
                        <i class="bx bx-archive-out"></i>
                        <span><b>Orders</b></span>
                    </a>
                </li>
                <li class="nav-item">
                    <?php if($nbitemscart > 0){
                        $cclass = 'with-cart';
                    }
                    else {
                        $cclass = '';
                    }
                    ?>
                    <a href="<?php echo base_url() ?>/cart" class="nav-link <?php echo $cclass;?>" id="acart">
                        <i class="bx bx-cart-alt"></i>
                        <span><b>Cart</b></span>
                    </a>

                </li>
                <?php if(SETTINGS['refsys'] == '1'){ ?>
                <li class="nav-item">
                    <a href="<?php echo base_url() ?>/myreferals" class="nav-link">
                        <i class="bx bx-network-chart"></i>
                        <span><b>Referals</b></span>
                    </a>
                </li>
                <?php }  ?>
                <?php if(SETTINGS['voucher'] == '1' && session()->get("suser_groupe") != '9'){ ?>
                <li class="nav-item">
                    <a href="javascript:void(0);" data-api="vocherinit" class="nav-link">
                        <i class="bx bx-barcode-reader"></i>
                        <span><b>Voucher</b></span>
                    </a>
                </li>
                <?php }  ?>
                <?php } ?>
                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link">
                        <span>Markets</span>
                    </a>
                </li>
                <?php foreach(SECTIONSLINKS as $val){
                if($val !== Null && $val['identifier'] == '1' && $val['sectionstatus'] == '1'){ 
                ?>

                
                <?php } ?>
                <?php } ?>
                <?php
                $links = '';
                foreach(SECTIONSLINKS as $val){
                    if($val !== Null && $val['sectionstatus'] == '1'){
                        if($val['identifier'] == '1'){
                            $links .= '<li class="nav-item">
                                        <a href="'.base_url().'/cards" class="nav-link">
                                            <i class="bx '.$val['sectionicon'].'"></i>
                                            <span><b>'.esc(ucfirst(strtolower($val['sectionlable']))).'</b></span>
                                        </a>
                                    </li>';    
                        }
                        elseif ($val['identifier'] == '2') {
                            $links .= '<li class="nav-item">
                                        <a href="'.base_url().'/cpanel" class="nav-link">
                                            <i class="bx '.$val['sectionicon'].'"></i>
                                            <span><b>'.esc(ucfirst(strtolower($val['sectionlable']))).'</b></span>
                                        </a>
                                    </li>'; 
                        }
                        elseif ($val['identifier'] == '3') {
                            $links .= '<li class="nav-item">
                                        <a href="'.base_url().'/rdp" class="nav-link">
                                            <i class="bx '.$val['sectionicon'].'"></i>
                                            <span><b>'.esc(ucfirst(strtolower($val['sectionlable']))).'</b></span>
                                        </a>
                                    </li>'; 
                        }
                        elseif ($val['identifier'] == '4') {
                            $links .= '<li class="nav-item">
                                        <a href="'.base_url().'/smtp" class="nav-link">
                                            <i class="bx '.$val['sectionicon'].'"></i>
                                            <span><b>'.esc(ucfirst(strtolower($val['sectionlable']))).'</b></span>
                                        </a>
                                    </li>'; 
                        }
                        elseif ($val['identifier'] == '5') {
                            $links .= '<li class="nav-item">
                                        <a href="'.base_url().'/shell" class="nav-link">
                                            <i class="bx '.$val['sectionicon'].'"></i>
                                            <span><b>'.esc(ucfirst(strtolower($val['sectionlable']))).'</b></span>
                                        </a>
                                    </li>'; 
                        }
                        else {         
                            $links .=  '
                            <li class="nav-item">
                            <a href="'.base_url().'/market/'.$val['identifier'].'" class="nav-link">
                            <i class="bx '.$val['sectionicon'].'"></i>
                            <span><b>'.esc(ucfirst(strtolower($val['sectionlable']))).'</b></span>
                            </a>
                            </li>';
                        }
                    }
                }
                echo $links;
                ?>
                <li class="nav-item text-center">
                    <a href="javascript:void(0);" class="nav-link">
                        <span>Support</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url() ?>/tikets" class="nav-link">
                        <i class="bx bx-support"></i>
                        <span><b>Tickets</b></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url() ?>/reports" class="nav-link">
                        <i class="bx bx-alert"></i>
                        <span><b>Reports</b></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url() ?>/faq" class="nav-link">
                        <i class="bx bx-analyse"></i>
                        <span><b>FAQ</b></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SETTINGS["chatlink"] ?>" target="_blank" class="nav-link">
                        <i class="icon ion-ios-paperplane-outline"></i>
                        <span><b>Telegram</b></span>
                    </a>
                </li>
                <?php if(session()->get('suser_groupe') == '0' && session()->get('suser_requeststatus') == '0' && SETTINGS['sellersystem'] == '1'){ ?>
                <li class="nav-item">
                    <a href="javascript:void(0);" data-api="getSeller" class="nav-link">
                        <i class="icon ion-social-buffer-outline"></i>
                        <span><b>Get Seller</b></span>
                    </a>
                </li>
                <?php } ?>
            </ul>
        </div><!-- #mainMenu -->
    </div><!-- tab-content -->
</div><!-- am-sideleft -->



<div class="am-pagetitle">
<?php  if($sectionName == 'My Orders'){ ?>
<?php } else { ?> 
   
    <h5 class="am-title">
        <?php echo $sectionName ?>
    </h5>
    <?php if($sectionName !== 'Cards'){ ?>
    <form id="searchBar" class="search-bar" action="index.html">
        <div class="form-control-wrapper">
            <input type="search" class="form-control bd-0" placeholder="Search...">
        </div><!-- form-control-wrapper -->
        <button id="searchBtn" class="btn btn-orange"><i class="fa fa-search"></i></button>
    </form><!-- search-bar -->
    <?php } ?>
<?php }  ?>
</div><!-- am-pagetitle -->
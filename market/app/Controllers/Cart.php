<?php

namespace App\Controllers;
class Cart extends BaseController
{
	public function index()
	{
		if(session()->get("logedin") == "1"){
			$data = [];
			$settings = fetchSettings();
			$mycart = getCart();
			$countmycart = count(session()->get('cart'));

			if($countmycart > 0){
				$total = 0;
				foreach(session()->get('cart') as $p => $o){ 
					$total = $total + str_replace('$', '', $o["price"]); 
				}
				$data["total"] = '$'.number_format($total, 2,'.', '');
			}
			else {
				$data["total"] = '$0.00';
			}
			$data["nbitemscart"] = $mycart[0];
			$data["cartInnerHtml"] = $mycart[1];
			$data["settings"] = $settings;	

			$data["nbitemscart"] = $mycart[0];
			$data["cartInnerHtml"] = $mycart[1];
			$data["settings"] = $settings;
			$data["sectionName"] = "My Cart";
			echo view("assets/header", $data);
            echo view("assets/aside");
            echo view("assets/topbarre");
            echo view("cart");
            echo view("assets/footer");
            echo view("assets/scripts");	
		}
		else {
			header('location:'.base_url().'/login');
			exit();
		}
		
	}

	public function refreshCart(){
	    if($this->request->isAJAX()){
    		if(session()->get("logedin") == "1"){
    			$response = array();
    			$mycart = getCart();
    			$countmycart = count(session()->get('cart'));
    
    			if($countmycart > 0){
    				$total = 0;
    				foreach(session()->get('cart') as $p => $o){ 
    					$total = $total + str_replace('$', '', esc($o["price"])); 
    				}
    				$response["total"] = '$'.number_format($total, 2,'.', '');
    			}
    			else {
    				$response["total"] = '$0.00';
    			}
    		}
    		else {
    			$response["total"] = '$0.00';
    		}
    
    		echo json_encode($response);
    		exit();
	    }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }

	}

	public function fetchTable(){
	    if($this->request->isAJAX()){
    		if(session()->get("logedin") == '1'){
    			$output = array('data' => array());
    			
    			$Results = session()->get('cart');
    			$countResults = count($Results);
    			$total = 0;
    			if($countResults > 0){
    				foreach ($Results as $Result) {
    					//id
    					$item = '<span style="font-size:20px" class="bx bx-'.$Result['icon'].'"></span>';
    					$type = $Result['type'];
    					$price = $Result['price'];
    					$button = '<button data-api="removeCartProd-'.$Result['id'].'|'.esc($Result['typebuying']).'" type="button" class="btn btn-sm btn-outline-danger"><span class="lni lni-close"></span></button>';
                    	$output['data'][] = array(
                    		$item,
                    		esc(ucfirst($type)),
                    		esc($price),
                    		$button,			
    					);
    					$total = $total + str_replace('$', '',$price);
    				}
    				echo json_encode($output);
    				exit();
    			}
    			else {
    				if(session()->get("suser_groupe") == "1"){
    					$output['data'][] = array(
    						NULL,
    	            		NULL,
    						NULL,
    						NULL,
    					);
    				}
    				else {
    					$output['data'][] = array(
    						NULL,
    	            		NULL,
    						NULL,
    						NULL,
    					);
    				}	
    				echo json_encode($output);
    				exit();	
    			}
    		}
    		else {
    			header('location:'.base_url().'/');
    			exit();
    		}
	    }
	    else {
	        echo "Nice try ;)";
	        exit();
	    }
	}


}

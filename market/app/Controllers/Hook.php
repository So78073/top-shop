<?php
namespace App\Controllers;
use App\Models\CardsModel;
use App\Models\UsersModel;
use App\Models\NotificationsModel;
use App\Models\PayementsModel;

class Hook extends BaseController
{
	public function index(){
        $debug =  file_get_contents('php://input',JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        $decoded = json_decode($debug,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        
        $payementid = $decoded["payment_id"];
        $btcAddress = $decoded["pay_address"];
        
        $model = new PayementsModel;
        $Results = $model->where(['payment_id' => $payementid, 'pay_address' => $btcAddress ])->findAll();
        
        if(count($Results) == 1){
            
            $userid = $Results[0]['userid'];
            $usersModel = new UsersModel;
            $ResultsUser = $usersModel->where(['id' => $userid])->findAll();
            if($decoded["payment_status"] == "sending"){
                if($Results[0]['price_amount'] != $decoded["price_amount"]){
                    die('Error ! 11');
                }
                else if($Results[0]['created_at'] != $decoded["created_at"]){
                   die('Error ! 12'); 
                }
                else if($Results[0]['order_id'] != $decoded["order_id"]) {
                   die('Error ! 13'); 
                }
                else {
                    
                    if(count($ResultsUser) == 1){
                        if($Results[0]['intstatus'] == '0'){
                            $data = [
                                'payment_status' => $decoded["payment_status"],
                                'intstatus' => '1'
                            ];
                            $model->update($Results[0]['id'], $data);
                            $nbNotif = $ResultsUser[0]['notifications_nb']+1;
                            $newbalance = $ResultsUser[0]['balance'] +  $decoded["price_amount"];
                            $dataUser = [
                                'balance' => $newbalance,
                                'notifications_nb' => $nbNotif,
                                'active' => '1',
                                
                            ];
                            
                            $usersModel->update($ResultsUser[0]['id'], $dataUser);
                            
                            $dataNotif = [
                                'subject' => 'Success',
                                'text' => 'Your balance has been updated!',
                                'url' => base_url().'/history',
                                'userid' => $Results[0]['userid']
                            ];
                            $modelNotif = new NotificationsModel;
                            $modelNotif->save($dataNotif);
                        }     
                    }
                }
            }
            else if($decoded["payment_status"] == "waiting" || $decoded["payment_status"] == "expired" || $decoded["payment_status"] == "confirming"){
                if($Results[0]['price_amount'] != $decoded["price_amount"]){
                    die('Error ! 1');
                }
                else if($Results[0]['created_at'] != $decoded["created_at"]){
                   die('Error ! 2'); 
                }
                else if($Results[0]['order_id'] != $decoded["order_id"]) {
                   die('Error ! 3'); 
                }
                if(count($ResultsUser) == 1){
                    switch ($decoded["payment_status"]) {
                        case 'sending':
                            $init = '1';
                        break;
                        case 'waiting':
                            $init = '0';
                        break;
                        case 'partially_paid':
                            $init = '2';
                        break;
                        case 'expired':
                            $init = '3';
                        break;
                    }
                    $data = [
                        'payment_status' => $decoded["payment_status"],
                        'intstatus' => $init
                    ];
                    $model->update($Results[0]['id'], $data);
                }  
            }
            else if($decoded["payment_status"] == "partially_paid"){
                if(count($ResultsUser) == 1){
                    $data = [
                        'payment_status' => 'Partially paid contact support',
                        'intstatus' => $init
                    ];
                    $model->update($Results[0]['id'], $data);
                }  
            }
            else  {
               die('Error ! 3');  
            }      
        }
	}
}




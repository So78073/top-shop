<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = ['url','filesystem', 'form', 'security', 'Global_helper'];
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $DataSettings = fetchSettings();
        
        $DataSections = fetchSections();
        define("SETTINGS", $DataSettings[0]);
        
        define("SECTIONS", $DataSections[0]);
        define("SECTIONSLINKS", $DataSections);
        if(session()->get("logedin") == "1"){
            refreshUserInfos();
            $DataNotification = fetchNotifications();
            define("DATANOTIFS", $DataNotification);
        }
    }
}

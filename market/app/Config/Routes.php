<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true); 
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
//$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */
$routes->get('/mytiket/((?:[A-Za-z0-9+/]{4})*(?:[A-Za-z0-9+/]{2}==|[A-Za-z0-9+/]{3}=)?)', 'Mytiket::index/$1');
$routes->get('/sellerdashboard/((?:[A-Za-z0-9+/]{4})*(?:[A-Za-z0-9+/]{2}==|[A-Za-z0-9+/]{3}=)?)', 'Sellerdashboard::index/$1');
$routes->get('/sellerdashboard/((?:[A-Za-z0-9+/]{4})*(?:[A-Za-z0-9+/]{2}==|[A-Za-z0-9+/]{3}=)?)/(:num)', 'Sellerdashboard::index/$1/$2');
//cpanel
$routes->post('/sellerdashboard/initEditcpanel', 'Cpanel::initEdit');
$routes->post('/sellerdashboard/editcpanel', 'Cpanel::edit');
$routes->post('/sellerdashboard/rminitcpanel', 'Cpanel::rminit');
$routes->post('/sellerdashboard/rmcpanel', 'Cpanel::rm');
$routes->post('/sellerdashboard/massinitEditcapanel', 'Cpanel::massinitEdit');
$routes->post('/sellerdashboard/masseditcpanel', 'Cpanel::massedit');
$routes->post('/sellerdashboard/massrmcpanel', 'Cpanel::massrm');
$routes->post('/sellerdashboard/massrmuserinitcpanel', 'Cpanel::massrmuserinit');
//rdp
$routes->post('/sellerdashboard/initEditrdp', 'Rdp::initEdit');
$routes->post('/sellerdashboard/editrdp', 'Rdp::edit');
$routes->post('/sellerdashboard/rminitrdp', 'Rdp::rminit');
$routes->post('/sellerdashboard/rmrdp', 'Rdp::rm');
$routes->post('/sellerdashboard/massinitEditrdp', 'Rdp::massinitEdit');
$routes->post('/sellerdashboard/masseditrdp', 'Rdp::massedit');
$routes->post('/sellerdashboard/massrmrdp', 'Rdp::massrm');
$routes->post('/sellerdashboard/massrmuserinitrdp', 'Rdp::massrmuserinit');
//rdp
$routes->post('/sellerdashboard/initEditsmtp', 'Smtp::initEdit');
$routes->post('/sellerdashboard/editsmtp', 'Smtp::edit');
$routes->post('/sellerdashboard/rminitsmtp', 'Smtp::rminit');
$routes->post('/sellerdashboard/rmsmtp', 'Smtp::rm');
$routes->post('/sellerdashboard/massinitEditsmtp', 'Smtp::massinitEdit');
$routes->post('/sellerdashboard/masseditsmtp', 'Smtp::massedit');
$routes->post('/sellerdashboard/massrmsmtp', 'Smtp::massrm');
$routes->post('/sellerdashboard/massrmuserinitsmtp', 'Smtp::massrmuserinit');

$routes->get('/reports/fetch/open', 'Reports::fetchTableOpen');
$routes->get('/reports/fetch/replaced', 'Reports::fetchTableReplace');
$routes->get('/reports/fetch/refunded', 'Reports::fetchTableRefunds');
$routes->get('/reports/fetch/closed', 'Reports::fetchTableCloses');
$routes->post('/manerefund', 'Reports::ManuelleRefund');
$routes->post('/closedispute', 'Reports::ManuelleClose');
$routes->post('/proof', 'Reports::ProofProvider');
$routes->post('/chat', 'Reports::InChat');
$routes->post('/checkmessages', 'Reports::getMessages');

//report details
$routes->get('/reports/details/(:num)', 'Reportdetails::index/$1');

$routes->post('myorders/dispute', 'Reports::initReport');
$routes->post('myorders/confirmdispute', 'Reports::Report');

$routes->get('baselists/(:any)', 'Baselist::index/$1');
//$routes->get('baselist/fetchTable/(:any)/(:any)', 'Baselist::fetchTable/$1');

//rdp
/**$routes->post('/sellerdashboard/initEditshell', 'Shell::initEdit');
$routes->post('/sellerdashboard/editshell', 'Shell::edit');
$routes->post('/sellerdashboard/rminitshell', 'Shell::rminit');
$routes->post('/sellerdashboard/rmshell', 'Shell::rm');
$routes->post('/sellerdashboard/massinitEditshell', 'Shell::massinitEdit');
$routes->post('/sellerdashboard/masseditshell', 'Shell::massedit');
$routes->post('/sellerdashboard/massrmshell', 'Shell::massrm');
$routes->post('/sellerdashboard/massrmuserinitshell', 'Shell::massrmuserinit');**/


$routes->get('/myorders/downloadcc/(:num)', 'Myorders::downloadcc/$1');
$routes->get('/myorders/((?:[A-Za-z0-9+/]{4})*(?:[A-Za-z0-9+/]{2}==|[A-Za-z0-9+/]{3}=)?)', 'Myorders::index/$1');
$routes->get('/myorders/createLogInit', 'Tikets::createLogInit');
$routes->post('/myorders/createLog', 'Tikets::createLog');
//$routes->post('/cards/initEdit', 'Sellerdashboard::initEdit');
//$routes->post('/cards/edit', 'Sellerdashboard::edit');
// We get a performance increase by specifying the default
// route since we don't have to scan directories.
//$routes->get('/', 'Home::index');
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

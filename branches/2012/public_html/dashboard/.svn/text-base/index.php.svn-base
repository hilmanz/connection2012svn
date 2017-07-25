<?php
/**
* ADMINISTRATION PAGE
* @author Hapsoro Renaldy N <hapsoro.renaldy@winixmedia.com>
*/

include_once "common.php";
//header('Pragma: public');        
//header('Cache-control: private');
//header('Expires: -1');
$view = new BasicView();

$admin = new Admin();
//$admin->DEBUG=true;
//assign sections
if($admin->auth->isLogin()){
	switch($req->getRequest("s")){
        // User
		case "user":
			include_once $APP_PATH."marlboro/dashboard/user.php";
			$dash = new user();
            $view->assign("mainContent",$dash->user());
		break;

        //mobile
		case "mobile":
            include_once $APP_PATH."marlboro/dashboard/mobile.php";
            $dash = new mobile();
            $view->assign("mainContent",$dash->mobile());
        break;


        // Website Section
        case "device-used": 
            include_once $APP_PATH."marlboro/dashboard/website.php";
            $dash = new website($req);
            $view->assign("mainContent",$dash->deviceused());
        break;

        case "top-city": 
            include_once $APP_PATH."marlboro/dashboard/website.php";
            $dash = new website($req);
            $view->assign("mainContent",$dash->topcity());
        break;

        case "top-user": 
            include_once $APP_PATH."marlboro/dashboard/website.php";
            $dash = new website($req);
            $view->assign("mainContent",$dash->topuser());
        break;

        case "auction-history": 
            include_once $APP_PATH."marlboro/dashboard/website.php";
            $dash = new website($req);
            $view->assign("mainContent",$dash->auctionhistory());
        break;

        case "redeem-merchandise": 
            include_once $APP_PATH."marlboro/dashboard/website.php";
            $dash = new website($req);
            $view->assign("mainContent",$dash->redeemmerch());
        break;

        

        case "badges-trading": 
            include_once $APP_PATH."marlboro/dashboard/website.php";
            $dash = new website($req);
            $view->assign("mainContent",$dash->badgestrading());
        break;

        case "badges": 
            include_once $APP_PATH."marlboro/dashboard/website.php";
            $dash = new website($req);
            $view->assign("mainContent",$dash->badges());
        break;

        case "activities": 
            include_once $APP_PATH."marlboro/dashboard/website.php";
            $dash = new website($req);
            $view->assign("mainContent",$dash->activities());
        break;

        case "login-history": 
            include_once $APP_PATH."marlboro/dashboard/website.php";
            $dash = new website($req);
            $view->assign("mainContent",$dash->loginhistory());
        break;

        case "number-of-user":
            include_once $APP_PATH."marlboro/dashboard/website.php";
            $dash = new website($req);
            $view->assign("mainContent",$dash->numberuser());
        break;
        
        

        //////////////////////////////
        // Dashboard section
		default:
			    include_once $APP_PATH."marlboro/dashboard/dashboard.php";
                $dash = new dashboard($req);
                $view->assign("mainContent",$dash->index());
		break;
	}
}else{
    if($_GET['f']==1){
    $view->assign('msg','Invalid login!');
    }
    $view->assign('notlogin',TRUE);
    $view->assign("mainContent",$view->toString("dashboard/login.html"));
}
//assign content to main template
// $admin->show();

// $view->assign('sess',$_SESSION['GM_ADMIN']['username']);
// $view->assign("mainContent",$admin->toString());
//output the populated main template
print $view->toString($MAIN_TEMPLATE);
?>
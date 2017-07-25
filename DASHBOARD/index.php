<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="js/themes/base/jquery.ui.all.css">
	<link rel="stylesheet" type="text/css" href="css/connectionDashboard.css" />
	<link rel="stylesheet" type="text/css" href="css/drop.css" />
	<script src="js/jquery-1.7.2.js"></script>
	<script src="js/ui/jquery.ui.core.js"></script>
	<script src="js/ui/jquery.ui.widget.js"></script>
	<script src="js/ui/jquery.ui.datepicker.js"></script>
    <script src="js/connectionDashboard.js"></script>
	<script type="text/javascript" language="javascript" src="js/hoverIntent.js"></script>
	<script type="text/javascript" language="javascript" src="js/jquery.dropdown.js"></script>
    <script src="js/scroll-startstop.events.jquery.js"></script>
    <!--[if lte IE 7]>
        <link rel="stylesheet" type="text/css" href="css/ie.css" media="screen" />
    <![endif]-->
	<title>MARLBORO CONNECTIONS</title>
</head>

<body>
    <div id="body">
        <div id="universal">
           <div id="header">
                <a id="logo" href="index.php?menu=about">&nbsp;</a>
                <div id="topnav">
                	<ul class="nav">
                    	<li><a class="btnAdministration" href="#">&nbsp;</a></li>
                    	<li><a class="btnLogout" href="#">&nbsp;</a></li>
                    </ul>
                </div>
            </div><!-- end #header -->
            <div id="container">
            	<div class="container">
                    <div id="subnavigation">
                        <ul class="nav dropdown">
                            <li><a href="index.php">Dashboard</a></li>
                            <li class="haveSub"><a href="#">Website</a>
                                  <ul class="sub_menu">
                        			<li id="arrowMenu"></li>
                                	<li><a href="index.php?menu=number-of-user">Number of User</a></li>
                                	<li><a href="index.php?menu=login-history">Login History</a></li>
                                	<li><a href="index.php?menu=activities">Activities</a></li>
                                	<li><a href="index.php?menu=badges">Badges</a></li>
                                	<li><a href="index.php?menu=badges-trading">Badges & Trading</a></li>
                                    <li><a href="index.php?menu=redeem-merchandise">Redeem Merchandise</a></li>
                                    <li><a href="index.php?menu=auction">Auction</a></li>
                                    <li><a href="index.php?menu=auction-history">Auction History</a></li>
                                    <li><a href="index.php?menu=top-user">Top User</a></li>
                                    <li><a href="index.php?menu=top-city">Top City</a></li>
                                    <li><a href="index.php?menu=device-used">Device Used</a></li>
                                </ul>
                            </li>
                            <li><a href="index.php?menu=user">User</a></li>
                            <li><a href="index.php?menu=mobile">Mobile</a></li>
                        </ul>
                    </div>
                    <?php 
                    if($_GET['menu']=='number-of-user'){
                        include("number-of-user.php");
                    }else if($_GET['menu']=='login-history'){ 
                        include("login-history.php");
                    }else if($_GET['menu']=='activities'){ 
                        include("activities.php");
                    }else if($_GET['menu']=='badges'){ 
                        include("badges.php");
                    }else if($_GET['menu']=='badges-trading'){ 
                        include("badges-trading.php");
                    }else if($_GET['menu']=='redeem-merchandise'){ 
                        include("redeem-merchandise.php");
                    }else if($_GET['menu']=='auction-history'){ 
                        include("auction-history.php");
                    }else if($_GET['menu']=='auction'){ 
                        include("auction.php");
                    }else if($_GET['menu']=='top-user'){ 
                        include("top-user.php");
                    }else if($_GET['menu']=='top-city'){ 
                        include("top-city.php");
                    }else if($_GET['menu']=='device-used'){ 
                        include("device-used.php");
                    }else if($_GET['menu']=='user'){ 
                        include("user.php");
                    }else if($_GET['menu']=='mobile'){ 
                        include("mobile.php");
                    }else{ 
                        include("home.php");
                    }?>
                    <div class="backTop">
                    	<a href="javascript:void(0);" id="backTop">Back To Top</a>
                    </div>
                </div><!-- end .container -->
			</div><!-- end #container -->
        </div><!-- end #universal -->
    </div><!-- end #body -->
</body>


</body>
</html>

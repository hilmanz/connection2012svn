<?php /* Smarty version 2.6.13, created on 2012-06-05 10:13:10
         compiled from marlboro/badges-trade.html */ ?>
<div id="tradePage">
    <div id="content">
    	<div class="post postTrade">
        	<form>
        	<table id="listBadges" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top">
                	<div class="listBadges">
                        <div class="titleBar">
                            <h1>MY BADGES</h1>
                        </div>
                        <div class="boxBadges">
                            <div class="theBadges"><img src="img/badge/badge1.png" /></div>
                            <div class="count">5</div>
                            <input type="radio" class="styled" name="badge"/>
                        </div>
                        <div class="boxBadges">
                            <div class="theBadges"><img src="img/badge/badge2.png" /></div>
                            <div class="count">2</div>
                            <input type="radio" class="styled" name="badge"/>
                        </div>
                        <div class="boxBadges">
                            <div class="theBadges"><img src="img/badge/badge3.png" /></div>
                            <div class="count">4</div>
                            <input type="radio" class="styled" name="badge"/>
                        </div>
                        <div class="boxBadges">
                            <div class="theBadges"><img src="img/badge/badge4.png" /></div>
                            <div class="count">0</div>
                            <input type="radio" class="styled" name="badge"/>
                        </div>
                        <div class="boxBadges">
                            <div class="theBadges"><img src="img/badge/badge5.png" /></div>
                            <div class="count">1</div>
                            <input type="radio" class="styled" name="badge"/>
                        </div>
                        <div class="boxBadges">
                            <div class="theBadges"><img src="img/badge/badge6.png" /></div>
                            <div class="count">3</div>
                            <input type="radio" class="styled" name="badge"/>
                        </div>
                        <div class="boxBadges">
                            <div class="theBadges"><img src="img/badge/badge7.png" /></div>
                            <div class="count">7</div>
                            <input type="radio" class="styled" name="badge"/>
                        </div>
                        <div class="boxBadges">
                            <div class="theBadges"><img src="img/badge/badge8.png" /></div>
                            <div class="count">0</div>
                            <input type="radio" class="styled" name="badge"/>
                        </div>
                        <div class="boxBadges">
                            <div class="theBadges"><img src="img/badge/badge9.png" /></div>
                            <div class="count">0</div>
                            <input type="radio" class="styled" name="badge"/>
                        </div>
                        <div class="boxBadges">
                            <div class="theBadges"><img src="img/badge/badge10.png" /></div>
                            <div class="count">1</div>
                            <input type="radio" class="styled" name="badge"/>
                        </div>
                    </div><!-- end .listBadges -->
                </td>
                <td><input type="button" class="tradeNow" onclick="" /></td>
                <td valign="top">
                	<div class="listBadges BadgeNeed">
                        <div class="titleBar">
                            <h1>WHAT I NEED?</h1>
                        </div>
                        <div class="boxBadges">
                            <div class="theBadges"><img src="img/badge/badge1.png" /></div>
                            <input type="radio" class="styled" name="badgeTrade"/>
                        </div>
                        <div class="boxBadges">
                            <div class="theBadges"><img src="img/badge/badge2.png" /></div>
                            <input type="radio" class="styled" name="badgeTrade"/>
                        </div>
                        <div class="boxBadges">
                            <div class="theBadges"><img src="img/badge/badge3.png" /></div>
                            <input type="radio" class="styled" name="badgeTrade"/>
                        </div>
                        <div class="boxBadges">
                            <div class="theBadges"><img src="img/badge/badge4.png" /></div>
                            <input type="radio" class="styled" name="badgeTrade"/>
                        </div>
                        <div class="boxBadges">
                            <div class="theBadges"><img src="img/badge/badge5.png" /></div>
                            <input type="radio" class="styled" name="badgeTrade"/>
                        </div>
                        <div class="boxBadges">
                            <div class="theBadges"><img src="img/badge/badge6.png" /></div>
                            <input type="radio" class="styled" name="badgeTrade"/>
                        </div>
                        <div class="boxBadges">
                            <div class="theBadges"><img src="img/badge/badge7.png" /></div>
                            <input type="radio" class="styled" name="badgeTrade"/>
                        </div>
                        <div class="boxBadges">
                            <div class="theBadges"><img src="img/badge/badge8.png" /></div>
                            <input type="radio" class="styled" name="badgeTrade"/>
                        </div>
                        <div class="boxBadges">
                            <div class="theBadges"><img src="img/badge/badge9.png" /></div>
                            <input type="radio" class="styled" name="badgeTrade"/>
                        </div>
                        <div class="boxBadges">
                            <div class="theBadges"><img src="img/badge/badge10.png" /></div>
                            <input type="radio" class="styled" name="badgeTrade"/>
                        </div>
                    </div><!-- end .listBadges -->
                </td>
              </tr>
            </table>
            </form>
        </div><!-- end .post -->
    </div><!-- end #content -->
</div><!-- end #activity -->
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "marlboro/popup_confirmTrade.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "marlboro/popup_finishTrade.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
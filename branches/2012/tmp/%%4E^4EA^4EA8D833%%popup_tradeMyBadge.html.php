<?php /* Smarty version 2.6.13, created on 2012-06-08 14:28:47
         compiled from marlboro/popup_tradeMyBadge.html */ ?>
<div id="popupTradeMyBadge" class="popupContainer">
	<div id="popupHead">
    	<div id="popupBottom">
        	<div id="popupContent" class="popupTradeMyBadge">
            	<div class="selotip"></div>
                <a class="closePopup" href="#">&nbsp;</a>
                <div class="poupContent">
                	<h1>CONFIRM REQUEST</h1>
                	<table border="0" cellspacing="0" cellpadding="0" height="200">
                      <tr>
                        <td><img src="img/badge/big/badge<?php echo $this->_tpl_vars['badge']; ?>
.png" /></td>
                        <td><input type="button" class="tradeOK" onClick="submitTrade()" /></td>
                        <td><img src="img/badge/big/badge<?php echo $this->_tpl_vars['want']; ?>
.png" /></td>
                      </tr>
                    </table>
                    <div class="boxTrader">
                        <div class="thumbTrader">
                            <a href="#"><img src="img/photo.jpg" /></a>
                        </div><!-- end .thumbTrader -->
                        <div class="boxName">
                            <span class="nameTrader" id="chosenTrader" ></span>
                            <a class="tradeMyBadge" href="#popupTradeMyBadge"></a>
                        </div><!-- end .boxName -->
                    </div><!-- end .boxTrader -->
                </div><!-- end .poupContent -->
            </div><!-- end #poupContent -->
        </div><!-- end #popupBottom -->
    </div><!-- end #popupHead -->
</div><!-- end .popupContainer -->
<div id="bgPopup"></div>
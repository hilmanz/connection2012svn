<?php /* Smarty version 2.6.13, created on 2012-06-20 18:51:45
         compiled from marlboro/popup_photo.html */ ?>
<div id="popupPhoto" class="popupContainer">
	<div id="popupHead">
    	<div id="popupBottom">
        	<div id="popupContent">
            	<div class="selotip"></div>
                <a class="closePopup" href="#">&nbsp;</a>
                <div class="changePhotoContent">
                	<h1>CHANGE PHOTO</h1>
                	<div id="thumbPhotoBig">
                    	<a href="#"><img src="<?php if ($this->_tpl_vars['avatar_med'] != ''):  echo $this->_tpl_vars['avatar_med'];  else: ?>img/photo.jpg<?php endif; ?>" /></a>
                    </div>
                    <form id="fphoto" method="post" action="index.php?page=myprofile&act=upload" class="uploadFoto" enctype="multipart/form-data">
                    	<label>BROWSE FILE</label>
                    	<input name="avatar" id="avatar" type="file" />
                        <input type="submit" class="btnupload" value="&nbsp;" />
                    </form>
                </div><!-- end .poupContent -->
            </div><!-- end #poupContent -->
        </div><!-- end #popupBottom -->
    </div><!-- end #popupHead -->
</div><!-- end .popupContainer -->
<div id="bgPopup"></div>
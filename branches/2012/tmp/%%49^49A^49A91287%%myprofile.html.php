<?php /* Smarty version 2.6.13, created on 2012-06-20 18:51:45
         compiled from marlboro/myprofile.html */ ?>
<div id="myprofile">
    <div id="content">
    	<div class="post postMyprofile">
        	<div id="card">
            	<div class="card">
                	<div id="thumbPhoto">
                    	<a href="#"><img src="<?php if ($this->_tpl_vars['avatar_sm'] != ''):  echo $this->_tpl_vars['avatar_sm'];  else: ?>img/photo.jpg<?php endif; ?>" /></a>
                    </div><!-- end #thumbPhoto -->
                    <div id="dataProfile">
                    	<p class="bold">Name</p>
                    	<p class="username"><?php echo $this->_tpl_vars['name']; ?>
</p>
                    	<p class="bold">AGE</p>
                    	<p class="age"><?php echo $this->_tpl_vars['age']; ?>
</p>
                    	<p class="bold">TOWN</p>
                    	<p class="towns"><?php echo $this->_tpl_vars['kota']; ?>
</p>
                    	<p class="bold">connection date</p>
                    	<p class="connectDate"><?php echo $this->_tpl_vars['date']; ?>
</p>
                    	<p class="bold">BADGE VALUE</p>
                    	<?php if ($this->_tpl_vars['totalBadge']): ?><p class="values"><?php echo $this->_tpl_vars['totalBadge']; ?>
</p><?php else: ?><p class="connectDate">You don't have badges</p><?php endif; ?>
                    </div><!-- end #dataProfile -->
                    <div id="piala">
                    	                    </div><!-- end #piala -->
                </div><!-- end .card -->
            </div><!-- end #card -->
            <div id="actProfile">
            	<a href="#popupPhoto" class="changePhoto">Change Photo</a>
            	<a href="#" onClick="(alert('LINK TO MOP!'))" class="editProfile">Edit Profile</a>
            	<a href="index.php?page=redeem" class="redeemBadge">Redeem Badge</a>
            </div><!-- end #activity -->
            <div id="badgeList">
				<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['badge']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
            	<div class="badgeBox">
            		<a class="badgesIcon tip_trigger" href="#"><img src="img/badge/badge<?php echo $this->_tpl_vars['badge'][$this->_sections['i']['index']]['badge_id']; ?>
.png" /><?php if ($this->_tpl_vars['badge'][$this->_sections['i']['index']]['description'] != ''): ?><span class="tip"><span class="arrow"></span><?php echo $this->_tpl_vars['badge'][$this->_sections['i']['index']]['description']; ?>
</span><?php endif; ?></a>
            		<div class="badgesCount"><?php echo $this->_tpl_vars['badge'][$this->_sections['i']['index']]['total']; ?>
</div>
                </div><!-- end .badgeBox -->
            	<?php endfor; endif; ?>
            </div><!-- end #badgeList -->
        </div><!-- end .post -->
    </div><!-- end #content -->
</div><!-- end #activity -->
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "marlboro/popup_photo.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 defined('_JEXEC') or die('Restricted access'); 

?><div id="phocadownload-comments"><?php
	
	$uri 		= \Joomla\CMS\Uri\Uri::getInstance();
	$getParamsArray = explode(',', 'start,limitstart,template,fb_comment_id');
	if (!empty($getParamsArray) ) {
		foreach($getParamsArray as $key => $value) {
			$uri->delVar($value);
		}
	}
	
	if ($this->t['fb_comment_app_id'] == '') {
		echo JText::_('COM_PHOCADOWNLOAD_ERROR_FB_APP_ID_EMPTY');
	} else {
	
		$cCount = '';
		if ((int)$this->t['fb_comment_count'] > 0) {
			$cCount = 'numposts="'.$this->t['fb_comment_count'].'"';
		}

?><fb:comments href="<?php echo $uri->toString(); ?>" simple="1" <?php echo $cCount;?> width="<?php echo (int)$this->t['fb_comment_width'] ?>"></fb:comments>
<div id="fb-root"></div>
<script type="text/javascript">
  window.fbAsyncInit = function() {
   FB.init({
     appId: '<?php echo $this->t['fb_comment_app_id'] ?>',
     status: true,
	 cookie: true,
     xfbml: true
   });
 }; 
  (function() {
    var e = document.createElement('script');
    e.type = 'text/javascript';
    e.src = document.location.protocol + '//connect.facebook.net/<?php echo $this->t['fb_comment_lang']; ?>/all.js';
    e.async = true;
    document.getElementById('fb-root').appendChild(e);
   }());
</script>
<?php } ?>
</div>

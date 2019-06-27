<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Installer.webinstaller
 *
 * @copyright   Copyright (C) 2013 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/** @var PlgInstallerWebinstaller $this */

$dir         = $this->isRTL() ? ' dir="ltr"' : '';
$installfrom = $this->getInstallFrom();

?>

<div class="clr"></div>
<fieldset class="uploadform">
	<legend><?php echo Text::_('COM_INSTALLER_INSTALL_FROM_WEB', true); ?></legend>
	<div id="jed-container"<?php echo $dir; ?>>
		<div id="mywebinstaller" style="display:none">
			<a href="#"><?php echo Text::_('COM_INSTALLER_WEBINSTALLER_LOAD_APPS'); ?></a>
		</div>
		<div class="well" id="web-loader" style="display:none">
			<h2><?php echo Text::_('COM_INSTALLER_WEBINSTALLER_INSTALL_WEB_LOADING'); ?></h2>
		</div>
		<div class="alert alert-error" id="web-loader-error" style="display:none">
			<a class="close" data-dismiss="alert">×</a><?php echo Text::_('COM_INSTALLER_WEBINSTALLER_INSTALL_WEB_LOADING_ERROR'); ?>
		</div>
	</div>
	<fieldset class="uploadform" id="uploadform-web" style="display:none"<?php echo $dir; ?>>
		<div class="control-group">
			<strong><?php echo Text::_('COM_INSTALLER_WEBINSTALLER_INSTALL_WEB_CONFIRM'); ?></strong><br />
			<span id="uploadform-web-name-label"><?php echo Text::_('COM_INSTALLER_WEBINSTALLER_INSTALL_WEB_CONFIRM_NAME'); ?>:</span> <span id="uploadform-web-name"></span><br />
			<?php echo Text::_('COM_INSTALLER_WEBINSTALLER_INSTALL_WEB_CONFIRM_URL'); ?>: <span id="uploadform-web-url"></span>
		</div>
		<div class="form-actions">
			<input type="button" class="btn btn-primary" value="<?php echo Text::_('COM_INSTALLER_INSTALL_BUTTON'); ?>" onclick="Joomla.submitbutton<?php echo $installfrom != '' ? 4 : 5; ?>()" />
			<input type="button" class="btn btn-secondary" value="<?php echo Text::_('JCANCEL'); ?>" onclick="Joomla.installfromwebcancel()" />
		</div>
	</fieldset>
</fieldset>

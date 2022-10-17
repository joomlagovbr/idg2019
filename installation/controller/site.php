<?php
/**
 * @package     Joomla.Installation
 * @subpackage  Controller
 *
 * @copyright   (C) 2013 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Controller class to set the site data for the Joomla Installer.
 *
 * @since  3.1
 */
class InstallationControllerSite extends JControllerBase
{
	/**
	 * Execute the controller.
	 *
	 * @return  void
	 *
	 * @since   3.1
	 */
	public function execute()
	{
		// Get the application
		/** @var InstallationApplicationWeb $app */
		$app = $this->getApplication();

		// Check for request forgeries.
		JSession::checkToken() or $app->sendJsonResponse(new Exception(JText::_('JINVALID_TOKEN_NOTICE'), 403));

		// Get the setup model.
		$model = new InstallationModelSetup;

		// Check the form
		$model->checkForm('site');

		// Redirect to the page.
		$r = new stdClass;
		$r->view = 'database';
		$app->sendJsonResponse($r);
	}
}

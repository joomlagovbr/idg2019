<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );


if (! class_exists('PhocaDownloadLoader')) {
    require_once( JPATH_ADMINISTRATOR.'/components/com_phocadownload/libraries/loader.php');
}
// Require the base controller
require_once( JPATH_COMPONENT.'/controller.php' );

phocadownloadimport('phocadownload.utils.settings');
phocadownloadimport('phocadownload.utils.utils');
phocadownloadimport('phocadownload.path.path');
phocadownloadimport('phocadownload.path.route');
phocadownloadimport('phocadownload.render.layout');
phocadownloadimport('phocadownload.file.file');
phocadownloadimport('phocadownload.file.fileupload');
phocadownloadimport('phocadownload.file.fileuploadmultiple');
phocadownloadimport('phocadownload.file.fileuploadsingle');
phocadownloadimport('phocadownload.download.download');
phocadownloadimport('phocadownload.render.renderfront');
phocadownloadimport('phocadownload.rate.rate');
phocadownloadimport('phocadownload.stat.stat');
phocadownloadimport('phocadownload.mail.mail');
phocadownloadimport('phocadownload.pagination.pagination');
phocadownloadimport('phocadownload.ordering.ordering');
phocadownloadimport('phocadownload.access.access');
phocadownloadimport('phocadownload.category.category');
phocadownloadimport('phocadownload.user.user');
phocadownloadimport('phocadownload.log.log');
phocadownloadimport('phocadownload.utils.utils');

jimport( 'joomla.filesystem.folder' ); 
jimport( 'joomla.filesystem.file' );


// Require specific controller if requested
if($controller = JFactory::getApplication()->input->get('controller')) {
    $path = JPATH_COMPONENT.'/controllers/'.$controller.'.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}

$classname    = 'PhocaDownloadController'.ucfirst($controller);
$controller   = new $classname( );
$controller->execute( JFactory::getApplication()->input->get('task') );
$controller->redirect();
?>
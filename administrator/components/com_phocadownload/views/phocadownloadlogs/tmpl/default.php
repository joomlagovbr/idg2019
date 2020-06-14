<?php
/*
 * @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
$class		= $this->t['n'] . 'RenderAdminViews';
$r 			=  new $class();
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', $this->t['o']);
$saveOrder	= $listOrder == 'a.ordering';
if ($saveOrder) {
	$saveOrderingUrl = 'index.php?option='.$this->t['o'].'&task='.$this->t['tasks'].'.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'categoryList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);
}
$sortFields = $this->getSortFields();

echo $r->jsJorderTable($listOrder);



echo $r->startForm($this->t['o'], $this->t['tasks'], 'adminForm');




echo $r->startFilter();
//echo $r->selectFilterLanguage('JOPTION_SELECT_LANGUAGE', $this->state->get('filter.language'));
//echo $r->selectFilterCategory(PhocaDownloadCategory::options($this->t['o']), 'JOPTION_SELECT_CATEGORY', $this->state->get('filter.category_id'));
echo $r->endFilter();

echo $r->startMainContainer();

if ($this->t['p']->get('enable_logging', 0) == 0) {
	echo '<div class="alert"><a class="close" data-dismiss="alert" href="#">&times;</a>'. JText::_('COM_PHOCADOWNLOAD_LOGGING_NOT_ENABLED').'</div>';
}

echo $r->startFilterBar();
echo $r->inputFilterSearch($this->t['l'].'_FILTER_SEARCH_LABEL', $this->t['l'].'_FILTER_SEARCH_DESC',
							$this->escape($this->state->get('filter.search')));
echo $r->inputFilterSearchClear('JSEARCH_FILTER_SUBMIT', 'JSEARCH_FILTER_CLEAR');
echo $r->inputFilterSearchLimit('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC', $this->pagination->getLimitBox());
echo $r->selectFilterDirection('JFIELD_ORDERING_DESC', 'JGLOBAL_ORDER_ASCENDING', 'JGLOBAL_ORDER_DESCENDING', $listDirn);
echo $r->selectFilterSortBy('JGLOBAL_SORT_BY', $sortFields, $listOrder);

echo $r->startFilterBar(2);
echo $r->selectFilterType($this->t['l'].'_SELECT_TYPE', $this->state->get('filter.type'), array(1 => JText::_($this->t['l'].'_DOWNLOADS'), 2 =>JText::_($this->t['l'].'_UPLOADS')));
echo $r->endFilterBar();


echo $r->endFilterBar();		

echo $r->startTable('categoryList');

echo $r->startTblHeader();

echo '<th></th>';//$r->thOrdering('JGRID_HEADING_ORDERING', 0,0);
echo $r->thCheck('JGLOBAL_CHECK_ALL');
echo '<th class="ph-date">'.JHTML::_('grid.sort', $this->t['l'].'_DATE', 'a.date', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-uploaduser">'.JHTML::_('grid.sort', $this->t['l'].'_USER', 'username', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-ip">'.JHTML::_('grid.sort', $this->t['l'].'_IP', 'a.ip', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-file-short">'.JHTML::_('grid.sort',  	$this->t['l'].'_FILE', 'filename', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-catid">'.JHTML::_('grid.sort',  	$this->t['l'].'_CATEGORY', 'category_id', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-page">'.JHTML::_('grid.sort', $this->t['l'].'_PAGE', 'a.page', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-type">'.JHTML::_('grid.sort', $this->t['l'].'_TYPE', 'a.type', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-id">'.JHTML::_('grid.sort', $this->t['l'].'_ID', 'a.id', $listDirn, $listOrder ).'</th>'."\n";

echo $r->endTblHeader();
			
echo '<tbody>'. "\n";

$originalOrders = array();	
$parentsStr 	= "";		
$j 				= 0;

if (is_array($this->items)) {
	foreach ($this->items as $i => $item) {
		//if ($i >= (int)$this->pagination->limitstart && $j < (int)$this->pagination->limit) {
			$j++;
/*
$urlEdit		= 'index.php?option='.$this->t['o'].'&task='.$this->t['task'].'.edit&id=';
$urlTask		= 'index.php?option='.$this->t['o'].'&task='.$this->t['task'];
$orderkey   	= array_search($item->id, $this->ordering[$item->catid]);		
$ordering		= ($listOrder == 'a.ordering');			
$canCreate		= $user->authorise('core.create', $this->t['o']);
$canEdit		= $user->authorise('core.edit', $this->t['o']);
$canCheckin		= $user->authorise('core.manage', 'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
$canChange		= $user->authorise('core.edit.state', $this->t['o']) && $canCheckin;
$linkEdit 		= JRoute::_( $urlEdit. $item->id );

$linkCat	= JRoute::_( 'index.php?option='.$this->t['o'].'&task='.$this->t['c'].'cat.edit&id='.(int) $item->category_id );
$canEditCat	= $user->authorise('core.edit', $this->t['o']);*/


$iD = $i % 2;
echo "\n\n";
echo '<tr class="row'.$iD.'" sortable-group-id="0" item-id="0" parents="0" level="0">'. "\n";

echo $r->tdOrder(0,0,0);
echo $r->td(JHtml::_('grid.id', $i, $item->id), "small hidden-phone");


echo $r->td($this->escape($item->date));

$usrO = $item->usernameno;
if ($item->username) {$usrO = $usrO . ' ('.$item->username.')';}
if (!$usrO) {
	$usrO = JText::_('COM_PHOCADOWNLOAD_GUEST');
}
echo $r->td($usrO, "small hidden-phone");
					
echo $r->td($this->escape($item->ip));

//echo $r->td($this->escape($item->filetitle));
echo $r->td($this->escape($item->file_title) . ' ('.$this->escape($item->filename) . ')');

echo $r->td($this->escape($item->category_id));
echo $r->td('<span class="editlinktip hasTip" title="'. JText::_( $this->t['l'].'_PAGE' ).'::'. $this->escape($item->page).'">'
			.'<a href="javascript:void(0);" >'. JText::_( $this->t['l'].'_PAGE' ).'</a></span>');

if ($item->type == 2) {
	echo $r->td('<span class="label label-warning">'.JText::_($this->t['l'].'_UPLOAD').'</span>', "small hidden-phone");
} else {
	echo $r->td('<span class="label label-success">'.JText::_($this->t['l'].'_DOWNLOAD').'</span>', "small hidden-phone");
}

echo $r->td($item->id, "small hidden-phone");

echo '</tr>'. "\n";
						
		//}
	}
}
echo '</tbody>'. "\n";

echo $r->tblFoot($this->pagination->getListFooter(), 15);
echo $r->endTable();

//echo $r->formInputs($listOrder, $originalOrders);
echo $r->formInputs($listOrder, $listDirn, $originalOrders);
echo $r->endMainContainer();
echo $r->endForm();
?>

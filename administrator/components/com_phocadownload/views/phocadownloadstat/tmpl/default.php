<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
//$class		= $this->t['n'] . 'RenderAdminViews';
$r 			=  new PhocaDownloadRenderAdminViews();
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', $this->t['o']);
$saveOrder	= $listOrder == 'a.ordering';
if ($saveOrder) {
	$saveOrderingUrl = 'index.php?option='.$this->t['o'].'&task='.$this->t['task'].'.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'categoryList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);
}
$sortFields = $this->getSortFields();

echo $r->jsJorderTable($listOrder);

echo '<div class="clearfix"></div>';

echo $r->startForm($this->t['o'], $this->t['task'], 'adminForm');
echo $r->startFilter();
//echo $r->startFilter($this->t['l'].'_FILTER');
//echo $r->selectFilterPublished('JOPTION_SELECT_PUBLISHED', $this->state->get('filter.state'));
//echo $r->selectFilterLanguage('JOPTION_SELECT_LANGUAGE', $this->state->get('filter.language'));
echo $r->endFilter();

echo $r->startMainContainer();
echo $r->startFilterBar();
echo $r->inputFilterSearch($this->t['l'].'_FILTER_SEARCH_LABEL', $this->t['l'].'_FILTER_SEARCH_DESC',
							$this->escape($this->state->get('filter.search')));
echo $r->inputFilterSearchClear('JSEARCH_FILTER_SUBMIT', 'JSEARCH_FILTER_CLEAR');
echo $r->inputFilterSearchLimit('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC', $this->pagination->getLimitBox());
echo $r->selectFilterDirection('JFIELD_ORDERING_DESC', 'JGLOBAL_ORDER_ASCENDING', 'JGLOBAL_ORDER_DESCENDING', $listDirn);
echo $r->selectFilterSortBy('JGLOBAL_SORT_BY', $sortFields, $listOrder);
echo $r->endFilterBar();

echo $r->startTable('categoryList');

echo $r->startTblHeader();

//echo $r->thOrdering('JGRID_HEADING_ORDERING', $listDirn, $listOrder);
echo '<th class="nowrap center hidden-phone ph-ordering"></th>';
//echo $r->thCheck('JGLOBAL_CHECK_ALL');
echo '<th class=""></th>'."\n";
echo '<th class="ph-title">'.JHTML::_('grid.sort',  	$this->t['l'].'_TITLE', 'a.title', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-filename-long">'.JHTML::_('grid.sort',  	$this->t['l'].'_FILENAME', 'a.filename', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-hits">'.JHTML::_('grid.sort',  		$this->t['l'].'_DOWNLOADS', 'a.hits', $listDirn, $listOrder ).'</th>'."\n";

echo $r->endTblHeader();


echo '<tbody>'. "\n";

$color 	= 0;
$colors = array (
'#FF8080','#FF9980','#FFB380','#FFC080','#FFCC80','#FFD980','#FFE680','#FFF280','#FFFF80','#E6FF80',
'#CCFF80','#99FF80','#80FF80','#80FFC9','#80FFFF','#80C9FF','#809FFF','#9191FF','#AA80FF','#B580FF',
'#D580FF','#FF80FF','#FF80DF','#FF80B8');

$originalOrders = array();
$parentsStr 	= "";
$j 				= 0;

if (is_array($this->items)) {
	foreach ($this->items as $i => $item) {
		//if ($i >= (int)$this->pagination->limitstart && $j < (int)$this->pagination->limit) {
			if ($item->textonly == 0) {
				$j++;


$urlEdit		= 'index.php?option='.$this->t['o'].'&task='.$this->t['task'].'.edit&id=';
$urlTask		= 'index.php?option='.$this->t['o'].'&task='.$this->t['task'];
$orderkey   	= array_search($item->id, $this->ordering[0]);
$ordering		= ($listOrder == 'a.ordering');
$canCreate		= $user->authorise('core.create', $this->t['o']);
$canEdit		= $user->authorise('core.edit', $this->t['o']);
$canCheckin		= $user->authorise('core.manage', 'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
$canChange		= $user->authorise('core.edit.state', $this->t['o']) && $canCheckin;
$linkEdit 		= JRoute::_( $urlEdit. $item->id );


$iD = $i % 2;
echo "\n\n";
echo '<tr class="row'.$iD.'" sortable-group-id="'.$item->category_id.'" item-id="'.$item->id.'" parents="'.$item->category_id.'" level="0">'. "\n";

echo $r->tdOrder($canChange, $saveOrder, $orderkey, $item->ordering);
//echo $r->td(JHtml::_('grid.id', $i, $item->id), "small hidden-phone");
echo $r->td('');
$checkO = '';
/*if ($item->checked_out) {
	$checkO .= JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, $this->t['tasks'].'.', $canCheckin);
}
if ($canCreate || $canEdit) {
	$checkO .= '<a href="'. JRoute::_($linkEdit).'">'. $this->escape($item->title).'</a>';
} else {*/
	$checkO .= $this->escape($item->title);
//}
$checkO .= '<br /><span class="smallsub">(<span>'.JText::_($this->t['l'].'_FIELD_ALIAS_LABEL').':</span>'. $this->escape($item->alias).')</span>';
echo $r->td($checkO, "small hidden-phone ph-wrap-12");

echo $r->td($item->filename, 'ph-wrap-12');


if ((int)$this->maxandsum->maxhit == 0) {
	$per = 0;
	$perOutput = 0;
} else {
	$per 		= round((int)$item->hits / (int)$this->maxandsum->maxhit * 500);
	$perOutput 	= round((int)$item->hits / (int)$this->maxandsum->sumhit * 100);
}

echo '<td>';
echo '<div style="background:'.$colors[$color].' url(\''. JURI::root(true).'/media/com_phocadownload/images/white-space.png'.'\') '.$per.'px 0px no-repeat;width:500px;padding:5px 0px;margin:5px 0px;border:1px solid #ccc;">';
//	echo '<small style="color:#666666">['. $row->id .']</small>';
echo '<div> &nbsp;'.$item->hits.' ('.$perOutput .' %) &nbsp;</div>';
echo '</div>';
echo '</td></tr>';

$color++;
if ($color > 23) {
	$color = 0;
}



echo '</tr>'. "\n";
			}
		//}
	}
}
echo '</tbody>'. "\n";

echo $r->tblFoot($this->pagination->getListFooter(), 5);
echo $r->endTable();

//echo $r->formInputs($listOrder, $originalOrders);
echo $r->formInputs($listOrder, $listDirn, $originalOrders);
echo $r->endMainContainer();
echo $r->endForm();
?>

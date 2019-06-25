<?php
/**
 * @package		
 * @subpackage	
 * @copyright	
 * @license		
 */

// no direct access
defined('_JEXEC') or die;
require __DIR__.'/_helper.php';
$lang = JFactory::getLanguage();
$upper_limit = $lang->getUpperLimitSearchWord();

?>
<h2>
	<?php if ($this->escape($this->params->get('page_heading'))) :?>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	<?php else : ?>
		<?php echo $this->escape($this->params->get('page_title')); ?>
	<?php endif; ?>
</h2>

<div class="row search" style="clear: both;">
	<form id="searchForm" action="<?php echo JRoute::_('index.php?option=com_search');?>" method="post">
		<fieldset class="word">
			<div class="form-group row">
				<label for="staticEmail" class="col-md-2 col-form-label ">Estou pesquisando</label>
				<div class="col-md-10">
					<input type="text" name="searchword" id="search-searchword" size="30" maxlength="<?php echo $upper_limit; ?>" value="<?php echo $this->escape($this->origkeyword); ?>" class="form-control " id="staticEmail">
              		
				</div>
			</div>
			<div class="form-group row">
				<label for="inlineFormCustomSelectPref" class="col-md-1 col-form-label">sendo</label>
				<div class="col-md-3">
					<?php TemplateSearchHelper::displaySearchPhrase(); ?>
				</div>

				<label for="ordem" class="col-md-2 col-form-label alinha-direita">e ordenado por</label>
				<div class="col-md-4">
					<?php TemplateSearchHelper::displaySearchOrdering(); ?>
				</div>
				<div class="col-md-2">
					<button class="botao-busca" type="button" onclick="this.form.submit()" class="btn"><?php echo JText::_('COM_SEARCH_SEARCH');?></button>
				</div>
				<input type="hidden" name="task" value="search" />
			</div>
		</fieldset>

		<!--
		<?php if ($this->params->get('search_areas', 1)) : ?>
			<fieldset class="only">
			<legend><?php echo JText::_('COM_SEARCH_SEARCH_ONLY');?></legend>
			<?php TemplateSearchHelper::displaySearchOnly( $this->searchareas ); ?>				
			</fieldset>
		<?php endif; ?>	 
		-->

	</form>
</div>	

<div class="<?php echo $this->params->get('pageclass_sfx'); ?>">
	<?php if (!empty($this->searchword)):?>
	<p class="description"><?php echo JText::plural('COM_SEARCH_SEARCH_KEYWORD_N_RESULTS', $this->total);?></p>
	<?php endif;?>
</div>

<div class="row">
	<?php if ($this->error==null && count($this->results) > 0) :
		echo $this->loadTemplate('results');
	else :
		echo $this->loadTemplate('error');
	endif; ?>
</div>

<?php if ($this->total > 0) : ?>
	<fieldset class="fieldset-limitbox">
		<legend><?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?></legend>
		<label for="limit" class="hide">
			<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
		</label>
		<?php echo $this->pagination->getLimitBox(); ?>			
	</fieldset>
<?php endif; ?>


<?php if ($this->total > 0) : ?>
	<div class="row-fluid">
		<div class="span9">				
			<div class="row-fluid">
				<div class="pagination text-center">
					<?php echo $this->pagination->getPagesLinks(); ?>
				</div>
			</div>
			<div class="row-fluid text-center">
			<p class="counter">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
			</div>			
		</div>
	</div>					
<?php endif; ?>

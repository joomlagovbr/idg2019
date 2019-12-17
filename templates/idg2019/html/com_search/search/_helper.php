<?php
/**
 * @package		
 * @subpackage	
 * @copyright	
 * @license		
 */

// no direct access
defined('_JEXEC') or die;


class TemplateSearchHelper {

	static function displaySearchPhrase() {  //TemplateSearchHelper::displaySearchPhrase()

		$searchphrases		= array();

		$searchphrases[]	= JHtml::_('select.option',  'all', JText::_('COM_SEARCH_ALL_WORDS'));
		$searchphrases[]	= JHtml::_('select.option',  'any', JText::_('COM_SEARCH_ANY_WORDS'));
		$searchphrases[]	= JHtml::_('select.option',  'exact', JText::_('COM_SEARCH_EXACT_PHRASE'));

		$input = JFactory::getApplication()->input;
		$match = $input->get('searchphrase', 'all', 'string');

		?>
		<select name="searchphrase" class="custom-select" id="searchphrase-id<?php echo $k ?>">
		
			<?php
			foreach($searchphrases as $k => $search){
			?>
				<option value="<?php echo $search->value ?>" <?php if($match==$search->value): ?>selected="selected"<?php endif; ?>><?php echo $search->text; ?></option>
			<?php }	?>		
		
		</select>
	<?php
	}

	static function displaySearchOrdering() {  //TemplateSearchHelper::displaySearchOrdering()

		$searchordering		= array();

		$searchordering[] = JHtml::_('select.option', 'newest', JText::_('COM_SEARCH_NEWEST_FIRST'));
		$searchordering[] = JHtml::_('select.option', 'oldest', JText::_('COM_SEARCH_OLDEST_FIRST'));
		$searchordering[] = JHtml::_('select.option', 'popular', JText::_('COM_SEARCH_MOST_POPULAR'));
		$searchordering[] = JHtml::_('select.option', 'alpha', JText::_('COM_SEARCH_ALPHABETICAL'));
		$searchordering[] = JHtml::_('select.option', 'category', JText::_('JCATEGORY'));

		$input = JFactory::getApplication()->input;
		$match = $input->get('ordering', 'all', 'string');

		?>
		<select id="ordering" name="ordering" class="custom-select">
		
			<?php
			foreach($searchordering as $k => $search){
			?>
				<option value="<?php echo $search->value ?>"<?php if($match==$search->value): ?>selected="selected"<?php endif; ?>><?php echo $search->text; ?></option>
			<?php }	?>		
		
		</select>
	<?php
	}

	static function displaySearchOnly( $searchareas = array() ) {
		foreach ($searchareas['search'] as $val => $txt):
			$checked = is_array($searchareas['active']) && in_array($val, $searchareas['active']) ? 'checked="checked"' : '';
			?>
			<label for="area-<?php echo $val;?>" class="checkbox">
				<input type="checkbox" name="areas[]" value="<?php echo $val;?>" id="area-<?php echo $val;?>" <?php echo $checked;?> />
				<?php echo JText::_($txt); ?>
			</label>
		<?php endforeach;
	}

	static function displayMetakeyLinks( $metakey, $link = '', $searchword = '' )
	{
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$itemid = $jinput->get('Itemid', 0, 'integer');
		$menu = $app->getMenu();

		if(empty($link)){
			$link = 'index.php?ordering=newest&searchphrase=all&Itemid='.$menu->getItem($itemid)->id.'&option=com_search&searchword=';
		}


		$keys = explode(',', $metakey);
		$count_keys = count($keys);
		$lang = JFactory::getLanguage();

		if(count($keys)==1)
		{				
			$keys =  explode(';', $metakey);
			$count_keys = count($keys);
		}
		for ($i=1; $i <= $count_keys; $i++) { 
			if($i!=$count_keys)
				$separator = '';
			else
				$separator = '';

			if(trim($keys[$i-1]) != ''):
				$search_formated = urlencode(substr(trim($keys[$i-1]),0, $lang->getUpperLimitSearchWord()));
			?>
			<span class="data-noticia">
				<a href="<?php echo JRoute::_($link . $search_formated); ?>" class="link-categoria">
					<?php
					$keys[$i-1] = str_ireplace($searchword, '<span class="highlight">'.$searchword.'</span>', $keys[$i-1]);
					if(strpos($keys[$i-1], '<span class="highlight">')!==false)
						$replace = true;
					else
						$replace = false;
					?>
					<?php if(strtolower($searchword) == strtolower(trim($keys[$i-1])) && $replace == false): ?><span class="highlight"><?php endif; ?>
					<?php echo trim($keys[$i-1]); ?>
					<?php if(strtolower($searchword) == strtolower(trim($keys[$i-1])) && $replace == false): ?></span><?php endif; ?>
				</a>
				<?php echo $separator; ?>
			</span>
			<?php
			endif;
		}
	}
}
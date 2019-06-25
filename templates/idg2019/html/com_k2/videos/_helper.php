<?php 

class TemplateK2CategoryHelper {

	static function getCategories( $parent )
	{
		$db = JFactory::getDBO();
		$ids = array();
	
		$query = $db->getQuery(true);
		$query->select('id,name');
		$query->from('#__k2_categories');
		$query->where('parent IN ('.$parent.') and published = 1');			
		$db->setQuery($query);
		$result = $db->loadRowList();

		return($result);
		
	}

}

?>
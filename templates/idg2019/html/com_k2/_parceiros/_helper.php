<?php 

class TemplateK2CategoryHelper {

	static function getCategories( $parent )
	{
		$db = JFactory::getDBO();
		$ids = array();
	
		$query = $db->getQuery(true);
		$query->select('id,name');
		$query->from('#__k2_categories');
		$query->where('parent IN ('.$parent.')');			
		$db->setQuery($query);
		$result = $db->loadRowList();

		return($result);
		
	}

}

?>
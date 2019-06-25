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
$category_alias_layout = TemplateContentArticleHelper::getTemplateByCategoryAlias( $this->item );

//modificacoes relativas � publica��o no facebook
$current_url = JURI::getInstance()->toString();
$this->document->addCustomTag('<meta property="og:url" content="'. $current_url .'" />');
$this->document->addCustomTag('<meta property="og:type" content="article" />');
$this->document->addCustomTag('<meta property="og:title" content="'. $this->escape($this->item->title) .'" />');

//alteração para pegar imagens do introimages do artigo.
//$img_tmb = TemplateContentArticleHelper::customImg($this->item->text);
$img_tmb = TemplateContentArticleHelper::customImg(json_decode($this->item->images)->image_intro);
JFactory::getDocument()->addCustomTag('<meta property="og:image" content="'.$img_tmb.'" />');


if( $category_alias_layout !== false )
{
	$this->setLayout( $category_alias_layout );
	require __DIR__.'/'. $category_alias_layout .'.php';
}
else
{
	require __DIR__.'/default_.php';
}
// uteis para debug:
// JFactory::getApplication()->getTemplate();
// $this->getLayout();
// $this->getLayoutTemplate();
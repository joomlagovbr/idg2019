<?php
/**
 * @package         Articles Anywhere
 * @version         10.1.4
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2020 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Plugin\System\ArticlesAnywhere;

defined('_JEXEC') or die;

use Joomla\CMS\Factory as JFactory;
use RegularLabs\Library\Article as RL_Article;
use RegularLabs\Library\Document as RL_Document;
use RegularLabs\Library\Html as RL_Html;

/**
 * Plugin that replaces stuff
 */
class Helper
{

	public function onContentPrepare($context, &$article, &$params)
	{
		$area    = isset($article->created_by) ? 'article' : 'other';
		$context = (($params instanceof \JRegistry) && $params->get('rl_search')) ? 'com_search.' . $params->get('readmore_limit') : $context;

		if ( ! isset($article->id) && isset($article->slug))
		{
			$slug_parts = explode(':', $article->slug);
			$article_id = array_shift($slug_parts);
			if (is_numeric($article_id))
			{
				$article->id = $article_id;
			}
		}

		RL_Article::process($article, $context, $this, 'replaceTags', [$area, $context, $article]);
	}

	public function onAfterDispatch()
	{
		if ( ! $buffer = RL_Document::getBuffer())
		{
			return;
		}

		if ( ! Replace::replaceTags($buffer, 'component'))
		{
			return;
		}

		RL_Document::setBuffer($buffer);
	}

	public function onAfterRender()
	{
		$html = JFactory::getApplication()->getBody();

		if ($html == '')
		{
			return;
		}

		if (RL_Document::isFeed())
		{
			Replace::replaceTags($html);

			Clean::cleanLeftoverJunk($html);

			JFactory::getApplication()->setBody($html);

			return;
		}

		// only do stuff in body
		list($pre, $body, $post) = RL_Html::getBody($html);
		Replace::replaceTags($body, 'body');
		$html = $pre . $body . $post;

		Clean::cleanLeftoverJunk($html);

		JFactory::getApplication()->setBody($html);
	}

	public function replaceTags(&$string, $area = 'article', $context = '', $article = null)
	{
		Replace::replaceTags($string, $area, $context, $article);
	}
}

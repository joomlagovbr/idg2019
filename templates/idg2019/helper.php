<?php
/**
 * @package     Joomlagovbr
 * @subpackage  tmpl_padraogoverno01
 *
 * @copyright   Copyright (C) 2013 Comunidade Joomla Calango e Grupo de Trabalho de Ministérios
 * @license     GNU General Public License version 2
 */

defined('_JEXEC') or die;

class TmplIdg2019Helper
{

    static function getScripts(&$tmpl)
    {
        $javascript_on_footer = $tmpl->params->get('javascript_on_footer', 0);
        $clear_default_javascript = $tmpl->params->get('clear_default_javascript', 0);

		if ( $javascript_on_footer==1 )
		{
			return self::clearDefaultScripts( $tmpl, true);
		}
		else if($clear_default_javascript==1)
		{
			self::clearDefaultScripts( $tmpl );
        }

        return array('scripts' => array(), 'script' => array());
    }

	static function clearDefaultScripts( &$tmpl, $return = false )
	{
		$clear_default_javascript = $tmpl->params->get('clear_default_javascript', 0);
		$new_scripts = $scripts = $tmpl->_scripts; 		
		$new_script  = $script = $tmpl->_script;
		$user = JFactory::getUser();

		if ($clear_default_javascript == 1 && $user->guest == 1) {
	 		unset($new_scripts[$tmpl->baseurl.'/media/system/js/mootools-core.js']);
			unset($new_scripts[$tmpl->baseurl.'/media/system/js/core.js']);
			unset($new_scripts[$tmpl->baseurl.'/media/system/js/caption.js']);        

	 		$limit_new_script = count($new_script);
	 		foreach ($new_script as $k => $v) {
	 			if(strpos($v, "new JCaption('img.caption');") !== false){
	 				unset($new_script[$k]);
					break; 				
	 			}
	 		}
			$tmpl->_scripts = $new_scripts;
			$tmpl->_script  = $new_script;
		}

        if ($return) {
            $return_array = array();
            $return_array['scripts'] = $scripts;
            $return_array['script'] = $script;

            return $return_array;
        }
    }

    /*
    * coloca scripts no rodape. Codigo base original do joomla que renderiza o head está em /libraries/joomla/document/html/renderer/head.php
    */
    static function writeScripts($javascript, &$tmpl)
    {
        $document =& JFactory::getDocument();
        $lnEnd = $document->_getLineEnd();
        $tab = $document->_getTab();
        $buffer = '';

        foreach ($javascript['scripts'] as $strSrc => $strAttr) {
            $buffer .= $tab . '<script src="' . $strSrc . '"';
            if (!is_null($strAttr['mime'])) {
                $buffer .= ' type="' . $strAttr['mime'] . '"';
            }

            if ($strAttr['defer']) {
                $buffer .= ' defer="defer"';
            }

            if ($strAttr['async']) {
                $buffer .= ' async="async"';
            }

            $buffer .= '></script><noscript>&nbsp;<!-- item para fins de acessibilidade --></noscript>' . $lnEnd;
        }

        foreach ($javascript['script'] as $type => $content) {
            $buffer .= $tab . '<script type="' . $type . '">' . $lnEnd;

            $buffer .= $content . $lnEnd;

            $buffer .= $tab . '</script><noscript>&nbsp;<!-- item para fins de acessibilidade --></noscript>' . $lnEnd;
        }

        echo $buffer;
    }

	

	static function inFooter( $param='', &$tmpl )
	{
		if ($tmpl->params->get( $param, 'footer') == 'footer') {
			return true;
		}
		return false;
	}

    static function getBarra2019Script(&$tmpl)
    {
        // Chamada dos modulo para pegar os parametros para validação
        $module = JModuleHelper::getModule('barradogoverno');
        $moduleParams = new JRegistry();
        $moduleParams->loadString($module->params);

        $exibicao = $moduleParams->get('anexar_js_2014', '');
        $jslink = $moduleParams->get('endereco_js_2014', '');

        if($exibicao == '4'){ ?>
            <!-- Barra do Governo -->
            <script src="<?php echo $jslink; ?>" type="text/javascript"></script><noscript>&nbsp;<!-- item para fins de acessibilidade --></noscript>
        <?php } 
    }

    static function getActiveItemid()
    {
        $app = JFactory::getApplication();
        $jinput = $app->input;
        $itemid = $jinput->get('Itemid', 0, 'integer');
        $menu = $app->getMenu();

        return $menu->getItem($itemid);
    }

    static function getItemidParam($activeItemid, $param)
    {
        $app = JFactory::getApplication();
        $menu = $app->getMenu();

        if (!$activeItemid) {
            return '';
        }

        $params = $menu->getParams($activeItemid->id);
        return $params->get($param);
    }

	static function getPageClass( $activeItemid, $only_class = false, $pageclass = false )
	{
		$class = self::getItemidParam($activeItemid, 'pageclass_sfx');

        if ($only_class) {
            return $class;
        }

        if ((!empty($class)) && ($pageclass)) {
            $class = 'pagina-' . $class;
        }

        if (!empty($class)) {
            $class = 'class="' . $class . '"';
        } else {
            $class = '';
        }

        return $class;
    }

	static function getPagePositionPreffix($activeItemid)
	{
		$pos_preffix = self::getPageClass($activeItemid, true);		
		if(empty($pos_preffix))
		{
			$jinput = JFactory::getApplication()->input;
			$option = $jinput->get('option', '', 'string');
			$view   = $jinput->get('view', '', 'string');
			$pos_preffix = $option . '-' . $view;
		}
		else
		{
			$pos_preffix = explode(' ',$pos_preffix);
			$pos_preffix = $pos_preffix[0];
		}
		return $pos_preffix;
	}

    static function isOnlyModulesPage()
    {
        $jinput = JFactory::getApplication()->input;
        $option = $jinput->get('option', '', 'string');

        //informar aqui componentes que desejar utilizar para páginas internas de capa, que exibirão somente modulos:
        $onlyModules = array('com_blankcomponent', 'NOME_OUTRO_COMPONENTE');

		if(in_array($option, $onlyModules))
			return true;

		return false;
	}

	static function loadModuleByPosition($position = NULL, $attribs = array(), $modules = NULL) //self::loadModuleByPosition('')
	{
		if(is_null($modules))
			$modules = JModuleHelper::getModules( $position );
		else if(is_null($position))
			return;

        foreach ($modules as $k => $mod):
            if (count($attribs) > 0) {
                //correcoes utilizadas para menu de redes sociais, no rodape, por exemplo
                if (@$attribs['replaceHTMLentities'] == '1') {
                    $mod = JModuleHelper::renderModule($mod, $attribs);
                    $mod = str_replace(array('&lt;', '&gt;', '<i', 'i>'), array('<', '>', '<span', 'span>'), $mod);
                    echo $mod;
                } else
                    echo JModuleHelper::renderModule($mod, $attribs);
            } else
                echo JModuleHelper::renderModule($mod);

        endforeach;
    }

	static function getModules($position = NULL)
	{
		if(is_null($position))
			return array();

        $modules = JModuleHelper::getModules($position);
        return $modules;
    }

    static function hasMessage()
    {
        $message = JFactory::getApplication()->getMessageQueue();

        if (count($message) > 0)
        {
        	if(count($message)==1 && is_null($message[0]['message']))
        		return false;

        	return true;
        }

        return false;
    }

    static function debug($preffix = '', $active_item = 0)
    {
        $app = JFactory::getApplication();
        try {
            if ($app->get('debug') == 1) {
                // var_dump($active_item);
                echo '<strong>Debug de template</strong><br />';
                echo '<strong>Prefixo de posicoes de modulo:</strong> ' . $preffix . '<br />';
                echo '<strong>ID Item de menu ativo:</strong> ' . $active_item->id . '<br />';
                echo '<strong>LINK Item de menu ativo:</strong> ' . $active_item->link . '<br />';
            }
        } catch (\Exception $e) {
            // do nothing.
        }
    }

}

class InfoIdgHelper
{
    static function getID()
    {
        return JFactory::getApplication()->input->getInt('id', 0);
    }

    static function getItemid()
    {
        return JFactory::getApplication()->input->getInt('Itemid', 0);
    }

    static function getFullURL()
    {
        $input = JFactory::getApplication()->input;
        $view = $input->get('view', '');
        $option = $input->get('option', '');
        $task = $input->get('task', '');
        $id = $input->getInt('id', 0);
        $cid = $input->getInt('cid', 0);
        $tmpl = $input->get('tmpl', '');
        $layout = $input->get('layout', '');
        $Itemid = $input->getInt('Itemid', 0);

        $full_url = JURI::root() . 'index.php';

        $vars = array();

        if (!empty($option)) {
            $vars[] = 'option=' . $option;
        }

        if (!empty($view)) {
            $vars[] = 'view=' . $view;
        }

        if (!empty($task)) {
            $vars[] = 'task=' . $task;
        }

        if (!empty($id)) {
            $vars[] = 'id=' . $id;
        }

        if (!empty($cid)) {
            $vars[] = 'cid=' . $cid;
        }

        if (!empty($tmpl)) {
            $vars[] = 'tmpl=' . $tmpl;
        }

        if (!empty($layout)) {
            $vars[] = 'layout=' . $layout;
        }

        if (!empty($Itemid)) {
            $vars[] = 'Itemid=' . $Itemid;
        }

        if (count($vars)) {
            $full_url .= '?' . implode('&', $vars);
        }

        return $full_url;
    }

}
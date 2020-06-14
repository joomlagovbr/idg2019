<?php
/**
 * @package		J2XML
 * @subpackage	plg_j2xml_users
 *
 * @author		Helios Ciancio <info (at) eshiol (dot) it>
 * @link		http://www.eshiol.it
 * @copyright	Copyright (C) 2016 - 2019 Helios Ciancio. All Rights Reserved
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL v3
 * J2XML is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License
 * or other free or open source software licenses.
 */

// no direct access
defined('_JEXEC') or die('Restricted access.');

use eshiol\J2XML\Importer;
use eshiol\J2XML\Version;
use Joomla\Registry\Registry;

jimport('joomla.plugin.plugin');
jimport('joomla.application.component.helper');
jimport('joomla.filesystem.file');
jimport('joomla.user.helper');

\JLoader::import('eshiol.j2xml.Importer');
\JLoader::import('eshiol.j2xml.Version');

/**
 *
 * Convert CSV to J2XML
 *
 * @version 3.7.10
 * @since 3.0.0
 */
class plgJ2xmlUsers extends JPlugin
{

	/**
	 * Load the language file on instantiation.
	 *
	 * @var boolean
	 */
	protected $autoloadLanguage = true;

	/**
	 * Constructor
	 *
	 * @param object $subject
	 *        	The object to observe
	 * @param array $config
	 *        	An array that holds the plugin configuration
	 */
	function __construct (&$subject, $config)
	{
		parent::__construct($subject, $config);
		
		if ($this->params->get('debug', JComponentHelper::getParams('com_j2xml')->get('debug', 0)) || defined('JDEBUG') && JDEBUG)
		{
			JLog::addLogger(array(
					'text_file' => $this->params->get('log', 'eshiol.log.php'),
					'extension' => 'plg_j2xml_users_file'
			), JLog::ALL, array(
					'plg_j2xml_users'
			));
		}
		
		if (PHP_SAPI == 'cli')
		{
			JLog::addLogger(array(
					'logger' => 'echo',
					'extension' => 'plg_j2xml_users'
			), JLog::ALL & ~ JLog::DEBUG, array(
					'plg_j2xml_users'
			));
		}
		else
		{
			JLog::addLogger(array(
					'logger' => $this->params->get('logger', 'messagequeue'),
					'extension' => 'plg_j2xml_users'
			), JLog::ALL & ~ JLog::DEBUG, array(
					'plg_j2xml_users'
			));
			if ($this->params->get('phpconsole') && class_exists('JLogLoggerPhpconsole'))
			{
				JLog::addLogger(array(
						'logger' => 'phpconsole',
						'extension' => 'plg_j2xml_users_phpconsole'
				), JLog::DEBUG, array(
						'plg_j2xml_users'
				));
			}
		}
		
		JLog::add(new JLogEntry(__METHOD__, JLog::DEBUG, 'plg_j2xml_users'));

		if (JComponentHelper::getParams('com_j2xml')->get('ajax'))
		{
			JFactory::getDocument()->addScriptDeclaration('eshiol.j2xml.users.requireReset = ' . $this->params->get('requireReset', 1) . ';');
		}
	}

	/**
	 * Runs on content preparation
	 *
	 * @param string $context
	 *        	The context for the data
	 * @param string $data
	 *        	An object containing the data for the form.
	 *        	
	 * @return boolean
	 */
	public function onContentPrepareData ($context, &$data)
	{
		JLog::add(new JLogEntry(__METHOD__, JLog::DEBUG, 'plg_j2xml_users'));
		
		if (version_compare(Version::getFullVersion(), '19.2.323') == - 1)
		{
			JLog::add(
					new JLogEntry(JText::_('PLG_J2XML_USERS') . ' ' . JText::_('PLG_J2XML_USERS_MSG_REQUIREMENTS_LIB'), JLog::WARNING,
							'plg_j2xml_users'));
			return true;
		}
		
		libxml_use_internal_errors(true);
		$doc = simplexml_load_string($data);
		if ($doc)
		{
			JLog::add(new JLogEntry('XML file', JLog::DEBUG, 'plg_j2xml_users'));
			return true;
		}
		
		// Rearrange this array to change the search priority of delimiters
		$delimiters = array(
				'tab' => "\t",
				'semicolon' => ";",
				'colon' => ","
		);
		$lines = explode(PHP_EOL, $data);
		$line = array();
		$header = array_shift($lines);
		foreach ($delimiters as $key => $value)
		{
			$line[$value] = count(explode($value, $header)) - 1;
		}
		$delimiter = array_search(max($line), $line);
		
		$cols = array();
		$item = str_getcsv($header, $delimiter, '"', '#');
		foreach ($item as $i => $v)
		{
			$name = strtolower($v);
			if ($name == 'groups')
			{
				$cols['groups'] = $i;
			}
			elseif (($name == 'group') || (substr($name, 0, 6) == 'group.'))
			{
				if (! isset($cols['group']))
				{
					$cols['group'] = array();
				}
				$cols['group'][] = $i;
			}
			elseif (substr($name, 0, 6) == 'field.')
			{
				if (! isset($cols['fields']))
				{
					$cols['fields'] = array();
				}
				$cols['fields'][substr($v, 6)] = $i;
			}
			elseif (substr($name, 0, 8) == 'profile.')
			{
				if (! isset($cols['profiles']))
				{
					$cols['profiles'] = array();
				}
				$cols['profiles'][substr($v, 8)] = $i;
			}
			else
			{
				$cols[$name] = $i;
			}
		}
		
		if (! isset($cols['username']) || (! isset($cols['name'])) || (! isset($cols['email'])))
		{
			JLog::add(new JLogEntry('invalid CSV file', JLog::DEBUG, 'plg_j2xml_users'));
			return true;
		}
		JLog::add(new JLogEntry('CSV format: ' . print_r($cols, true), JLog::DEBUG, 'plg_j2xml_users'));
		
		$new_usertype = $this->params->get('new_usertype', JComponentHelper::getParams('com_users')->get('new_usertype'));
		JLog::add(new JLogEntry('new usertype: ' . $new_usertype, JLog::DEBUG, 'plg_j2xml_users'));
		
		$xml = '';
		foreach ($lines as $line)
		{
			if ($line)
			{
				JLog::add(new JLogEntry('line: ' . $line, JLog::DEBUG, 'plg_j2xml_users'));
				$item = str_getcsv($line, $delimiter, '"', '#');
				JLog::add(new JLogEntry(print_r($item, true), JLog::DEBUG, 'plg_j2xml_users'));
				
				foreach ($cols as $k => $v)
				{
					if (is_array($v))
					{
						foreach ($v as $k1 => $v1)
						{
							JLog::add(new JLogEntry($k . '.' . $k1 . ': ' . $item[$v1], JLog::DEBUG, 'plg_j2xml_users'));
						}
					}
					else
					{
						JLog::add(new JLogEntry($k . ': ' . $item[$v], JLog::DEBUG, 'plg_j2xml_users'));
					}
				}
				$xml .= "<user>\n";
				$xml .= "<id>0</id>\n";
				$xml .= "<name><![CDATA[" . $item[$cols['name']] . "]]></name>\n";
				$xml .= "<username><![CDATA[" . $item[$cols['username']] . "]]></username>\n";
				$xml .= "<email><![CDATA[" . $item[$cols['email']] . "]]></email>\n";
				if (isset($cols['password']) && isset($item[$cols['password']]) && $item[$cols['password']])
				{
					$xml .= "<password><![CDATA[{$item[$cols['password']]}]]></password>\n";
				}
				elseif (isset($cols['password_clear']) && isset($item[$cols['password_clear']]) && $item[$cols['password_clear']])
				{
					$xml .= "<password_clear><![CDATA[{$item[$cols['password_clear']]}]]></password_clear>\n";
				}
				else
				{
					$password_clear = JUserHelper::genRandomPassword();
					$xml .= "<password_clear><![CDATA[{$password_clear}]]></password_clear>\n";
				}
				$xml .= "<block>0</block>\n";
				$xml .= "<sendEmail>0</sendEmail>\n";
				$xml .= "<registerDate><![CDATA[" . date("Y-m-d H:i:s") . "]]></registerDate>\n";
				$xml .= "<lastvisitDate><![CDATA[0000-00-00 00:00:00]]></lastvisitDate>\n";
				$xml .= "<activation/>\n";
				$xml .= "<params><![CDATA[{\"admin_style\":\"\",\"admin_language\":\"\",\"language\":\"\",\"editor\":\"\",\"helpsite\":\"\",\"timezone\":\"\"}]]></params>\n";
				$xml .= "<lastResetTime><![CDATA[0000-00-00 00:00:00]]></lastResetTime>\n";
				$xml .= "<resetCount>0</resetCount>\n";
				$xml .= "<otpKey/>\n";
				$xml .= "<otep/>\n";
				if (! isset($cols['requirereset']))
				{
					$xml .= "<requireReset>{$this->params->get('requireReset', 1)}</requireReset>\n";
				}
				elseif (is_null($item[$cols['requirereset']]))
				{
					$xml .= "<requireReset>{$this->params->get('requireReset', 1)}</requireReset>\n";
				}
				elseif ($item[$cols['requirereset']] == 0)
				{
					$xml .= "<requireReset>0</requireReset>\n";
				}
				elseif ($item[$cols['requirereset']] == 1)
				{
					$xml .= "<requireReset>1</requireReset>\n";
				}
				else
				{
					$xml .= "<requireReset>{$this->params->get('requireReset', 1)}</requireReset>\n";
				}
				if (isset($cols['groups']) || isset($cols['group']))
				{
					JLog::add(new JLogEntry('processing groups...', JLog::DEBUG, 'plg_j2xml_users'));
					$groups = array();
					if (isset($cols['groups']))
					{
						foreach (json_decode($item[$cols['groups']], true) as $v)
						{
							$groups[] = json_encode($v);
						}
					}
					if (isset($cols['group']))
					{
						foreach ($cols['group'] as $i)
						{
							if (isset($item[$i]) && $item[$i])
							{
								$groups[] = $item[$i];
							}
						}
					}
					
					$grouplist = '';
					foreach (array_unique($groups) as $group)
					{
						$grouplist .= "<group><![CDATA[{$group}]]></group>\n";
					}
					if (count($groups) > 1)
					{
						$xml .= "<grouplist>\n{$grouplist}</grouplist>\n";
					}
					else
					{
						$xml .= $grouplist;
					}
				}
				else
				{
					JLog::add(new JLogEntry('default group: ' . $new_usertype, JLog::DEBUG, 'plg_j2xml_users'));
					$xml .= "<group>{$new_usertype}</group>\n";
				}
				
				if (isset($cols['fields']))
				{
					JLog::add(new JLogEntry('processing fields...', JLog::DEBUG, 'plg_j2xml_users'));
					JLog::add(new JLogEntry(print_r($cols['fields'], true), JLog::DEBUG, 'plg_j2xml_users'));
					$n = 0;
					$fieldlist = '';
					foreach ($cols['fields'] as $k => $i)
					{
						if (isset($item[$i]) && $item[$i])
						{
							JLog::add(new JLogEntry($k . ': ' . $item[$i], JLog::DEBUG, 'plg_j2xml_users'));
							$n ++;
							$fieldlist .= "<field>\n";
							$fieldlist .= "<name><![CDATA[{$k}]]></name>\n";
							if (is_numeric($item[$i]))
							{
								$fieldlist .= "<value>{$item[$i]}</value>\n";
							}
							elseif (strtotime($item[$i]))
							{
								$date = new JDate($item[$i]);
								$timeZone = new DateTimeZone(JFactory::getApplication()->get('offset'));
								$date->setTimeZone($timeZone);
								$fieldlist .= "<value><![CDATA[" . $date->toSql() . "]]></value>\n";
							}
							else
							{
								$fieldlist .= "<value><![CDATA[" . $item[$i] . "]]></value>\n";
							}
							$fieldlist .= "</field>\n";
						}
					}
					if ($n > 1)
					{
						$xml .= "<fieldlist>\n{$fieldlist}</fieldlist>\n";
					}
					elseif ($n == 1)
					{
						$xml .= $fieldlist;
					}
				}
				
				if (isset($cols['profiles']))
				{
					JLog::add(new JLogEntry('processing profiles...', JLog::DEBUG, 'plg_j2xml_users'));
					JLog::add(new JLogEntry(print_r($cols['profiles'], true), JLog::DEBUG, 'plg_j2xml_users'));
					$n = 0;
					$profilelist = '';
					foreach ($cols['profiles'] as $k => $i)
					{
						JLog::add(new JLogEntry($k . '[' . $i . '] = ' . $item[$i], JLog::DEBUG, 'plg_j2xml_users'));
						if (isset($item[$i]) && $item[$i])
						{
							JLog::add(new JLogEntry($k . ': ' . $item[$i], JLog::DEBUG, 'plg_j2xml_users'));
							
							$n ++;
							$profilelist .= "<profile>\n";
							$profilelist .= "<name><![CDATA[{$k}]]></name>\n";
							if (is_numeric($item[$i]))
							{
								$profilelist .= "<value>{$item[$i]}</value>\n";
							}
							else
							{
								$profilelist .= "<value><![CDATA[" . json_encode($item[$i]) . "]]></value>\n";
							}
							$profilelist .= "</profile>\n";
						}
					}
					if ($n > 1)
					{
						$xml .= "<profilelist>\n{$profilelist}</profilelist>\n";
					}
					elseif ($n == 1)
					{
						$xml .= $profilelist;
					}
				}
				
				$xml .= "</user>\n";
			}
		}
		
		$xml = ' <?xml version="1.0" encoding="UTF-8" ?>' . "\n" . '<j2xml version="' . Version::$DOCVERSION . '">' . "\n" . $xml . '</j2xml>';
		
		$data = $xml;
		JLog::add(new JLogEntry('xml: ' . $xml, JLog::DEBUG, 'plg_j2xml_users'));
		return true;
	}

	/**
	 * Method is called by index.php and administrator/index.php
	 *
	 * @access public
	 */
	public function onAfterDispatch ()
	{
		JLog::add(new JLogEntry(__METHOD__, JLog::DEBUG, 'plg_j2xml_users'));
		
		$app = JFactory::getApplication();
		if ($app->getName() != 'administrator')
		{
			return true;
		}
		
		$enabled = JComponentHelper::getComponent('com_j2xml', true);
		if (! $enabled->enabled)
		{
			return true;
		}
		
		$option = JRequest::getVar('option');
		$view = JRequest::getVar('view');
		
		$cparams = JComponentHelper::getParams('com_j2xml');
		if ($cparams->get('ajax', false) && ($option == 'com_j2xml') && (! $view || $view == 'cpanel'))
		{
			$doc = JFactory::getDocument();
			$min = ($this->params->get('debug', $cparams->get('debug', 0)) ? '' : '.min');
			JLog::add(new JLogEntry("loading CSVToArray{$min}.js...", JLog::DEBUG, 'plg_j2xml_users'));
			$doc->addScript("../media/plg_j2xml_users/js/CSVToArray{$min}.js");
			JLog::add(new JLogEntry("loading j2xml{$min}.js...", JLog::DEBUG, 'plg_j2xml_users'));
			$doc->addScript("../media/plg_j2xml_users/js/j2xml{$min}.js");
		}
		return true;
	}
}

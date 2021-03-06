<?php
/**
 * Joomla! link-plugin for SimpleLists - Default
 *
 * @author    Yireo
 * @package   SimpleLists
 * @copyright Copyright 2016
 * @license   GNU Public License
 * @link      https://www.yireo.com/
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Include the parent class
require_once JPATH_ADMINISTRATOR . '/components/com_simplelists/libraries/plugin/link.php';

/**
 * SimpleLists Link Plugin - Default
 */
class plgSimpleListsLinkDefault extends SimplelistsPluginLink
{
	/*
	 * Method to get the title for this plugin
	 *
	 * @access public
	 * @param null
	 * @return string
	 */
	public function getTitle()
	{
		return 'None';
	}
}

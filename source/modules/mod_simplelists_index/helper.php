<?php
/**
 * @author Yireo
 * @copyright Copyright (C) 2013 Yireo
 * @license GNU/GPL
 * @link http://www.yireo.com/
*/

// No direct access
defined('_JEXEC') or die('Restricted access');

/*
 * Helper class
 */
class modSimpleListsIndexHelper
{
    /*
     * Method to get a list of items
     */
	static public function getItems($params)
	{
        include_once JPATH_SITE.'/components/com_simplelists/models/items.php' ;
        $model = new SimplelistsModelItems();
        return $model->getData();
    }
}

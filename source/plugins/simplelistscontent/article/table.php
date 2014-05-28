<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright (C) 2014
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
* Content Table class
*/
class TableContent extends YireoTable
{
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    public function __construct(& $db) 
    {
        parent::__construct('#__content', 'id', $db);
    }
}

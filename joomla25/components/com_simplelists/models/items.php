<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2014
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Simplelists Items Model
 */
class SimplelistsModelItems extends YireoModel
{
    /**
     * Data for the category containing these items
     *
     * @protected int
     */
    protected $_category = null;

    /**
     * Constructor
     *
     * @access public
     * @param null
     * @return null
     */
    public function __construct()
    {
        // Debugging
        $this->_debug = false;

        // Deterine the ID for SimpleLists content
        $category_id = JRequest::getInt('category_id', '0');
        $this->setId($category_id);
        $this->setIdByAlias(JRequest::getString('alias', ''));

        // Construct the item
        parent::__construct('item');

        // Set pagination
        if ($this->params->get('use_pagination')) {
            $this->setLimitQuery(true);
            if ($this->params->get('limit') > 0) {
                $this->initLimit($this->params->get('limit'));
            }
        } else {
            $this->setLimitQuery(false);
        }
    }

    /**
     * Method to set the simplelist alias
     *
     * @access public
     * @param string Simplelist category-alias
     */
    public function setIdByAlias($alias)
    {
        if (empty($this->_id)) {
            require_once JPATH_ADMINISTRATOR.'/components/com_simplelists/helpers/category.php';
            $this->setId(SimplelistsCategoryHelper::getId($alias));
        }
    }

    /**
     * Method to build the database query
     *
     * @access protected
     * @param null
     * @return mixed
     */
    protected function buildQuery($query = '')
    {
        $query = 'SELECT item.*'
            . ' FROM #__simplelists_items AS item' 
            . ' LEFT JOIN #__simplelists_categories AS relation ON item.id = relation.id'
            . ' LEFT JOIN #__categories AS category ON category.id = relation.category_id'
        ;
        return parent::buildQuery($query);
    }

    /**
     * Method to build the query WHERE segment
     *
     * @access protected
     * @param null
     * @return string
     */
    protected function buildQueryWhere()
    {
        $this->addWhere('category.published = 1');

        // Apply the category-filter
        $category_id = (int)$this->getId();
        if ($category_id > 0) {
            $this->addWhere('relation.category_id = '.$category_id);
        }

        // Apply the character-filter
        if ($this->getState('no_char_filter') != 1) {
            $character = JRequest::getCmd('char');
            if (!empty($character) && preg_match( '/^([a-z]{1})$/', $character)) {
                $this->addWhere('item.title LIKE '.$this->_db->Quote($character.'%'));
            }
        }

        return parent::buildQueryWhere();
    }

    /**
     * Method to build the query ORDER BY segment
     *
     * @access protected
     * @subpackage Yireo
     * @param null
     * @return string
     */
    protected function buildQueryOrderBy()
    {
        $ordering = $this->params->get('orderby');
        switch ($ordering) {
            case 'alpha': 
                $orderby = 'item.title ASC' ;
                break ;
            case 'ralpha': 
                $orderby = 'item.title DESC' ;
                break ;
            case 'date': 
                $orderby = 'item.created DESC, item.modified DESC' ;
                break ;
            case 'rdate': 
                $orderby = 'item.created ASC, item.modified ASC' ;
                break ;
            case 'random': 
                $orderby = 'RAND()' ;
                break ;
            case 'rorder':
                $orderby = 'item.ordering DESC' ;
                break ;
            default:
                $orderby = 'item.ordering' ;
                break ;
        }
        $this->addOrderby($orderby);

        return parent::buildQueryOrderBy();
    }

    /**
     * Method to get a category
     */
    public function getCategory($category_id = null)
    {
        // Only run this once
        if (empty($this->_category)) {

            // Set the ID
            if (empty($category_id)) $category_id = $this->getId();

            // Fetch the category of these items
            require_once JPATH_ADMINISTRATOR.'/components/com_simplelists/models/category.php';
            $model = new SimplelistsModelCategory();
            $model->setId($category_id);
            $category = $model->getData();

            // Fetch the related categories (parent and children) of this category
            require_once JPATH_ADMINISTRATOR.'/components/com_simplelists/models/categories.php';
            $model = new SimplelistsModelCategories();
            $model->addWhere('category.id = '.(int)$category->id.' AND category.parent_id = '.(int)$category->parent_id);
            $related = $model->getData();

            foreach ($related as $id => $item) {

                // Make sure this related category is not the parent-category
                if ($item->id == $category->parent_id) {
                    $category->parent = $item;
                    unset( $related[$id] );
                    continue;
                }
            }

            $category->childs = $related;

            // Insert this category in the model
            $this->_category = $category;
        }

        return $this->_category;
    }
}

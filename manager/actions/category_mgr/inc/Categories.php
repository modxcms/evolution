<?php
/**
 * Class to handle the modx-categories
 */
class Categories
{
    var $db       = '';
    var $db_tbl   = array();    
    var $elements = array( 'templates', 'tmplvars', 'htmlsnippets', 'snippets', 'plugins', 'modules' );

    function __construct()
    {
        global $modx;

        $this->db                   = & $modx->db;
        $this->db_tbl['categories'] = $modx->getFullTableName('categories');
        
        foreach( $this->elements as $element )
        {
            $this->db_tbl[$element] = $modx->getFullTableName('site_' . $element );
        }
    }


    /**
     * Get all categories
     * @return  array   $categories / array contains all categories
     */
    function getCategories()
    {
        $categories = $this->db->makeArray(
            $this->db->select( 
                '*',
                $this->db_tbl['categories'],
                '1',
                '`rank`,`category`'
            )
        );

        if( !empty( $categories ) )
        {
            return $categories;
        }
        return false;
    }

    function getCategory( $search, $where = 'category' )
    {
        $category = $this->db->getRow(
            $this->db->select(
                '*',
                $this->db_tbl['categories'],
                "`" . $where . "` = '" . $search . "'"
            )
        );
        return $category;
    }

    function getCategoryValue( $value, $search, $where = 'category' )
    {
        $_value = $this->db->getValue(
            $this->db->select(
                '`' . $value . '`',
                $this->db_tbl['categories'],
                "`" . $where . "` = '" . $search . "'"
            )
        );
        return $_value;
    }

    function getAssignedElements( $category_id, $element )
    {
        if( in_array( $element, $this->elements, true ) )
        {
            
            $fields = '`name`,`description`';
            if( $element === 'templates' )
            {
                $fields = '`templatename`,`description`';
            }
            
            $fields = '*';
            
            $elements = $this->db->makeArray(
                $this->db->select( 
                    $fields,
                    $this->db_tbl[$element],
                    "`category` = '" . $category_id . "'"
                )
            );
            
            // correct the name of templates
            if( $element === 'templates' )
            {
                $_elements_count = count($elements);
                for( $i=0; $i < $_elements_count; $i++ )
                {
                    $elements[$i]['name'] = $elements[$i]['templatename'];
                }
            }
            return $elements;
        }
        return false;
    }

    function getAllAssignedElements( $category_id )
    {
        $elements = array();
        foreach( $this->elements as $element )
        {
            $elements[$element] = $this->getAssignedElements( $category_id, $element );
        }
        return $elements;
    }

    function deleteCategory( $category_id )
    {
        $_update = array('category' => 0);
        foreach( $this->elements as $element )
        {
            $this->db->update(
                $_update,
                $this->db_tbl[$element],
                "`category` = '" . $category_id . "'"
            );
        }
        
        $this->db->delete(
            $this->db_tbl['categories'],
            "`id` = '" . $category_id . "'"
        );
        
        if( $this->db->getAffectedRows() === 1 )
        {
            return true;    
        }
        return false;
    }

    function updateCategory( $category_id, $data = array() )
    {
        if( empty( $data )
            || empty( $category_id ) )
        {
            return false;
        }
        
        $_update = array(
            'category' => $this->db->escape( $data['category'] ),
            'rank'     => (int)$data['rank']
        );

        $this->db->update(
            $_update,
            $this->db_tbl['categories'],
            "`id` = '" . (int)$category_id . "'"
        );

        if( $this->db->getAffectedRows() === 1 )
        {
            return true;
        }

        return false;
    }

    function addCategory( $category_name, $category_rank )
    {
        if( $this->isCategoryExists( $category_name ) )
        {
            return false;
        }

        $_insert = array(
            'category' => $this->db->escape( $category_name ),
            'rank'     => (int)$category_rank
        );
        
        $this->db->insert(
            $_insert,
            $this->db_tbl['categories']
        );
        
        if( $this->db->getAffectedRows() === 1 )
        {
            return $this->db->getInsertId();
        }

        //return $this->db->getLastError(); // this will never happen...
        return false;
    }

    function isCategoryExists( $category_name )
    {
        $category = $this->db->escape( $category_name );

        $category_id = $this->db->getValue(
            $this->db->select(
                '`id`',
                $this->db_tbl['categories'],
                "`category` = '" . $category . "'"
            )
        );
        
        if( $this->db->getAffectedRows() === 1 )
        {
            return $category_id;
        }
        return false;
    }
}
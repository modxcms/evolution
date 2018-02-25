<?php
/**
 * Class for MODx Categories Manager
 */
if( !is_object( $modx )
    || $modx->isBackend() === false )
{
    die('Please use the MODx Backend.');
}

require_once realpath( dirname(__FILE__) ) . DIRECTORY_SEPARATOR . 'Categories.php';

class Module_Categories_Manager extends Categories
{
    public $params           = array();
    public $translations     = array();
    public $new_translations = array();


    /**
     * Set a paramter key and its value
     *
     * @return null
     * @param string    $key    paramter key
     * @param mixed     $value  parameter value - could be mixed value-types
     */
    public function set($key, $value)
    {
        $this->params[$key] = $value;
        return null;
    }


    /**
     * Get a parameter value
     *
     * @return  mixed           return the parameter value if exists, otherwise false
     * @param   string  $key    Paramter-key
     */
    public function get( $key )
    {
        global $modx;

        if( isset( $this->params[$key] ) )
        {
            return $this->params[$key];
        }
        elseif( isset( $modx->config[$key] ) )
        {
            return $modx->config[$key];
        }
        elseif( isset( $modx->event->params[$key] ) )
        {
            return $modx->event->params[$key];
        }
        return false;
    }


    public function addMessage( $message, $namespace = 'default' )
    {
        $this->params['messages'][$namespace][] = $message;
    }


    public function getMessages( $namespace = 'default' )
    {
        if( isset( $this->params['messages'][$namespace] ) )
        {
            return $this->params['messages'][$namespace];
        }
        return false;
    }


    public function renderView( $view_name, $data = array() )
    {
        global $_lang, $_style;

        $filename = trim( $view_name ) . '.tpl.phtml';
        $file     = self::get('views_dir') . $filename;
        $view     = & $this;

        if( is_file( $file )
            && is_readable( $file ) )
        {
            include $file;
        }
        else
        {
            echo sprintf(
                'View "%s<strong>%s</strong>" not found.',
                self::get('views_dir'),
                $filename
            );
        }
    }

    public function updateElement( $element, $element_id, $category_id )
    {

        $_update = array(
            'id'       => (int)$element_id,
            'category' => (int)$category_id
        );

        $this->db->update(
            $_update,
            $this->db_tbl[$element],
            "`id` = '" . (int)$element_id . "'"
        );

        return $this->db->getAffectedRows() === 1;
    }


    public function txt( $txt )
    {
        global $_lang;
        if(isset($_lang[$txt])) return $_lang[$txt];
        return $txt;
    }
}

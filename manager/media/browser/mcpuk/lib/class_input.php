<?php

/** This file is part of KCFinder project
  *
  *      @desc Input class for GET, POST and COOKIE requests
  *   @package KCFinder
  *   @version 2.54
  *    @author Pavel Tzonkov <sunhater@sunhater.com>
  * @copyright 2010-2014 KCFinder Project
  *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
  *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
  *      @link http://kcfinder.sunhater.com
  */

class input {

  /** Filtered $_GET array
    * @var array */
    public $get;

  /** Filtered $_POST array
    * @var array */
    public $post;

  /** Filtered $_COOKIE array
    * @var array */
    public $cookie;

    public function __construct() {
        $this->get = &$_GET;
        $this->post = &$_POST;
        $this->cookie = &$_COOKIE;
    }

  /** Magic method to get non-public properties like public.
    * @param string $property
    * @return mixed */

    public function __get($property) {
        return property_exists($this, $property) ? $this->$property : null;
    }
}

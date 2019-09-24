<?php
// https://blog.darkoverlordofdata.com/2015/04/using-view-layouts-in-codeigniter.html

class MY_Loader extends CI_Loader {

     var $layout = '';
     const EXT = '.php'; // php, phtml

	
	function __construct() {
        parent::__construct();
        
        $layout = config_item('layout');
        if ($layout <> '') {
            $this->layout = $layout.self::EXT;
        }
    } 
	
	function setLayout($layout) {
        $this->layout = $layout.self::EXT;
    }
	
	function view($view = '' , $view_data = array(), $return = FALSE) {  
        if ($view <> '') {
            $view = $view.self::EXT;
        }
        if ($this->layout <> '') {
            $view_data['content'] = parent::view($view, $view_data, TRUE);
            return parent::view($this->layout, $view_data, $return);
        } else {
            return parent::view($view, $view_data, $return);
        }
    }
	
	
}
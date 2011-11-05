<?php

//*************** See constructor parameters for instantiation
//*************** add_style and add_script accept the first three parameters accepted by wp_enqueue/wp_register functions, passed as an array


class WPAdminOptionsPage
{
        private $_scripts;
        private $_styles;
        private $_parentMenu;
        private $_menuTitle;
        private $_pageTitle;
        private $_minAccessLevel;
        private $_callback;
        public $_getPageParam;
        public $_getPageParamLength;
        private $_topLevel;
		
	public function __construct($getPageParam, $parentMenu, $menuTitle, $pageTitle, $minAccessLevel, $callback, $topLevel=FALSE)
	{
                $this->_getPageParam = $getPageParam;
                $this->_getPageParamLength = strlen($this->_getPageParam) * -1;
                $this->_topLevel = $topLevel;
		$this->_parentMenu = $parentMenu;
                $this->_menuTitle = $menuTitle;
                $this->_pageTitle = $pageTitle;
                $this->_minAccessLevel = $minAccessLevel;
                $this->_callback = $callback;
                add_action('admin_menu', array($this, 'admin_page_controller'));
	}
	
	public function admin_page_controller()
	{
                if ($this->_topLevel) $optpage = add_menu_page( $this->_menuTitle, $this->_pageTitle, $this->_minAccessLevel, $this->_getPageParam, $this->_callback);
                else $optpage = add_submenu_page( $this->_parentMenu, $this->_menuTitle, $this->_pageTitle, $this->_minAccessLevel, $this->_getPageParam, $this->_callback);
		if (isset($_GET['page']) and substr($_GET['page'], $this->_getPageParamLength) == $this->_getPageParam)
		{
			add_action('admin_enqueue_scripts', array($this, 'enqueue_js'));
			add_action('admin_print_styles-'. $optpage, array($this, 'enqueue_styles'));
		}
	}
	
	public function enqueue_js()
	{
            if (is_array($this->_scripts))
            {
		foreach($this->_scripts as $script)
                {
                    if (count($script) > 1)
                    {
                        isset($script[3])&&is_array($script[3]) ? wp_register_script($script[0], $script[1], $script[2]) : wp_register_script($script[0], $script[1]);
                    }
                    
                    wp_enqueue_script($script[0]);
                }
            }
	}
	
	public function enqueue_styles()
	{
            if (is_array($this->_styles))
            {
		foreach($this->_styles as $style)
                {
                    if (count($style) > 1)
                    {
                        isset($script[3])&&is_array($style[3]) ? wp_register_style($style[0], $style[1], $style[2]) : wp_register_style($style[0], $style[1]);
                    }
                    
                    wp_enqueue_style($style[0]);
                }
            }
	}
        
        public function add_style(array $style)
        {
            $this->_styles[] = $style;
        }
        
        public function add_script(array $script)
        {
            $this->_scripts[] = $script;
        }
}
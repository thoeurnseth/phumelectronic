<?php

	/**
	 * Each package contains wordpress hooks, so that the
	 * autoloader is able to load all of the required packages.
	 */
	//require 'biz_plasgate/wordpress/hooks/add_action_plugins_loaded.php';			// The Hook of "plugins_loaded" has to be loaded first before any other hook
	require 'biz_plasgate/wordpress/hooks/add_action_admin_init.php';
	require 'biz_plasgate/wordpress/hooks/add_action_admin_enqueue_scripts.php';
	require 'biz_plasgate/wordpress/hooks/add_action_admin_menu.php';
	require 'biz_plasgate/wordpress/hooks/add_action_admin_notices.php';
	require 'biz_plasgate/wordpress/hooks/add_filter_plugin_row_meta.php';


	require 'biz_plasgate/library/BasicAuth.php';
	require 'biz_plasgate/library/PlasGate.php';
	require 'biz_plasgate/library/CambodiaNetworkOperator.php';
	
	

















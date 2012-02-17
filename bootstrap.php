<?php

/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel-Expose
 * @version    1.0
 * @author     Dan Harper
 * @link       http://github.com/danharper/fuel-expose
 */

Autoloader::add_core_namespace('Expose');

Autoloader::add_classes(array(
	'Expose\\Expose'          => __DIR__.'/classes/expose.php',
));

/* End of file bootstrap.php */
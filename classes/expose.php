<?php

namespace Expose;

class Expose
{
	final private function __construct() {}

	/**
	 * Initialise FuelPHP package, load S3 auth keys and settings
	 * @return void
	 */
	public static function _init()
	{
	}

	public static function forge($models)
	{
		if (is_array($models))
		{
			$r = array();
			foreach ($models as $model)
			{
				$r[] = self::__expose_one($model);
			}
		}
		else
		{
			$r = self::__expose_one($models);
		}
		return $r;
	}

	private static __expose_one($model)
	{
		$exposed = array();
		foreach ($model::$_public_api as $expose)
		{
			$exposed[$expose] = $model->$expose;
		}
		return array_merge($exposed, $model::$_public_api_dynamic($model));
	}

}
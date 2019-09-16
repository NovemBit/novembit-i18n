<?php

defined( 'ABSPATH' ) || exit;

class NovemBit_i18n_bootstrap {


    public static function init() {

		self::includeFiles();

		self::defineConstants();

	}

	/**
	 * Include composer file
	 */
	private static function includeFiles() {

		/*
		 * Include composer vendor autoload.php file
		 * */
		include_once "../vendor/autoload.php";

		include_once "class-novembit-i18n.php";
	}

	/**
	 * Define constants
	 */
	private static function defineConstants() {

	}
}
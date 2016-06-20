<?php

abstract class PageController {

	protected $title;
	protected $metaDesc;
	protected $dbc;
	protected $plates;

	public function __construct() {

		//instantiate (create instance of) plates library
		$this->plates = new League\Plates\Engine('app/templates');
	}

	//force children classes to have the buildHTML function
	abstract public function buildHTML();
}
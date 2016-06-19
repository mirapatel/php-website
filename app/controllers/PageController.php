<?php

abstract class PageController {

	protected $title;
	protected $metaDesc;
	protected $dbc;

	//force children classes to have the buildHTML function
	abstract public function buildHTML();
}
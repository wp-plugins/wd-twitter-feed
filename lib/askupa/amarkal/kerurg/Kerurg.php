<?php

namespace Kerurg;

abstract class Kerurg {
	public static $Web;
	public static function Kerurg() { 
		$Web = new Web(); 
	}
}

class Web {
	public static $WebElements;
	
	public static function Web() {
		$WebElements = new WebElements();
	}
}

class WebElements {
	public static $Image;
	
	public static function WebElements() {
		$Image = new Image();
	}
}

class Image {
	
}

$img = new Kerurg\Web\WebElements\Image();

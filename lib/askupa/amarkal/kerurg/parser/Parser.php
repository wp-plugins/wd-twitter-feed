<?php
namespace Kerurg\Parser;

class Parser {
	private function Parser() { }
	static function parse($data) {
		$singleQuotesStack = new \SplStack();
		$doubleQuotesStack = new \SplStack();
		$AngleBracketsStack = new \SplStack();
		return $data;
	}
}

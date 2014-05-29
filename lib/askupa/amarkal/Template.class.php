<?php

class Template {
	
	private $_scriptPath = TEMPLATE_PATH; //comes from config.php
	public $properties;
	
	public function setScriptPath($scriptPath){
		$this->_scriptPath=$scriptPath;
	}
	
	public function __construct(){
		$this->properties = array();
	}
	
	public function render($filename){
		ob_start();
		if(file_exists($this->_scriptPath.$filename)){
			include($this->_scriptPath.$filename);
		} else throw new TemplateNotFoundException();
		return ob_get_clean();
	}
	
	public function __set($k, $v){
		$this->properties[$k] = $v;
	}
	
	public function __get($k){
		return $this->properties[$k];
	}
}
?>
<!-- a template example -->
<html>
      <head>
         <title><?=$this->title?></title>
      </head>
      <body>Hey <?=$this->name?></body>
</html>
<!-- invoking will look like -->
<?php
$view = new Template();
$view->title="Hello World app";
$view->properties['name'] = "Jude";
echo $view->render('hello.inc');
?>


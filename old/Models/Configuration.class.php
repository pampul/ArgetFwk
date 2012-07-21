<?php

class Configuration {

	public $id;
	public $config;
	public $value;
	
	function __construct($id, $config, $value) {
		
		$this->id = $id;
		$this->config = $config;
		$this->value = $value;
		
	}
	
	public function getId() { return $this->id; }
        public function getConfig() { return $this->config; }
	public function getValue() { return $this->value; }
	
}

?>
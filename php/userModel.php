<?php

class User{

	public $name;
	public $email;
  public $isPriority; //a boolean

	public function __construct($name, $email, $isPriority=1) {

		$this->name = $name;
    $this->email = $email;
    $this->isPriority = $isPriority;
  }
}
?>

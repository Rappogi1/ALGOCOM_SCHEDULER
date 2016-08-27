<?php

class User{

	public $name;
	public $email;
  public $isPriority; //a boolean
	public $available; // a boolean

	public function __construct($name, $email, $isPriority=1, $available=0) {

		$this->name = $name;
    $this->email = $email;
    $this->isPriority = $isPriority;
    $this->available = $available;

  }
}
?>

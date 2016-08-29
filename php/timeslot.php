<?php

class Timeslot{

	public $datetimeStart;
	public $datetimeEnd;
	public $score;
  public $available = array();
	public $notAvailable = array();

	public function __construct($datetimeStart, $datetimeEnd, $score, $available, $notAvailable) {

		$this->datetimeStart = $datetimeStart;
    $this->datetimeEnd = $datetimeEnd;
		$this->score = $score;
		$this->available = $available;
		$this->notAvailable = $notAvailable;

    // foreach($available as $avail){
    //   array_push($this->available, $avail);
    // }
    // foreach($notAvailable as $notAvail){
    //   array_push($this->notavailable, $notAvail);
    // }
    // foreach($this->available as $avail){
    //   echo $avail->name;
    // }
  }
	public function __toString()
	{
		return $this->datetimeStart . $this->datetimeEnd;
	}
}
?>

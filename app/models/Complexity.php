<?php

class Complexity extends \Phalcon\Mvc\Model
{
    public $id;
    public $name;

    public function initialize()
    {
        $this->hasOne(
            "id",
            "Book",
            "complexity_id"
    	);
	}

    public function getSource() {
        return 'complexity';
    }
}
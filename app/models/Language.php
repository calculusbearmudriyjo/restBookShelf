<?php

class Language extends \Phalcon\Mvc\Model
{
    public $id;
    public $name;

    public function initialize()
    {
        $this->hasOne(
            "id",
            "Book",
            "language_id"
    	);
	}

    public function getSource() {
        return 'language';
    }
}
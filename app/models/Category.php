<?php

class Category extends \Phalcon\Mvc\Model
{
    public $id;
    public $name;

    public function initialize()
    {
        $this->hasMany(
            "id",
            "CategoryBooks",
            "category_id"
    	);

	}

    public function getSource() {
        return 'category';
    }
}
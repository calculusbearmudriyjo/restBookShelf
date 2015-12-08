<?php

class Book extends \Phalcon\Mvc\Model
{
    public $id;
    public $title;
    public $description;
    public $date_created;
    public $date_publish;
    public $images;
    public $external_links;
    public $language_id;
    public $url;
    public $complexity_id;


    public function initialize()
    {
        $this->hasMany(
	        "id",
	        "CategoryBooks",
	        "book_id"
    	);

        $this->hasMany(
            "id",
            "Rating",
            "book_id"
        );

    	$this->belongsTo(
    		"language_id",
    		"Language",
    		"id"
    		);

    	$this->belongsTo(
    		"complexity_id",
    		"Complexity",
    		"id"
    		);
	}

    public function getSource() {
        return 'book';
    }
}
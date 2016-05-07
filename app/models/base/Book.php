<?php
namespace app\models\base;

class Book extends \Phalcon\Mvc\Model
{
    protected $id;
    protected $title;
    protected $description;
    protected $date_created;
    protected $date_publish;
    protected $images;
    protected $external_links;
    protected $language_id;
    protected $url;
    protected $complexity_id;


    public function initialize()
    {
        $this->hasMany(
	        "id",
	        "app\\models\\base\\CategoryBooks",
	        "book_id"
    	);

        $this->hasMany(
            "id",
            "app\\models\\base\\Rating",
            "book_id"
        );

    	$this->belongsTo(
    		"language_id",
    		"app\\models\\base\\Language",
    		"id"
    		);

    	$this->belongsTo(
    		"complexity_id",
    		"app\\models\\base\\Complexity",
    		"id"
    		);
	}

    public function getSource() {
        return 'book';
    }
}
<?php

class CategoryBooks extends \Phalcon\Mvc\Model
{
    public $id;
    public $book_id;
    public $category_id;


    public function initialize()
    {
        $this->setSource('category_books');
        $this->belongsTo('book_id', 'Book', 'id');
        $this->belongsTo('category_id', 'Category', 'id');
	}

    public function getSource() {
        return 'category_books';
    }
}
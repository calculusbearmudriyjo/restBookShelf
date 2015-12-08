<?php

class Rating extends \Phalcon\Mvc\Model
{
    public $id;
    public $book_id;
    public $rating;

    public function initialize()
    {
        $this->hasOne(
            "book_id",
            "Book",
            "id"
        );
    }

    public function getSource() {
        return 'rating';
    }
}
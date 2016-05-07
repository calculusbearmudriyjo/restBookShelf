<?php
namespace app\models\base;

class Rating extends \Phalcon\Mvc\Model
{
    protected $id;
    protected $book_id;
    protected $rating;

    public function initialize()
    {
        $this->hasOne(
            "book_id",
            "app\\models\\base\\Book",
            "id"
        );
    }

    public function getSource() {
        return 'rating';
    }
}
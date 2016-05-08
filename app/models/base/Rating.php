<?php
namespace app\models\base;

use app\exception\HttpMissingParametersException;
use app\exception\HttpWrongParameter;

class Rating extends \Phalcon\Mvc\Model
{
    protected $id;
    protected $book_id;
    protected $rating;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getBookId()
    {
        return $this->book_id;
    }

    /**
     * @param mixed $book_id
     */
    public function setBookId($book_id)
    {
        $this->book_id = $book_id;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating)
    {
        if($rating < 0 || $rating > 5) {
            throw new HttpWrongParameter();
        }
        $this->rating = $rating;
    }

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

    public function setBook(Book $book) {
        $this->book_id = $book->getId();
    }
}
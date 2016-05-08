<?php
namespace app\models\base;

use Phalcon\Mvc\Model;

class CategoryBooks extends Model
{
    protected $id;
    protected $book_id;
    protected $category_id;


    public function initialize()
    {
        $this->setSource('category_books');
        $this->belongsTo('book_id', 'app\\models\\base\\Book', 'id');
        $this->belongsTo('category_id', 'app\\models\\base\\Category', 'id');
	}

    public function getSource() {
        return 'category_books';
    }
}
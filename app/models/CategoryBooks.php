<?php

namespace app\models;

use app\exception\HttpNotFound;
use Phalcon\Di;
use Phalcon\Exception;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class CategoryBooks extends \app\models\base\CategoryBooks
{
    public function getAllCategory() {
        return $this::query()->execute();
    }

    public function getById($id) {
        $categoryBooks = $this::findFirst($id);
        if(!$categoryBooks) {
            throw new HttpNotFound();
        }
        return $categoryBooks;
    }

    public function saveCategoryBook(Book $book, Category $category)
    {
        $db = Di::getDefault()->get('db');
        $db->query('INSERT INTO category_books VALUES (default, ?, ?)', [$book->getId(), $category->getId()]);
    }

    public function deleteCategoryBooks() {
        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();
        $this->setTransaction($transaction);

        if($this->delete() == false)
        {
            $transaction->rollback();
            throw new Exception();
        }

        $transaction->commit();
    }
}
<?php

namespace app\models;

use app\exception\HttpNotFound;
use Phalcon\Di;
use Phalcon\Exception;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class Rating extends base\Rating
{
    public function getAllRating() {
        return $this::query()->execute();
    }

    public function getById($id) {
        $rating = $this::findFirst($id);
        if(!$rating) {
            throw new HttpNotFound();
        }
        return $rating;
    }

    public function saveRating()
    {
        $db = Di::getDefault()->get('db');
        $db->query('INSERT INTO rating VALUES (default, ?, ?)', [$this->book_id, $this->rating]);
    }

    public function deleteRating() {
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

    public function getRatingByName($name) {
        $rating = $this::findFirst("name = '$name'");
        if(!$rating) {
            throw new HttpNotFound();
        }
        return $rating;
    }

    public function updateRating() {
        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();
        $this->setTransaction($transaction);

        if($this->save() == false)
        {
            $transaction->rollback();
            throw new Exception();
        }
        $transaction->commit();
    }
}
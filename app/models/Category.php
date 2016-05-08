<?php

namespace app\models;

use app\exception\HttpNotFound;
use Phalcon\Di;
use Phalcon\Exception;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class Category extends base\Category
{
    public function getAllCategory() {
        return $this::query()->execute();
    }

    public function getById($id) {
        $category = $this::findFirst($id);
        if(!$category) {
            throw new HttpNotFound();
        }
        return $category;
    }

    public function saveCategory()
    {
        $db = Di::getDefault()->get('db');
        $db->query('INSERT INTO category VALUES (default, ?)', [$this->getName()]);
    }

    public function deleteCategory() {
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

    public function getCategoryByName($name) {
        $category = $this::findFirst("name = '$name'");
        if(!$category) {
            throw new HttpNotFound();
        }
        return $category;
    }

    public function updateCategory() {
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
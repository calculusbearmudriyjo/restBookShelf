<?php

namespace app\models;

use app\exception\HttpNotFound;
use Phalcon\Di;
use Phalcon\Exception;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class Complexity extends base\Complexity
{
    public function getAllComplexity() {
        return $this::query()->execute();
    }

    public function getById($id) {
        $category = $this::findFirst($id);
        if(!$category) {
            throw new HttpNotFound();
        }
        return $category;
    }

    public function saveComplexity()
    {
        $db = Di::getDefault()->get('db');
        $db->query('INSERT INTO complexity VALUES (default, ?)', [$this->getName()]);
    }

    public function deleteComplexity() {
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

    public function getComplexityByName($name) {
        $category = $this::findFirst("name = '$name'");
        if(!$category) {
            throw new HttpNotFound();
        }
        return $category;
    }

    public function updateComplexity() {
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
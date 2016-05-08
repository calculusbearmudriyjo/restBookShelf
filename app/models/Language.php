<?php

namespace app\models;

use app\exception\HttpNotFound;
use Phalcon\Di;
use Phalcon\Exception;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class Language extends base\Language
{
    public function getAllLanguage() {
        return $this::query()->execute();
    }

    public function getById($id) {
        $category = $this::findFirst($id);
        if(!$category) {
            throw new HttpNotFound();
        }
        return $category;
    }

    public function saveLanguage()
    {
        $db = Di::getDefault()->get('db');
        $db->query('INSERT INTO language VALUES (default, ?)', [$this->name]);
    }

    public function deleteLanguage() {
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

    public function getLanguageByName($name) {
        $category = $this::findFirst("name = '$name'");
        if(!$category) {
            throw new HttpNotFound();
        }
        return $category;
    }

    public function updateLanguage() {
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
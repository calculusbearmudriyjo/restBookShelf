<?php
namespace app\controllers;

use \Phalcon\Di as Di;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class LanguageController extends BaseController
{
    public function listAction()
    {
        $language = Language::query()->execute();
        echo json_encode($language->toArray(), JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function languageAction($id)
    {
        $language = Language::findFirst($id);
        echo json_encode($language->toArray(), JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function saveAction()
    {
        if($this->_auth()) {
            $db = Di::getDefault()->get('db');
            $language_name = Di::getDefault()->get('request')->get('name');

            if (!empty($language_name)) {
                $db->query('INSERT INTO language VALUES (default, ?)', [$language_name]);
            } else {
                $this->response->setStatusCode(422, 'missing parameters');
            }
        } else {
            $this->response->setStatusCode(403, 'cannot auth');
        }
    }

    public function updateAction($id)
    {
        if($this->_auth()) {
            $language_name = Di::getDefault()->get('request')->get('name');
            /** @var Language $language */
            $language = Language::findFirst($id);

            if ($language) {
                $transactionManager = new TransactionManager();
                $transaction = $transactionManager->get();
                $language->setTransaction($transaction);
                $language->name = $language_name;

                if($language->save() == false)
                {
                    $transaction->rollback();
                    $this->response->setStatusCode(500, 'error save');
                }
                $transaction->commit();
            } else {
                $this->response->setStatusCode(422, 'missing parameters');
            }
        } else {
            $this->response->setStatusCode(403, 'cannot auth');
        }
    }

    public function deleteAction($id)
    {
        if($this->_auth()) {
            /** @var Language $language */
            $language = Language::findFirst($id);

            if ($language) {
                $transactionManager = new TransactionManager();
                $transaction = $transactionManager->get();
                $language->setTransaction($transaction);

                if($language->delete() == false)
                {
                    $transaction->rollback();
                    $this->response->setStatusCode(500, 'error delete');
                }

                $transaction->commit();
            } else {
                $this->response->setStatusCode(422, 'missing parameters');
            }
        } else {
            $this->response->setStatusCode(403, 'cannot auth');
        }
    }
}
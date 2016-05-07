<?php
namespace app\controllers;

use \Phalcon\Di as Di;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class ComplexityController extends BaseController
{
    public function listAction()
    {
        $complexity = Complexity::query()->execute();
        echo json_encode($complexity->toArray(), JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function complexityAction($id)
    {
        $complexity = Complexity::findFirst($id);
        echo json_encode($complexity->toArray(), JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function saveAction()
    {
        if($this->_auth()) {
            $db = Di::getDefault()->get('db');
            $complexity_name = Di::getDefault()->get('request')->get('name');

            if (!empty($complexity_name)) {
                $db->query('INSERT INTO complexity VALUES (default, ?)', [$complexity_name]);
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
            $complexity_name = Di::getDefault()->get('request')->get('name');
            /** @var Complexity $complexity */
            $complexity = Complexity::findFirst($id);

            if ($complexity && !empty($complexity_name)) {
                $transactionManager = new TransactionManager();
                $transaction = $transactionManager->get();
                $complexity->setTransaction($transaction);
                $complexity->name = $complexity_name;

                if($complexity->save() == false)
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
            /** @var Complexity $complexity */
            $complexity = Complexity::findFirst($id);

            if ($complexity) {
                $transactionManager = new TransactionManager();
                $transaction = $transactionManager->get();
                $complexity->setTransaction($transaction);

                if($complexity->delete() == false)
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
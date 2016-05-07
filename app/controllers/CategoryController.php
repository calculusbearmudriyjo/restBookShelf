<?php
namespace app\controllers;

use \Phalcon\Di as Di;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class CategoryController extends BaseController
{
    public function listAction()
    {
        $category = Category::query()->execute();
        echo json_encode($category->toArray(), JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function categoryAction($id)
    {
        $category = Category::findFirst($id);
        echo json_encode($category->toArray(), JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function saveAction()
    {
        if($this->_auth()) {
            $db = Di::getDefault()->get('db');
            $category_name = Di::getDefault()->get('request')->get('name');

            if (!empty($category_name)) {
                $db->query('INSERT INTO complexity VALUES (default, ?)', [$category_name]);
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
            $category_name = Di::getDefault()->get('request')->get('name');
            /** @var Category $category */
            $category = Category::findFirst($id);

            if ($category) {
                $transactionManager = new TransactionManager();
                $transaction = $transactionManager->get();
                $category->setTransaction($transaction);
                $category->name = $category_name;

                if($category->save() == false)
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
            /** @var Category $category */
            $category = Category::findFirst($id);

            if ($category) {
                $transactionManager = new TransactionManager();
                $transaction = $transactionManager->get();
                $category->setTransaction($transaction);

                if($category->delete() == false)
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

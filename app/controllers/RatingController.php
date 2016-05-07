<?php
namespace app\controllers;

use \Phalcon\Di as Di;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class RatingController extends BaseController
{
    public function listAction()
    {
        $rating = Rating::query()->execute();
        echo json_encode($rating->toArray(), JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function categoryAction($id)
    {
        $rating = Rating::findFirst($id);
        echo json_encode($rating->toArray(), JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function saveAction()
    {
        $db = Di::getDefault()->get('db');
        $book_id = Di::getDefault()->get('request')->get('book_id');
        $rating = Di::getDefault()->get('request')->get('rating');

        if (isset($rating) && isset($book_id) && $rating >= 0 && $rating <= 5) {
            $db->query('INSERT INTO rating VALUES (default, ?, ?)', [$book_id, $rating]);
        } else {
            $this->response->setStatusCode(422, 'missing parameters');
        }
    }

    public function deleteAction($id)
    {
        if($this->_auth()) {
            /** @var Rating $rating */
            $rating = Rating::findFirst($id);

            if ($rating) {
                $transactionManager = new TransactionManager();
                $transaction = $transactionManager->get();
                $rating->setTransaction($transaction);

                if($rating->delete() == false)
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
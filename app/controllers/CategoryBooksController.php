<?php
use \Phalcon\Di as Di;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class CategoryBooksController extends BaseController
{
    public function listAction()
    {
        $categoryBooks = CategoryBooks::query()->execute();
        echo json_encode($categoryBooks->toArray(), JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function categoryAction($id)
    {
        $categoryBooks = CategoryBooks::findFirst($id);
        echo json_encode($categoryBooks->toArray(), JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function saveAction()
    {
        if($this->_auth()) {
            $db = Di::getDefault()->get('db');
            $book_id = Di::getDefault()->get('request')->get('book_id');
            $category_id = Di::getDefault()->get('request')->get('category_id');

            if (!empty($category_id) && !empty($book_id)) {
                $db->query('INSERT INTO category_books VALUES (default, ?, ?)', [$book_id, $category_id]);
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
            $categoryBooks = CategoryBooks::findFirst($id);

            if ($categoryBooks) {
                $transactionManager = new TransactionManager();
                $transaction = $transactionManager->get();
                $categoryBooks->setTransaction($transaction);

                if($categoryBooks->delete() == false)
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
<?php
namespace app\controllers;

use app\library\HttpCode;
use Phalcon\Di;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;
use app\models\Book as Book;
use app\models\Language as Language;
use app\models\Complexity as Complexity;
use app\models\Category as Category;

class BookController extends BaseController
{
    /** @var Request $_request  */
    protected $_request = null;
    /** @var Response $_request  */
    protected $_response = null;
    /** @var HttpCode $_request  */
    protected $_httpCode = null;

    public function initialize()
    {
        $this->_request = Di::getDefault()->get('request');
        $this->_response = Di::getDefault()->get('response');
        $this->_httpCode = Di::getDefault()->get('httpCode');
    }

    public function listAction()
    {
        $resultJson = [];

        $language = Language::findFirst($this->_request->get('language'));
        $complexity = Complexity::findFirst($this->_request->get('complexity'));
        $category = Category::findFirst($this->_request->get('category'));

        try {
            $books = (new Book())->getBooksByParams($language, $complexity, $category);
        } catch (\HttpException $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'Not Found');
            return;
        }

        /** @var Book $book */
        foreach($books as $book)
        {
            $resultJson[] = $book->bookToArray();
        }

        echo json_encode($resultJson, JSON_UNESCAPED_UNICODE);
    }

    public function bookAction($id)
    {
        try {
            $book = Book::query()
                ->andWhere('Book.id = :id:')
                ->leftJoin('CategoryBooks', 'CategoryBooks.book_id = Book.id')
                ->bind(['id' => $id])
                ->execute();

            echo json_encode($this->_bookToArray($book[0]), JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Exception $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'Not Found');
        }
    }

    public function saveAction()
    {
        if($this->_auth()) {
            $db = Di::getDefault()->get('db');

            $title = Di::getDefault()->get('request')->get('title');
            $description = Di::getDefault()->get('request')->get('description');
            $date_publish = Di::getDefault()->get('request')->get('date_publish');
            $images = $this->_convertArrayToPsqlArray(Di::getDefault()->get('request')->get('images'));
            $external_links = $this->_convertArrayToPsqlArray(Di::getDefault()->get('request')->get('external_links'));
            $language_id = Di::getDefault()->get('request')->get('language_id');
            $url = Di::getDefault()->get('request')->get('url');
            $complexity_id = Di::getDefault()->get('request')->get('complexity_id');

            try {
                $db->query('INSERT INTO book VALUES (default, ?, ?, now(), ?, ' . $images . ', ' . $external_links . ', ?, ?, ?)',
                    [
                        $title,
                        $description,
                        $date_publish,
                        $language_id,
                        $url,
                        $complexity_id
                    ]);
            } catch (Exception $e) {
                $this->response->setStatusCode($this->_httpCode->unprocessableEntity(), 'missing parameters or ' . $e->getMessage());
            }
        } else {
            $this->response->setStatusCode($this->_httpCode->forbidden(), 'cannot auth');
        }
    }

    public function updateAction($id)
    {
        if($this->_auth()) {
            $title = Di::getDefault()->get('request')->get('title');
            $description = Di::getDefault()->get('request')->get('description');
            $date_publish = Di::getDefault()->get('request')->get('date_publish');
            $images = $this->_convertArrayToPsqlArray(Di::getDefault()->get('request')->get('images'));
            $external_links = $this->_convertArrayToPsqlArray(Di::getDefault()->get('request')->get('external_links'));
            $language_id = Di::getDefault()->get('request')->get('language_id');
            $url = Di::getDefault()->get('request')->get('url');
            $complexity_id = Di::getDefault()->get('request')->get('complexity_id');

            /** @var Book $book */
            $book = Book::findFirst($id);

            if ($book && !empty($book)) {
                $transactionManager = new TransactionManager();
                $transaction = $transactionManager->get();
                $book->setTransaction($transaction);
                $book->title = $title;
                $book->description = $description;
                $book->date_publish = $date_publish;
                $book->images = new \Phalcon\Db\RawValue($images);
                $book->external_links = new \Phalcon\Db\RawValue($external_links);
                $book->language_id = $language_id;
                $book->url = $url;
                $book->complexity_id = $complexity_id;

                if($book->save() == false)
                {
                    $transaction->rollback();
                    $this->response->setStatusCode($this->_httpCode->internalServerError(), 'error save');
                }
                $transaction->commit();
            } else {
                $this->response->setStatusCode($this->_httpCode->unprocessableEntity(), 'missing parameters');
            }
        } else {
            $this->response->setStatusCode($this->_httpCode->forbidden(), 'cannot auth');
        }
    }

    public function deleteAction($id)
    {
        if($this->_auth()) {
            /** @var Book $book */
            $book = Book::findFirst($id);

            if ($book) {
                $transactionManager = new TransactionManager();
                $transaction = $transactionManager->get();
                $book->setTransaction($transaction);

                if($book->delete() == false)
                {
                    $transaction->rollback();
                    $this->response->setStatusCode($this->_httpCode->internalServerError(), 'error delete');
                }

                $transaction->commit();
            } else {
                $this->response->setStatusCode($this->_httpCode->unprocessableEntity(), 'missing parameters');
            }
        } else {
            $this->response->setStatusCode($this->_httpCode->forbidden(), 'cannot auth');
        }
    }

    /**
     * @param array $array
     * @return string
     */
    protected function _convertArrayToPsqlArray($array)
    {
        if(!empty($array) && is_array($array))
        {
            $psqlInsertArray = "'{";
            $psqlInsertArray .= implode(',', $array) . "}'";
            return $psqlInsertArray;
        } else {
            return "'{}'";
        }
    }
}
<?php

use Phalcon\Di;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class BookController extends BaseController
{
    protected $_request = null;
    protected $_response = null;

    public function initialize()
    {
        $this->_request = Di::getDefault()->get('request');
        $this->_response = Di::getDefault()->get('response');
    }

    public function listAction()
    {
        $resultJson = [];
        try {

            $query = Book::query();
            $this->_addWhereIfExist($query, 'language');
            $this->_addWhereIfExist($query, 'complexity');

            $category = $this->_request->get('category');
            if(!empty($category))
            {
                $query->leftJoin('CategoryBooks', 'CategoryBooks.book_id = Book.id');
                $query->conditions('CategoryBooks.category_id = :category:')
                    ->bind(['category' => $category]);
            }

            $books = $query->execute();

            foreach($books as $book)
            {
                $resultJson[] = $this->_bookToArray($book);
            }

            echo json_encode($resultJson, JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Exception $e) {
            $this->response->setStatusCode(404, 'Not Found');
        }
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
            $this->response->setStatusCode(404, 'Not Found');
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
                $this->response->setStatusCode(422, 'missing parameters or ' . $e->getMessage());
            }
        } else {
            $this->response->setStatusCode(403, 'cannot auth');
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
            /** @var Book $book */
            $book = Book::findFirst($id);

            if ($book) {
                $transactionManager = new TransactionManager();
                $transaction = $transactionManager->get();
                $book->setTransaction($transaction);

                if($book->delete() == false)
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

    /**
     * @param \Phalcon\Mvc\Model\Criteria $criteria
     * @param $columnName
     * @return \Phalcon\Mvc\Model\Criteria
     */
    protected function _addWhereIfExist(\Phalcon\Mvc\Model\Criteria $criteria, $columnName)
    {
        $queryFieldValue = $this->_request->get($columnName);
        if(!is_null($queryFieldValue) && !is_array($queryFieldValue))
        {
            $criteria->addWhere("{$columnName}_id = :{$columnName}:", [$columnName => $queryFieldValue]);
        }
        return $criteria;
    }

    /**
     * @param Book $book
     * @return array
     */
    protected function _bookToArray(Book $book)
    {
        $resultJson = [
            'book' => [
                'category' => [],
                'complexity' => [],
                'language' => []
            ]
        ];

        $resultJson['book'] = $book->toArray();
        foreach ($book->categoryBooks as $categoryBook) {
            $resultJson['book']['category'][$categoryBook->category->id] = $categoryBook->category->name;
        }

        $resultJson['book']['rating'] = Rating::average([
            'column' => 'rating',
            'book_id = :id:',
            'bind' => ['id' => $book->id]
        ]);

        $resultJson['book']['complexity'][$book->complexity->id] = $book->complexity->name;
        $resultJson['book']['language'][$book->language->id] = $book->language->name;

        return $resultJson;
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
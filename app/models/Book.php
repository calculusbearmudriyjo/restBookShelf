<?php
namespace app\models;

use app\exception\HttpNotFound;
use Phalcon\Di;
use Phalcon\Exception;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class Book extends base\Book
{
    public function getBookById($bookId) {
        $books = Book::query()
            ->andWhere('app\models\Book.id = :id:')
            ->leftJoin('app\models\base\CategoryBooks', 'app\models\base\CategoryBooks.book_id = app\models\Book.id')
            ->bind(['id' => $bookId])
            ->execute();

        if(!is_array($books) || count($books) == 0) {
            throw new HttpNotFound;
        }

        return $books[0];
    }

    public function getBooksByParams(Language $language, Complexity $complexity, Category $category) {

        $query = parent::query();
        $this->_addWhereIfExist($query, $language->getLinkedFieldName(), $language->getId());
        $this->_addWhereIfExist($query, $complexity->getLinkedFieldName(), $complexity->getId());

        if($category != null)
        {
            $query->leftJoin('app\models\base\CategoryBooks', 'app\models\base\CategoryBooks.book_id = app\models\Book.id');
            $query->conditions('app\models\base\CategoryBooks.category_id = :category:')
                ->bind(['category' => $category->getId()]);
        }

        $result = $query->execute();

        if(!is_array($result) || count($result) == 0) {
            throw new HttpNotFound;
        }

        return $result;
    }

    public function insert() {
        /** @var \Phalcon\Db\Adapter\Pdo\Postgresql $db */
        $db = Di::getDefault()->get('db');
        $db->begin();

        try {
            $db->query('INSERT INTO book VALUES (default, ?, ?, now(), ?, ' . $this->_convertArrayToPsqlArray($this->images) . ', ' . $this->_convertArrayToPsqlArray($this->external_links) . ', ?, ?, ?)',
                [
                    $this->title,
                    $this->description,
                    $this->date_publish,
                    $this->language_id,
                    $this->url,
                    $this->complexity_id
                ]);
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    public function updateBook() {
        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();
        $this->setTransaction($transaction);
        $this->images = new \Phalcon\Db\RawValue($this->_convertArrayToPsqlArray($this->images));
        $this->external_links = new \Phalcon\Db\RawValue($this->_convertArrayToPsqlArray($this->external_links));

        if($this->save() == false)
        {
            $transaction->rollback();
            throw new Exception();
        }
        $transaction->commit();
    }

    public function deleteBook() {
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
    /**
     * @return array
     */
    public function bookToArray()
    {
        $resultJson = [
            'book' => [
                'category' => [],
                'complexity' => [],
                'language' => []
            ]
        ];

        $resultJson['book'] = $this->toArray();
        foreach ($this->categoryBooks as $categoryBook) {
            $resultJson['book']['category'][$categoryBook->category->id] = $categoryBook->category->name;
        }

        $resultJson['book']['rating'] = Rating::average([
            'column' => 'rating',
            'book_id = :id:',
            'bind' => ['id' => $this->id]
        ]);

        $resultJson['book']['complexity'][$this->complexity->id] = $this->complexity->name;
        $resultJson['book']['language'][$this->language->id] = $this->language->name;

        return $resultJson;
    }

    /**
     * @param \Phalcon\Mvc\Model\Criteria $criteria
     * @param $columnName
     * @return \Phalcon\Mvc\Model\Criteria
     */
    protected function _addWhereIfExist(\Phalcon\Mvc\Model\Criteria $criteria, $columnName, $columnValue)
    {
        $criteria->addWhere("{$columnName}_id = :{$columnName}:", [$columnName => $columnValue]);
        return $criteria;
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
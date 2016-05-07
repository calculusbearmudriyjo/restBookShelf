<?php
namespace app\models;

class Book extends base\Book
{

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

        return $query->execute();
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
}
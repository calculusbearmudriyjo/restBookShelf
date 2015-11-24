<?php

class BooksController extends RestController
{
    public function listAction()
    {
        $books = new Book();
        var_dump(Book::query()
            ->where("id = :id:")
            ->limit(20)
            ->bind(["id" => "0"])
            ->execute()
            ->toArray());exit;
        $this->request->getQuery();
        echo json_encode($books->find()->toArray());
    }
}
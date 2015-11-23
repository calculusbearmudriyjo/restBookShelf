<?php

class BookController extends RestController
{
    public function getBookAction()
    {
        $books = new Books();
        var_dump($books->find()->getFirst()->title);
        exit;
    }
}
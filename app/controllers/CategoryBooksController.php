<?php
namespace app\controllers;

use app\exception\HttpAccessException;
use app\exception\HttpNotFound;
use app\models\Book;
use app\models\Category;
use app\models\CategoryBooks;
use \Phalcon\Di as Di;
use Phalcon\Exception;

class CategoryBooksController extends BaseController
{
    public function listAction()
    {
        try {
            $categoryBooks = (new CategoryBooks())->getAllCategory();
            if(!$categoryBooks) {
                throw new HttpNotFound();
            }
            echo json_encode($categoryBooks->toArray(), JSON_UNESCAPED_UNICODE);
        } catch (HttpNotFound $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'Not Found');
        }
    }

    public function categoryAction($id)
    {
        try {
            $categoryBooks = (new CategoryBooks())->getById($id);
            if(!$categoryBooks) {
                throw new HttpNotFound();
            }
            echo json_encode($categoryBooks->toArray(), JSON_UNESCAPED_UNICODE);
        } catch (HttpNotFound $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'Not Found');
        }
    }

    public function saveAction()
    {
        try {
            $this->_auth();
            $book = (new Book())->getBookById($this->_request->get('book_id'));
            $category = (new Category())->getById($this->_request->get('category_id'));

            if(!$book || !$category) {
                throw new HttpNotFound();
            }

            (new CategoryBooks())->saveCategoryBook($book, $category);
        } catch (HttpNotFound $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'not found');
        } catch (HttpAccessException $e) {
            $this->response->setStatusCode($this->_httpCode->forbidden(), 'cannot auth');
        }
    }

    public function deleteAction($id)
    {
        try {
            $this->_auth();
            /** @var CategoryBooks $categoryBooks */
            $categoryBooks = (new CategoryBooks())->getById($id);
            $categoryBooks->deleteCategoryBooks();
        } catch (HttpNotFound $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'not found');
        } catch (HttpAccessException $e) {
            $this->response->setStatusCode($this->_httpCode->forbidden(), 'cannot auth');
        } catch (Exception $e) {
            $this->response->setStatusCode($this->_httpCode->internalServerError());
        }
    }
}
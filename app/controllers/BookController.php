<?php
namespace app\controllers;

use app\exception\HttpAccessException;
use app\exception\HttpMissingParametersException;
use app\exception\HttpNotFound;
use app\exception\MissingParametersException;
use app\library\HttpCode;
use Phalcon\Di;
use app\models\Book as Book;
use app\models\Language as Language;
use app\models\Complexity as Complexity;
use app\models\Category as Category;
use \Phalcon\Exception;

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
        } catch (HttpNotFound $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'Not Found');
            return;
        }

        /** @var Book $book */
        foreach($books as $book)
        {
            $resultJson[] = $book->bookToArray();
        }

        $this->response->setStatusCode($this->_httpCode->ok());
        echo json_encode($resultJson, JSON_UNESCAPED_UNICODE);
    }

    public function bookAction($id)
    {
        try {
            /** @var Book $book */
            $book = (new Book())->getBookById($id);
        } catch (HttpNotFound $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'Not Found');
            return;
        }

        $this->response->setStatusCode($this->_httpCode->ok());
        echo json_encode($book->bookToArray(), JSON_UNESCAPED_UNICODE);
    }

    public function saveAction()
    {
        try {
            $this->_auth();

            $book = new Book();
            $this->_mappingParameters($book);
            $book->insert();
            $this->response->setStatusCode($this->_httpCode->ok());
            return;
        } catch (HttpAccessException $e) {
            $this->response->setStatusCode($this->_httpCode->forbidden(), 'cannot auth');
        } catch (HttpMissingParametersException $e) {
            $this->response->setStatusCode($this->_httpCode->unprocessableEntity(), 'missing parameters');
        } catch (Exception $e) {
            $this->response->setStatusCode($this->_httpCode->unprocessableEntity(), 'missing parameters');
        }
    }

    public function updateAction($id)
    {
        try {
            $this->_auth();
            $book = Book::findFirst($id);
            if(!$book) {
                throw new HttpNotFound();
            }
            $this->_mappingParameters($book);
            $book->updateBook();
            $this->response->setStatusCode($this->_httpCode->ok());
            return;
        } catch (HttpAccessException $e) {
            $this->response->setStatusCode($this->_httpCode->forbidden());
        } catch (HttpMissingParametersException $e) {
            $this->response->setStatusCode($this->_httpCode->unprocessableEntity(), 'missing parameters');
        } catch (HttpNotFound $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'Not Found');
        } catch (Exception $e) {
            $this->response->setStatusCode($this->_httpCode->internalServerError(), 'error save');
        }
    }

    public function deleteAction($id)
    {
        try {
            $this->_auth();

            /** @var Book $book */
            $book = Book::findFirst($id);
            if(!$book) {
                throw new HttpNotFound();
            }
            $book->deleteBook();
            $this->response->setStatusCode($this->_httpCode->ok());
            return;
        } catch(HttpAccessException $e) {
            $this->response->setStatusCode($this->_httpCode->forbidden(), 'cannot auth');
        } catch (HttpNotFound $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'Not Found');
        } catch (Exception $e) {
            $this->response->setStatusCode($this->_httpCode->internalServerError(), 'error delete');
        }
    }

    protected function _mappingParameters(Book $book)  {
        if(!empty(Di::getDefault()->get('request')->get('title')) === false
            || !empty(Di::getDefault()->get('request')->get('language_id')) === false
            || !empty(Di::getDefault()->get('request')->get('complexity_id')) === false ) {
            throw new HttpMissingParametersException();
        }

        $book->setTitle(Di::getDefault()->get('request')->get('title'));
        $book->setDescription(Di::getDefault()->get('request')->get('description'));
        $book->setDatePublish(Di::getDefault()->get('request')->get('date_publish'));
        $book->setImages(Di::getDefault()->get('request')->get('images'));
        $book->setExternalLinks(Di::getDefault()->get('request')->get('external_links'));
        $book->setLanguageId(Di::getDefault()->get('request')->get('language_id'));
        $book->setUrl(Di::getDefault()->get('request')->get('url'));
        $book->setComplexityId(Di::getDefault()->get('request')->get('complexity_id'));
        return $book;
    }
}
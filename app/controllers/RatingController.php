<?php
namespace app\controllers;

use app\exception\HttpWrongParameter;
use app\models\Book;
use app\models\Rating;
use \Phalcon\Di as Di;
use app\exception\HttpAccessException;
use app\exception\HttpNotFound;
use Phalcon\Exception;

class RatingController extends BaseController
{

    public function listAction()
    {
        try {
            $rating = (new Rating())->getAllRating();
            if(!$rating) {
                throw new HttpNotFound();
            }
            echo json_encode($rating->toArray(), JSON_UNESCAPED_UNICODE);
        } catch (HttpNotFound $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'Not Found');
        }
    }

    public function categoryAction($id)
    {
        try {
            $rating = (new Rating())->getById($id);
            if(!$rating) {
                throw new HttpNotFound();
            }
            echo json_encode($rating->toArray(), JSON_UNESCAPED_UNICODE);
        } catch (HttpNotFound $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'Not Found');
        }
    }

    public function saveAction()
    {
        try {
            $rating = new Rating();
            $book = (new Book())->getBookById($this->_request->get('book_id'));
            $rating->setRating($this->_request->get('rating'));
            $rating->setBook($book);
            $rating->saveRating();
        }catch (HttpWrongParameter $e) {
            $this->response->setStatusCode($this->_httpCode->unprocessableEntity(), 'parameter not valid');
        } catch (HttpNotFound $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'not found');
        }
    }

    public function deleteAction($id)
    {
        try {
            $this->_auth();
            /** @var Rating $rating */
            $rating = (new Rating())->getById($id);
            $rating->deleteRating();
        } catch (HttpNotFound $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'not found');
        } catch (HttpAccessException $e) {
            $this->response->setStatusCode($this->_httpCode->forbidden(), 'cannot auth');
        } catch (Exception $e) {
            $this->response->setStatusCode($this->_httpCode->internalServerError());
        }
    }
}
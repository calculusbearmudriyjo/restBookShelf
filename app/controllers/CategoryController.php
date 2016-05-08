<?php
namespace app\controllers;

use app\exception\HttpAccessException;
use app\exception\HttpNotFound;
use app\models\Category;
use \Phalcon\Di as Di;
use Phalcon\Exception;

class CategoryController extends BaseController
{
    public function listAction()
    {
        try {
            $category = (new Category())->getAllCategory();
            if(!$category) {
                throw new HttpNotFound();
            }
            echo json_encode($category->toArray(), JSON_UNESCAPED_UNICODE);
        } catch (HttpNotFound $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'Not Found');
        }
    }

    public function categoryAction($id)
    {
        try {
            $category = (new Category())->getById($id);
            if(!$category) {
                throw new HttpNotFound();
            }
            echo json_encode($category->toArray(), JSON_UNESCAPED_UNICODE);
        } catch (HttpNotFound $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'Not Found');
        }
    }

    public function saveAction()
    {
        try {
            $this->_auth();
            $category = new Category();
            $category->setName($this->_request->get('name'));
            $category->saveCategory();
        } catch (HttpNotFound $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'not found');
        } catch (HttpAccessException $e) {
            $this->response->setStatusCode($this->_httpCode->forbidden(), 'cannot auth');
        }
    }

    public function updateAction($id)
    {
        try {
            $this->_auth();
            $category = (new Category())->getById($id);
            $category->setName($this->_request->get('name'));
            $category->updateCategory();
        } catch (HttpNotFound $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'not found');
        } catch (HttpAccessException $e) {
            $this->response->setStatusCode($this->_httpCode->forbidden(), 'cannot auth');
        } catch (Exception $e) {
            $this->response->setStatusCode($this->_httpCode->internalServerError());
        }
    }

    public function deleteAction($id)
    {
        try {
            $this->_auth();
            /** @var Category $category */
            $category = (new Category())->getById($id);
            $category->deleteCategory();
        } catch (HttpNotFound $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'not found');
        } catch (HttpAccessException $e) {
            $this->response->setStatusCode($this->_httpCode->forbidden(), 'cannot auth');
        } catch (Exception $e) {
            $this->response->setStatusCode($this->_httpCode->internalServerError());
        }
    }
}

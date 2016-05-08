<?php
namespace app\controllers;

use app\models\Complexity;
use \Phalcon\Di as Di;
use app\exception\HttpAccessException;
use app\exception\HttpNotFound;
use Phalcon\Exception;

class ComplexityController extends BaseController
{

    public function listAction()
    {
        try {
            $complexity = (new Complexity())->getAllComplexity();
            if(!$complexity) {
                throw new HttpNotFound();
            }
            echo json_encode($complexity->toArray(), JSON_UNESCAPED_UNICODE);
        } catch (HttpNotFound $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'Not Found');
        }
    }

    public function complexityAction($id)
    {
        try {
            $complexity = (new Complexity())->getById($id);
            if(!$complexity) {
                throw new HttpNotFound();
            }
            echo json_encode($complexity->toArray(), JSON_UNESCAPED_UNICODE);
        } catch (HttpNotFound $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'Not Found');
        }
    }

    public function saveAction()
    {
        try {
            $this->_auth();
            $complexity = new Complexity();
            $complexity->setName($this->_request->get('name'));
            $complexity->saveComplexity();
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
            $complexity = (new Complexity())->getById($id);
            $complexity->setName($this->_request->get('name'));
            $complexity->updateComplexity();
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
            /** @var Complexity $complexity */
            $complexity = (new Complexity())->getById($id);
            $complexity->deleteComplexity();
        } catch (HttpNotFound $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'not found');
        } catch (HttpAccessException $e) {
            $this->response->setStatusCode($this->_httpCode->forbidden(), 'cannot auth');
        } catch (Exception $e) {
            $this->response->setStatusCode($this->_httpCode->internalServerError());
        }
    }
}
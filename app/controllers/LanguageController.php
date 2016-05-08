<?php
namespace app\controllers;

use app\models\Language;
use \Phalcon\Di as Di;
use app\exception\HttpAccessException;
use app\exception\HttpNotFound;
use Phalcon\Exception;

class LanguageController extends BaseController
{
    public function listAction()
    {
        try {
            $language = (new Language())->getAllLanguage();
            if(!$language) {
                throw new HttpNotFound();
            }
            echo json_encode($language->toArray(), JSON_UNESCAPED_UNICODE);
        } catch (HttpNotFound $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'Not Found');
        }
    }

    public function languageAction($id)
    {
        try {
            $language = (new Language())->getById($id);
            if(!$language) {
                throw new HttpNotFound();
            }
            echo json_encode($language->toArray(), JSON_UNESCAPED_UNICODE);
        } catch (HttpNotFound $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'Not Found');
        }
    }

    public function saveAction()
    {
        try {
            $this->_auth();
            $language = new Language();
            $language->setName($this->_request->get('name'));
            $language->saveLanguage();
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
            $language = (new Language())->getById($id);
            $language->setName($this->_request->get('name'));
            $language->updateLanguage();
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
            /** @var Language $language */
            $language = (new Language())->getById($id);
            $language->deleteLanguage();
        } catch (HttpNotFound $e) {
            $this->response->setStatusCode($this->_httpCode->notFound(), 'not found');
        } catch (HttpAccessException $e) {
            $this->response->setStatusCode($this->_httpCode->forbidden(), 'cannot auth');
        } catch (Exception $e) {
            $this->response->setStatusCode($this->_httpCode->internalServerError());
        }
    }
}
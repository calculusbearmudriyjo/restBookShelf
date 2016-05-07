<?php
namespace app\library;

class HttpCode
{
    private $OK = 200;
    private $FORBIDDEN = 403;
    private $NOT_FOUND = 404;
    private $UNPROCESSABLE_ENTITY = 422;
    private $INTERNAL_SERVER_ERROR = 500;

    /**
     * @return int
     */
    public function ok()
    {
        return $this->OK;
    }

    /**
     * @return int
     */
    public function forbidden()
    {
        return $this->FORBIDDEN;
    }

    /**
     * @return int
     */
    public function notFound()
    {
        return $this->NOT_FOUND;
    }

    /**
     * @return int
     */
    public function unprocessableEntity()
    {
        return $this->UNPROCESSABLE_ENTITY;
    }

    /**
     * @return int
     */
    public function internalServerError()
    {
        return $this->INTERNAL_SERVER_ERROR;
    }

}
<?php
namespace app\models\base;

class Complexity extends \Phalcon\Mvc\Model
{
    protected $id;
    protected $name;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    public function initialize()
    {
        $this->hasOne(
            "id",
            "app\\models\\base\\Book",
            "complexity_id"
    	);
	}

    public function getSource() {
        return 'complexity';
    }

    public function getLinkedFieldName()
    {
        return "complexity_id";
    }
}
<?php
namespace app\models\base;

use Phalcon\Mvc\Model;

class Complexity extends Model
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

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
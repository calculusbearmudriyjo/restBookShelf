<?php
namespace app\models\base;

class Language extends \Phalcon\Mvc\Model
{
    protected $id;
    protected $name;

    public function initialize()
    {
        $this->hasOne(
            "id",
            "app\\models\\base\\Book",
            "language_id"
    	);
	}

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    public function getSource() {
        return 'language';
    }

    public function getLinkedFieldName()
    {
        return "language_id";
    }
}

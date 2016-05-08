<?php
namespace app\models\base;

use Phalcon\Mvc\Model;

class Category extends Model
{
    protected $id;
    protected $name;

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
        $this->hasMany(
            "id",
            "app\\models\\base\\CategoryBooks",
            "category_id"
    	);

	}

    public function getSource() {
        return 'category';
    }
}
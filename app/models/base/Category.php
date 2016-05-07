<?php
namespace app\models\base;

class Category extends \Phalcon\Mvc\Model
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
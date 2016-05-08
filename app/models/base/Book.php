<?php
namespace app\models\base;

use Phalcon\Mvc\Model;

class Book extends Model
{
    protected $id;
    protected $title;
    protected $description;
    protected $date_created;
    protected $date_publish;
    protected $images;
    protected $external_links;

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * @return mixed
     */
    public function getDatePublish()
    {
        return $this->date_publish;
    }

    /**
     * @return mixed
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @return mixed
     */
    public function getExternalLinks()
    {
        return $this->external_links;
    }

    /**
     * @return mixed
     */
    public function getLanguageId()
    {
        return $this->language_id;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getComplexityId()
    {
        return $this->complexity_id;
    }
    protected $language_id;
    protected $url;
    protected $complexity_id;


    public function initialize()
    {
        $this->hasMany(
	        "id",
	        "app\\models\\base\\CategoryBooks",
	        "book_id"
    	);

        $this->hasMany(
            "id",
            "app\\models\\base\\Rating",
            "book_id"
        );

    	$this->belongsTo(
    		"language_id",
    		"app\\models\\base\\Language",
    		"id"
    		);

    	$this->belongsTo(
    		"complexity_id",
    		"app\\models\\base\\Complexity",
    		"id"
    		);
	}

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param mixed $date_created
     */
    public function setDateCreated($date_created)
    {
        $this->date_created = $date_created;
    }

    /**
     * @param mixed $date_publish
     */
    public function setDatePublish($date_publish)
    {
        $this->date_publish = $date_publish;
    }

    /**
     * @param mixed $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @param mixed $external_links
     */
    public function setExternalLinks($external_links)
    {
        $this->external_links = $external_links;
    }

    /**
     * @param mixed $language_id
     */
    public function setLanguageId($language_id)
    {
        $this->language_id = $language_id;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @param mixed $complexity_id
     */
    public function setComplexityId($complexity_id)
    {
        $this->complexity_id = $complexity_id;
    }

    public function getSource() {
        return 'book';
    }
}
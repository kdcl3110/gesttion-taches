<?php
namespace Model;

class Category
{
    private $category_id;
    private $name;
    private $created_at;
    private $updated_at;

    public function __construct($category_id, $name, $created_at, $updated_at) {
        $this->category_id = $category_id;
        $this->name = $name;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
    
    // Getter et Setter pour category_id
    public function getCategoryID()
    {
        return $this->category_id;
    }

    public function setCategoryID($category_id)
    {
        $this->category_id = $category_id;
    }

    // Getter et Setter pour name
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    // Getter et Setter pour created_at
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    // Getter et Setter pour updated_at
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }
}

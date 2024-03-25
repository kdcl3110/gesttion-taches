<?php
namespace Model;

class Project
{
    private $project_id;
    private $name;
    private $due_date;
    private $category_id;
    private $description;
    private $created_at;
    private $updated_at;

    public function __construct($project_id, $name, $due_date, $category_id, $description, $created_at, $updated_at) {
        $this->project_id = $project_id;
        $this->name = $name;
        $this->due_date = $due_date;
        $this->category_id = $category_id;
        $this->created_at = $created_at;
        $this->description = $description;
        $this->updated_at = $updated_at;
    }

    // Getter et Setter pour project_id
    public function getProjectID()
    {
        return $this->project_id;
    }

    public function setProjectID($project_id)
    {
        $this->project_id = $project_id;
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

    // Getter et Setter pour due_date
    public function getDueDate()
    {
        return $this->due_date;
    }

    public function setDueDate($due_date)
    {
        $this->due_date = $due_date;
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

    // Getter et Setter pour updated_at
    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }
}

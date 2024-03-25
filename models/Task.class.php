<?php

namespace Model;

class Task
{
    private $task_id;
    private $name;
    private $description;
    private $status;
    private $due_date;
    private $project_id;
    private $user_id;
    private $created_at;
    private $updated_at;

    public function __construct($task_id, $name, $description, $status, $project_id, $user_id, $due_date, $created_at, $updated_at)
    {
        $this->task_id = $task_id;
        $this->name = $name;
        $this->description = $description;
        $this->status = $status;
        $this->project_id = $project_id;
        $this->user_id = $user_id;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->due_date = $due_date;
    }

    // Getter et Setter pour task_id
    public function getTaskID()
    {
        return $this->task_id;
    }

    public function setTaskID($task_id)
    {
        $this->task_id = $task_id;
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

    // Getter et Setter pour description
    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    // Getter et Setter pour status
    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
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

    // Getter et Setter pour user_id
    public function getUserID()
    {
        return $this->user_id;
    }

    public function setUserID($user_id)
    {
        $this->user_id = $user_id;
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

    // Getter et Setter pour due_date
    public function getDueDate()
    {
        return $this->due_date;
    }

    public function setDueDate($due_date)
    {
        $this->due_date = $due_date;
    }
}

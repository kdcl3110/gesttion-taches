<?php

namespace Model;

class Subtask
{
    private $subtask_id;
    private $name;
    private $status;
    private $task_id;
    private $created_at;
    private $updated_at;

    public function __construct($subtask_id, $name, $status, $task_id, $created_at, $updated_at)
    {
        $this->subtask_id = $subtask_id;
        $this->name = $name;
        $this->status = $status;
        $this->task_id = $task_id;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // Getter et Setter pour subtask_id
    public function getSubtaskID()
    {
        return $this->subtask_id;
    }

    public function setSubtaskID($subtask_id)
    {
        $this->subtask_id = $subtask_id;
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

    // Getter et Setter pour status
    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
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

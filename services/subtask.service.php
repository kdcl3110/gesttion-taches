<?php

namespace Services;

use Model\Subtask;

class SubtaskService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createSubtask(Subtask $subtask)
    {
        $sql = "INSERT INTO subtasks (name, status, task_id) VALUES (:name, :status, :task_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'name' => $subtask->getName(),
            'status' => $subtask->getStatus(),
            'task_id' => $subtask->getTaskID()
        ]);
        return $this->pdo->lastInsertId();
    }

    public function getSubtaskByID($subtask_id)
    {
        $sql = "SELECT * FROM subtasks WHERE subtask_id = :subtask_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['subtask_id' => $subtask_id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $subtask = new Subtask($row['subtask_id'], $row['name'], $row['status'], $row['task_id'], $row['created_at'], $row['updated_at']);
        return $subtask;
    }

    public function getSubtasks()
    {
        $sql = "SELECT * FROM subtasks ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $subtasks = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $subtask = new Subtask($row['subtask_id'], $row['name'], $row['status'], $row['task_id'], $row['created_at'], $row['updated_at']);
            $subtasks[] = $subtask;
        }
        return $subtasks;
    }

    public function updateSubtask(Subtask $subtask)
    {
        $sql = "UPDATE subtasks SET name = :name, status = :status, task_id = :task_id WHERE subtask_id = :subtask_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'name' => $subtask->getName(),
            'status' => $subtask->getStatus(),
            'task_id' => $subtask->getTaskID(),
            'subtask_id' => $subtask->getSubtaskID()
        ]);
        return $stmt->rowCount();
    }

    public function deleteSubtask($subtask_id)
    {
        $sql = "DELETE FROM subtasks WHERE subtask_id = :subtask_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['subtask_id' => $subtask_id]);
        return $stmt->rowCount();
    }
}

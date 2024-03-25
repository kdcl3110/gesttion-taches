<?php

namespace Services;

use Model\Task;
use PDOException;

class TaskService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createTask(Task $task)
    {
        try {
            $sql = "INSERT INTO tasks (name, description, status, project_id, user_id, due_date) VALUES (:name, :description, :status, :project_id, :user_id, :due_date)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'name' => $task->getName(),
                'description' => $task->getDescription(),
                'status' => $task->getStatus(),
                'project_id' => $task->getProjectID(),
                'user_id' => $task->getUserID(),
                'due_date' => $task->getDueDate()
            ]);
            return true;
        } catch (PDOException $th) {
            return false;
        }
    }

    public function getTaskByID($task_id)
    {
        $sql = "SELECT * FROM tasks WHERE task_id = :task_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['task_id' => $task_id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $task = new Task($row['task_id'], $row['name'], $row['description'], $row['status'], $row['project_id'], $row['user_id'], $row['due_date'], $row['created_at'], $row['updated_at']);
        return $task;
    }

    public function getTasks()
    {
        $sql = "SELECT * FROM tasks ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $tasks = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $task = new Task(
                $row['task_id'],
                $row['name'],
                $row['description'],
                $row['status'],
                $row['project_id'],
                $row['user_id'],
                $row['due_date'],
                $row['created_at'],
                $row['updated_at']
            );
            $tasks[] = $task;
        }
        return $tasks;
    }

    public function getTasksForProject($project_id)
    {
        try {
            //code...
            $sql = "SELECT * FROM tasks WHERE project_id = :project_id ORDER BY created_at DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['project_id' => $project_id]);
            $tasks = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $task = new Task(
                    $row['task_id'],
                    $row['name'],
                    $row['description'],
                    $row['status'],
                    $row['project_id'],
                    $row['user_id'],
                    $row['due_date'],
                    $row['created_at'],
                    $row['updated_at']
                );
                $tasks[] = $task;
            }
            return $tasks;
        } catch (PDOException $th) {
            return [];
        }
    }

    public function getTasksForUser($user_id, $status = null)
    {
        try {
            $sql = "SELECT * FROM tasks WHERE user_id = :user_id ORDER BY created_at DESC";
            $params = ['user_id' => $user_id];
            if ($status != null) {
                $sql = "SELECT * FROM tasks WHERE user_id = :user_id AND status = :status ORDER BY created_at DESC";
                $params['status'] = $status;
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $tasks = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $task = new Task(
                    $row['task_id'],
                    $row['name'],
                    $row['description'],
                    $row['status'],
                    $row['project_id'],
                    $row['user_id'],
                    $row['due_date'],
                    $row['created_at'],
                    $row['updated_at']
                );
                $tasks[] = $task;
            }
            return $tasks;
        } catch (PDOException $th) {
            return [];
        }
    }

    public function updateTask(Task $task)
    {
        try {
            $sql = "UPDATE tasks SET name = :name, description = :description, status = :status, due_date = :due_date, project_id = :project_id, user_id = :user_id WHERE task_id = :task_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'name' => $task->getName(),
                'description' => $task->getDescription(),
                'status' => $task->getStatus(),
                'project_id' => $task->getProjectID(),
                'user_id' => $task->getUserID(),
                'task_id' => $task->getTaskID(),
                'due_date' => $task->getDueDate()
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteTask($task_id)
    {
        try {
            $sql = "DELETE FROM tasks WHERE task_id = :task_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['task_id' => $task_id]);
            return true;
        } catch (PDOException $th) {
            return false;
        }
    }

    public function getTaskProject($project_id)
    {
        $sql = "SELECT * FROM tasks WHERE project_id = :project_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['project_id' => $project_id]);
        $tasks = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $task = new Task(
                $row['task_id'],
                $row['name'],
                $row['description'],
                $row['status'],
                $row['project_id'],
                $row['user_id'],
                $row['due_date'],
                $row['created_at'],
                $row['updated_at']
            );
            $tasks[] = $task;
        }
        return $tasks;
    }
}

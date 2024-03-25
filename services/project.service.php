<?php

namespace Services;

use Exception;
use Model\Project;

class ProjectService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createProject(Project $project)
    {
        try {
            $sql = "INSERT INTO projects (name, due_date, category_id, description) VALUES (:name, :due_date, :category_id, :description)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'name' => $project->getName(),
                'due_date' => $project->getDueDate(),
                'category_id' => $project->getCategoryID(),
                'description' => $project->getDescription()
            ]);
            // return $this->pdo->lastInsertId();
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function getProjectByID($project_id)
    {
        try {
            $sql = "SELECT * FROM projects WHERE project_id = :project_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['project_id' => $project_id]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!$row) {
                return null;
            }
            $project = new Project(
                $row['project_id'],
                $row['name'],
                $row['due_date'],
                $row['category_id'],
                $row['description'],
                $row['created_at'],
                $row['updated_at']
            );
            return $project;
        } catch (\PDOException $th) {
            return null;
        }
    }

    public function getProjects()
    {
        $sql = "SELECT * FROM projects ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $projects = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $project = new Project(
                $row['project_id'],
                $row['name'],
                $row['due_date'],
                $row['category_id'],
                $row['description'],
                $row['created_at'],
                $row['updated_at']
            );
            $projects[] = $project;
        }
        return $projects;
    }

    public function updateProject(Project $project)
    {
        $sql = "UPDATE projects SET name = :name, due_date = :due_date, category_id = :category_id WHERE project_id = :project_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'name' => $project->getName(),
            'due_date' => $project->getDueDate(),
            'category_id' => $project->getCategoryID(),
            'project_id' => $project->getProjectID()
        ]);
        return $stmt->rowCount();
    }

    public function deleteProject($project_id)
    {
        try {
            $sql = "DELETE FROM projects WHERE project_id = :project_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['project_id' => $project_id]);
            return true;
        } catch (Exception $th) {
            return false;
        }
    }
}

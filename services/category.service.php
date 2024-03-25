<?php

namespace Services;

use Model\Category;

class CategoryService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createCategory(Category $category)
    {
        $sql = "INSERT INTO categories (name) VALUES (:name)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name' => $category->getName()]);
        return $this->pdo->lastInsertId();
    }

    public function getCategoryByID($category_id)
    {
        $sql = "SELECT * FROM categories WHERE category_id = :category_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['category_id' => $category_id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $category = new Category($row['category_id'], $row['name'], $row['created_at'], $row['updated_at']);
        return $category;
    }

    public function getCategories()
    {
        $sql = "SELECT * FROM categories ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $categories = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $category = new Category($row['category_id'], $row['name'], $row['created_at'], $row['updated_at']);
            $categories[] = $category;
        }
        return $categories;
    }

    public function updateCategory(Category $category)
    {
        $sql = "UPDATE categories SET name = :name WHERE category_id = :category_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'name' => $category->getName(),
            'category_id' => $category->getCategoryID()
        ]);
        return $stmt->rowCount();
    }

    public function deleteCategory($category_id)
    {
        $sql = "DELETE FROM categories WHERE category_id = :category_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['category_id' => $category_id]);
        return $stmt->rowCount();
    }
}

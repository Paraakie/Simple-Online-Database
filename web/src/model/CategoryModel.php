<?php

namespace jis\a2\model;

/**
 * Class CategoryModel Stores information about a category and handles saving it to a database
 * @package jis\a2\model
 * @author Issac Clancy, Junyi Chen, Sven Gerhards
 *
 */
class CategoryModel extends Model
{
    /**
     * @var int Unique
     */
    private $id;
    /**
     * @var string The name of the category e.g. Graphics card
     */
    private $name;

    /**
     * Loads a category from the database based on name
     * @param string $name The name of the category e.g. Graphics card
     * @return CategoryModel|null The category if successful, null otherwise
     */
    static public function loadByName(string $name): ?CategoryModel
    {
        $category = new CategoryModel();

        if (!$stm = $category->db->prepare(
            "SELECT `id` FROM `categories` WHERE `name`=?;"
        )) {
            die($category->db->error);
        }
        $stm->bind_param("s", $name);
        $result = $stm->execute();
        if (!$result) {
            $stm->close();
            die($category->db->error);
        }
        $stm->bind_result($id);
        $result = $stm->fetch();
        $stm->close();
        if (!$result) {
            return null;
        }

        $category->id = $id;
        $category->name = $name;

        return $category;
    }

    /**
     * @param $id int The unique id for this category
     * @return CategoryModel|null The category if successful, null otherwise
     */
    static public function loadById(int $id): ?CategoryModel
    {
        $category = new CategoryModel();

        if (!$stm = $category->db->prepare(
            "SELECT `name` FROM `categories` WHERE `id`=?;"
        )) {
            die($category->db->error);
        }
        $stm->bind_param("i", $id);
        $result = $stm->execute();
        if (!$result) {
            $stm->close();
            die($category->db->error);
        }
        $stm->bind_result($name);
        $result = $stm->fetch();
        $stm->close();
        if (!$result) {
            return null;
        }

        $category->id = $id;
        $category->name = $name;

        return $category;
    }

    /**
     * Creates a new category
     * @param string $name new Category name
     * @return CategoryModel
     */
    static public function create(string $name): CategoryModel
    {
        $category = new CategoryModel();
        $category->name = $name;
        return $category;
    }

    /**
     * @return int The unique id for this category
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string The name of the category e.g. Graphics card
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Saves category information to the database
     * @return $this CategoryModel
     */
    public function save(): CategoryModel
    {
        if (!isset($this->id)) {
            // New account - Perform INSERT
            if (!$stm = $this->db->prepare("INSERT INTO `categories` VALUES (NULL, ?);")) {
                die($this->db->error);
            }
            $stm->bind_param(
                "s",
                $this->name
            );
            $result = $stm->execute();
            $stm->close();
            if (!$result) {
                die($this->db->error);
            }
            $this->id = $this->db->insert_id;
        } else {
            // saving existing account - perform UPDATE
            if (!$stm = $this->db->prepare("UPDATE `categories` SET `name`=? WHERE `id`=?;")) {
                die($this->db->error);
            }
            $stm->bind_param(
                "si",
                $this->name,
                $this->id
            );
            $result = $stm->execute();
            $stm->close();
            if (!$result) {
                die($this->db->error);
            }
        }

        return $this;
    }
}
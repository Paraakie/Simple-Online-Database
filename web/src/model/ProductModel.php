<?php
/**
 * 159.339 Internet Programming 2018.2
 * 16192554, Junyi Chen
 * 15031719, Sven Gerhards
 * 16125296, Issac Clancy
 */
namespace jis\a2\model;


/**
 * Stores the information about a product and handles saving it to a database
 *
 * @package jis/a2
 * @author  Andrew Gilman <a.gilman@massey.ac.nz>
 * @author  Isaac Clancy, Junyi Chen, Sven Gerhards
 */
class ProductModel extends Model
{
    /**
     * @var int product ID
     */
    private $id;

    /**
     * @var string human readable name
     */
    private $name;

    /**
     * @var string a unique short name
     */
    private $stockKeepingUnit;

    /**
     * @var int cost in cents
     */
    private $cost;

    /**
     * @var int number of product in stock
     */
    private $quantity;

    /**
     * @var int The id of a category e.g. GPU or Laptop
     */
    private $categoryID;


    /**
     * @return int Product ID, unique to a product
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string A unique name that identifies the product
     */
    public function getStockKeepingUnit(): string
    {
        return $this->stockKeepingUnit;
    }

    /**
     * @return string The name given to an account by the user
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int balance in cents
     */
    public function getCost(): int
    {
        return $this->cost;
    }

    /**
     * @return int The amount of this product still in stock
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return CategoryModel the category e.g. GPU or CPU
     */
    public function getCategory(): CategoryModel
    {
        return CategoryModel::loadById($this->categoryID);
    }

    public function getCategoryID(): int
    {
        return $this->categoryID;
    }

    /**
     * Loads product information from the database
     *
     * @param int $id product ID
     *
     * @return $this ProductModel
     */
    public function load($id): ?ProductModel
    {
        if (!$result = $this->db->query(
            "SELECT `name`, `stockKeepingUnit`, `cost`, `quantity`, `categoryID` 
                FROM `products` WHERE `id` = $id;"
        )) {
            die($this->db->error);
        }

        $data = $result->fetch_assoc();
        if ($data === null) {
            return null;
        }
        $this->name = $data['name'];
        $this->stockKeepingUnit = $data['stockKeepingUnit'];
        $this->cost = intval($data['cost']);
        $this->quantity = intval($data['quantity']);
        $this->categoryID = $data['categoryID'];
        $this->id = $id;

        return $this;
    }

    /**
     * Saves account information to the database
     * @return $this ProductModel
     */
    public function save(): ProductModel
    {
        if (!isset($this->id)) {
            // New account - Perform INSERT
            if (!$stm = $this->db->prepare("INSERT INTO `products` VALUES (NULL, ?, ?, ?, ?, ?);")) {
                die($this->db->error);
            }
            $stm->bind_param(
                "ssiis",
                $this->name,
                $this->stockKeepingUnit,
                $this->cost,
                $this->quantity,
                $this->categoryID
            );
            $result = $stm->execute();
            $stm->close();
            if (!$result) {
                die($this->db->error);
            }
            $this->id = $this->db->insert_id;
        } else {
            // saving existing account - perform UPDATE
            if (!$stm = $this->db->prepare("UPDATE `products` SET `name`=?, `stockKeepingUnit`=?,
                    `cost`=?, `quantity`=?, `categoryID`=? WHERE `id`=?;")) {
                die($this->db->error);
            }
            $stm->bind_param(
                "ssiisi",
                $this->name,
                $this->stockKeepingUnit,
                $this->cost,
                $this->quantity,
                $this->categoryID,
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

    /**
     * Deletes the product from the database
     * @return $this ProductModel
     */
    public function delete(): ProductModel
    {
        if (!$result = $this->db->query("DELETE FROM `products` WHERE `id` = $this->id;")) {
            die($this->db->error);
        }

        return $this;
    }

    static public function create(string $name, string $stockKeepingUnit, int $cost, int $quantity,
        int $categoryID): ProductModel
    {
        $product = new ProductModel();
        $product->name = $name;
        $product->stockKeepingUnit = $stockKeepingUnit;
        $product->cost = $cost;
        $product->quantity = $quantity;
        $product->categoryID = $categoryID;
        return $product;
    }
}

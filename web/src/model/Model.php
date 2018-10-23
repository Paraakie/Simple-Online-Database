<?php
/**
 * 159.339 Internet Programming 2018.2
 * 16192554, Junyi Chen
 * 15031719, Sven Gerhards
 * 16125296, Issac Clancy
 */

namespace jis\a3\model;

use mysqli;

/**
 * Defines helper function for models and creates the database if it doesn't exist
 *
 * @package jis/a3
 * @author  Andrew Gilman <a.gilman@massey.ac.nz>
 * @author Isaac Clancy, Junyi Chen, Sven Gerhards
 */
class Model
{
    /**
     * @var mysqli Connection to database
     */
    protected $db;

    const DB_HOST = 'mysql';
    const DB_USER = 'root';
    const DB_PASS = 'root';
    const DB_NAME = 'a3';

    public function __construct()
    {
        //creating database
        $this->db = new mysqli(
            Model::DB_HOST,
            Model::DB_USER,
            Model::DB_PASS
        );

        if (!$this->db) {
            error_log("Failed to connect to mysql!", 0);
            die("Failed to connect to database");
        }

        // This is to make our life easier
        // Create your database and populate it with sample data
        $this->db->query("CREATE DATABASE IF NOT EXISTS " . Model::DB_NAME . ";");

        if (!$this->db->select_db(Model::DB_NAME)) {
            // somethings not right.. handle it
            error_log("Mysql database not available!", 0);
            die($this->db->error);
        }

        $needsToCreateSampleUser = false;
        $result = $this->db->query("SHOW TABLES LIKE 'user_accounts';");
        if ($result->num_rows == 0) {
            // table doesn't exist create it

            $result = $this->db->query(
                "CREATE TABLE `user_accounts` (
                                          `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
                                          `userName` varchar(255) NOT NULL,
                                          `nickName` varchar(255) NOT NULL,
                                          `password` varchar(255) NOT NULL,
                                          `email` varchar(255) NOT NULL,
                                          PRIMARY KEY (`id`) );"
            );

            if (!$result) {
                // handle appropriately
                error_log("Failed creating table user_accounts", 0);
                die($this->db->error);
            }

            $needsToCreateSampleUser = true;
        }

        $needsToCreateSampleCategories = false;
        $result = $this->db->query("SHOW TABLES LIKE 'categories';");
        if ($result->num_rows == 0) {
            // table doesn't exist create it

            $result = $this->db->query(
                "CREATE TABLE `categories` (
                                          `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
                                          `name` varchar(255) NOT NULL,
                                          PRIMARY KEY (`id`)
                                           );"
            );

            if (!$result) {
                // handle appropriately
                error_log("Failed creating table categories", 0);
                die($this->db->error);
            }

            $needsToCreateSampleCategories = true;
        }

        $needsToCreateSampleProducts = false;
        $result = $this->db->query("SHOW TABLES LIKE 'products';");
        if ($result->num_rows == 0) {
            // table doesn't exist create it

            $result = $this->db->query(
                "CREATE TABLE `products` (
                                          `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
                                          `name` varchar(255) NOT NULL,
                                          `stockKeepingUnit` varchar(255) NOT NULL,
                                          `cost` BIGINT unsigned NOT NULL,
                                          `quantity` BIGINT unsigned NOT NULL,
                                          `categoryID` int(8) unsigned NOT NULL,
                                          PRIMARY KEY (`id`),
                                          UNIQUE (`stockKeepingUnit`),
                                          FOREIGN KEY (`categoryID`) REFERENCES `categories`(`id`)
                                           );"
            );

            if (!$result) {
                // handle appropriately
                error_log("Failed creating table products", 0);
                die($this->db->error);
            }

            $needsToCreateSampleProducts = true;
        }

        // These need to be delayed to they can created models without the database being half initialed
        if($needsToCreateSampleCategories) {
            $this->createSampleCategories();
        }
        if($needsToCreateSampleProducts) {
            $this->createSampleProducts();
        }
        if($needsToCreateSampleUser) {
            $this->createSampleUser();
        }
    }

    /**
     * Create sample categories for testing
     */
    private function createSampleCategories(): void
    {
        CategoryModel::create('USB')->save();
        CategoryModel::create('PC')->save();
        CategoryModel::create('Motherboard')->save();
        CategoryModel::create('Graphics Card')->save();
        CategoryModel::create('Accessories')->save();
    }


    /**
     * Create Sample Products for testing
     */
    public function createSampleProducts(): void
    {
        // Create Sample data
        $usb = CategoryModel::loadByName('USB')->getId();
        ProductModel::create('Sword USB', 'U01', 5000, 20, $usb)->save();
        ProductModel::create('StarWars USB', 'U02', 6000, 10, $usb)->save();
        ProductModel::create('Standard USB', 'U03', 4500, 50, $usb)->save();
        ProductModel::create('Andrew USB', 'U04', 33900, 1, $usb)->save();

        $pc = CategoryModel::loadByName('PC')->getId();
        ProductModel::create('Andrew Gaming Computer', 'PC01', 15933900, 1, $pc)->save();
        ProductModel::create('Budget Gaming Computer', 'PC02', 100000, 50, $pc)->save();
        ProductModel::create('Standard Computer', 'PC03', 200000, 20, $pc)->save();
        ProductModel::create('Gaming Laptop', 'PC04', 400000, 10, $pc)->save();
        ProductModel::create('Work Laptop', 'PC05', 50000, 40, $pc)->save();
        ProductModel::create('Toaster', 'PC06', 1000, 10, $pc)->save();

        $mb = CategoryModel::loadByName('Motherboard')->getId();
        ProductModel::create('MLG Motherboard', 'M01', 42000, 30, $mb)->save();
        ProductModel::create('Standard Motherboard', 'M02', 60000, 50, $mb)->save();
        ProductModel::create('Used Motherboard', 'M03', 10000, 10, $mb)->save();

        $gpu = CategoryModel::loadByName('Graphics Card')->getId();
        ProductModel::create('Space Invader Graphics Card', 'G01', 197800, 10, $gpu)->save();
        ProductModel::create('Standard Graphics Card', 'G02', 150000, 30, $gpu)->save();
        ProductModel::create('4K Graphics Card', 'G03', 400000, 40, $gpu)->save();

        $accessories = CategoryModel::loadByName('Accessories')->getId();
        ProductModel::create('Mickey Mouse', 'A01', 5000, 20, $accessories)->save();
        ProductModel::create('Gaming Mouse', 'A02', 10000, 10, $accessories)->save();
        ProductModel::create('Standard Mouse', 'A03', 6000, 40, $accessories)->save();
        ProductModel::create('Keyboard', 'A04', 1000, 20, $accessories)->save();
        ProductModel::create('LED Keyboard', 'A05', 2000, 0, $accessories)->save();
    }

    /**
     * Create a sample user for testing
     */
    private function createSampleUser(): void
    {
        UserAccountModel::create("Tim Taylor", "TheToolman",
            password_hash("TheToolman", PASSWORD_DEFAULT), "TheToolman@gmail.com")->save();
    }

    /**
     * Closes the connection to the database
     */
    public function close()
    {
        $this->db->close();
    }
}

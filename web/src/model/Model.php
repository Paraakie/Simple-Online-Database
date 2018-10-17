<?php

namespace jis\a2\model;

use mysqli;

/**
 * Defines helper function for models and creates the database if it doesn't exist
 *
 * @package jis/a2
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
        }

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
        }

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

            $this->createSampleProducts();
        }

        $result = $this->db->query("SHOW TABLES LIKE 'transactions';");
        if ($result->num_rows == 0) {
            // table doesn't exist create it

            $result = $this->db->query(
                "CREATE TABLE `transactions` (
                                          `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
                                          `accountID` int(8) unsigned NOT NULL,
                                          `userID` int(8) unsigned NOT NULL,
                                          `time` varchar(100) NOT NULL,
                                          `amount` bigint NOT NULL,
                                          `type` varchar(1) NOT NULL,
                                          PRIMARY KEY (`id`)
                                          );"
            );

            if (!$result) {
                // handle appropriately
                error_log("Failed creating table transactions", 0);
                die($this->db->error);
            }
        }
    }


    /**
     * Create Sample Products for testing
     */
    public function createSampleProducts(): void
    {
        // Create Sample data
        $sql = "INSERT INTO products (`name`, stockKeepingUnit, cost, quantity, categoryID) VALUES (`Sword USB`, `U01`, `50.00`, `20`, `USB`)";
        $sql = "INSERT INTO products (`name`, stockKeepingUnit, cost, quantity, categoryID) VALUES (`StarWars USB`, `U02`, `60.00`, `10`, `USB`)";
        $sql = "INSERT INTO products (`name`, stockKeepingUnit, cost, quantity, categoryID) VALUES (`Standard USB`, `U03`, `45.00`, `50`, `USB`)";
        $sql = "INSERT INTO products (`name`, stockKeepingUnit, cost, quantity, categoryID) VALUES (`Andrew USB`, `U04`, `339.00`, `1`, `USB`)";

        $sql = "INSERT INTO products (`name`, stockKeepingUnit, cost, quantity, categoryID) VALUES (`Andrew Gaming Computer`, `PC01`, `159339.00`, `1`, `PC`)";
        $sql = "INSERT INTO products (`name`, stockKeepingUnit, cost, quantity, categoryID) VALUES (`Budget Gaming Computer`, `PC02`, `1000.00`, `50`, `PC`)";
        $sql = "INSERT INTO products (`name`, stockKeepingUnit, cost, quantity, categoryID) VALUES (`Standard Computer`, `PC03`, `2000.00`, `20`, `PC`)";
        $sql = "INSERT INTO products (`name`, stockKeepingUnit, cost, quantity, categoryID) VALUES (`Gaming Laptop`, `PC04`, `4000.00`, `10`, `PC`)";
        $sql = "INSERT INTO products (`name`, stockKeepingUnit, cost, quantity, categoryID) VALUES (`Work Laptop`, `PC05`, `500.00`, `40`, `PC`)";
        $sql = "INSERT INTO products (`name`, stockKeepingUnit, cost, quantity, categoryID) VALUES (`Toaster`, `PC06`, `10.00`, `10`, `PC`)";

        $sql = "INSERT INTO products (`name`, stockKeepingUnit, cost, quantity, categoryID) VALUES (`MLG Motherboard`, `M01`, `420.00`, `30`, `Motherboard`)";
        $sql = "INSERT INTO products (`name`, stockKeepingUnit, cost, quantity, categoryID) VALUES (`Standard Motherboard`, `M02`, `600.00`, `50`, `Motherboard`)";
        $sql = "INSERT INTO products (`name`, stockKeepingUnit, cost, quantity, categoryID) VALUES (`Used Motherboard`, `M03`, `100.00`, `10`, `Motherboard`)";

        $sql = "INSERT INTO products (`name`, stockKeepingUnit, cost, quantity, categoryID) VALUES (`Space Invader Graphics Card`, `G01`, `1978.00`, `10`, `Graphics Card`)";
        $sql = "INSERT INTO products (`name`, stockKeepingUnit, cost, quantity, categoryID) VALUES (`Standard Graphics Card`, `G02`, `1500.00`, `30`, `Graphics Card`)";
        $sql = "INSERT INTO products (`name`, stockKeepingUnit, cost, quantity, categoryID) VALUES (`4K Graphics Card`, `G03`, `4000.00`, `40`, `Graphics Card`)";

        $sql = "INSERT INTO products (`name`, stockKeepingUnit, cost, quantity, categoryID) VALUES (`Mickey Mouse`, `A01`, `50.00`, `20`, `Accessories`)";
        $sql = "INSERT INTO products (`name`, stockKeepingUnit, cost, quantity, categoryID) VALUES (`Gaming Mouse`, `A02`, `100.00`, `10`, `Accessories`)";
        $sql = "INSERT INTO products (`name`, stockKeepingUnit, cost, quantity, categoryID) VALUES (`Standard Mouse`, `A03`, `60.00`, `40`, `Accessories`)";
        $sql = "INSERT INTO products (`name`, stockKeepingUnit, cost, quantity, categoryID) VALUES (`Keyboard`, `A04`, `10.00`, `20`, `Accessories`)";





        if(!$this->db->query($sql)) {
            echo "ERROR: Could not able to execute $sql. " . $this->db->error;
        }
    }

    /**
     * Closes the connection to the database
     */
    public function close()
    {
        $this->db->close();
    }
}

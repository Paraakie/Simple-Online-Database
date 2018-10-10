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
                                          `name` varchar(256) NOT NULL,
                                          `password` varchar(256) NOT NULL,
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
     * Closes the connection to the database
     */
    public function close()
    {
        $this->db->close();
    }
}

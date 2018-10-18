<?php
/**
 * Created by PhpStorm.
 * User: Isaac
 * Date: 18/10/2018
 * Time: 12:48 PM
 */

namespace jis\a2\model;


/**
 * Handles finding product that satisfy the given condition
 * @package jis\a2
 */
class ProductListModel extends Model
{
    /**
     * Gets a list of products with a name similar to $name
     * @param string $name
     * @param int $maxNumber The maximum number of products to return
     * @return \Generator A list of products
     */
    public function findProductsWithSimilarName(string $name, int $maxNumber): \Generator
    {
        if(count($name) > 40) {
            $name = substr($name, 0, 40);
        }
        if(!$stm = $this->db->prepare("SELECT `id` FROM products WHERE `name` LIKE ? LIMIT ?")) {
            die($this->db->error);
        }
        $stm->bind_param("si", $name, $maxNumber);

        if(!$stm->execute()) {
            $stm->close();
            die($this->db->error);
        }
        $result = $stm->get_result();
        if(!$result) {
            $stm->close();
            die($this->db->error);
        }
        $ids = array_column($result->fetch_all(), 0);
        $stm->close();

        foreach ($ids as $id) {
            // Use a generator to save on memory/resources
            // load products from DB one at a time only when required
            yield (new ProductModel())->load($id);
        }
    }
}
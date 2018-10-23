<?php
/**
 * 159.339 Internet Programming 2018.2
 * 16192554, Junyi Chen
 * 15031719, Sven Gerhards
 * 16125296, Issac Clancy
 */

namespace jis\a3\model;

/**
 * Handles finding product that satisfy the given condition
 * @package jis\a3
 * @author Issac Clancy, Junyi Chen, Sven Gerhards
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
        if (count($name) > 40) {
            $name = substr($name, 0, 40);
        }
        if (!$stm = $this->db->prepare("SELECT `id` FROM products WHERE `name` LIKE ? LIMIT ?")) {
            die($this->db->error);
        }
        $pattern = '%' . $name . '%';
        $stm->bind_param("si", $pattern, $maxNumber);

        if (!$stm->execute()) {
            $stm->close();
            die($this->db->error);
        }
        $result = $stm->get_result();
        if (!$result) {
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

    /**
     * Gets all products with a quantity not equal to zero
     * @return \Generator A list of products
     */
    public function findProductsInStock(): \Generator
    {
        if (!$stm = $this->db->prepare("SELECT id FROM products WHERE quantity!=0")) {
            die($this->db->error);
        }
        if (!$stm->execute()) {
            $stm->close();
            die($this->db->error);
        }
        $result = $stm->get_result();
        if (!$result) {
            $stm->close();
            die($this->db->error);
        }
        $ids = array_column($result->fetch_all(), 0);
        $stm->close();

        foreach ($ids as $id) {
            // Use a generator to save on memory/resources
            yield (new ProductModel())->load($id);
        }
    }

    /**
     * Gets all products with a quantity equal to zero
     * @return \Generator A list of products
     */
    public function findProductsNotInStock(): \Generator
    {
        if (!$stm = $this->db->prepare("SELECT id FROM products WHERE quantity=0")) {
            die($this->db->error);
        }
        if (!$stm->execute()) {
            $stm->close();
            die($this->db->error);
        }
        $result = $stm->get_result();
        if (!$result) {
            $stm->close();
            die($this->db->error);
        }
        $ids = array_column($result->fetch_all(), 0);
        $stm->close();

        foreach ($ids as $id) {
            // Use a generator to save on memory/resources
            yield (new ProductModel())->load($id);
        }
    }

    /**
     * Gets all products
     */
    public function findAllProducts()
    {
        if (!$stm = $this->db->prepare("SELECT id FROM products")) {
            die($this->db->error);
        }

        if (!$stm->execute()) {
            $stm->close();
            die($this->db->error);
        }
        $result = $stm->get_result();
        if (!$result) {
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

    /**
     * finds products that match the category name
     * @param array $categories category name
     * @return \Generator
     */
    public function findProductsWithCategory(array $categories)
    {
        $categoryIds = [];
        foreach ($categories as $category) {
            $categoryIds[] = CategoryModel::loadByName($category)->getId();
        }
        $idsString = implode(',', $categoryIds);
        if (!$stmt = $this->db->prepare("SELECT id FROM products WHERE categoryId IN (" . $idsString . ")")) {
            die($this->db->error);
        }
        if (!$stmt->execute()) {
            $stmt->close();
            die($this->db->error);
        }
        $result = $stmt->get_result();
        if (!$result) {
            $stmt->close();
            die($this->db->error);
        }
        $ids = array_column($result->fetch_all(), 0);
        $stmt->close();

        foreach ($ids as $id) {
            // Use a generator to save on memory/resources
            // load products from DB one at a time only when required
            yield (new ProductModel())->load($id);
        }
    }

    /**
     * finds products that are in stock with category specified
     * @param array $categories
     * @return \Generator
     */
    public function findInStockProductsWithCategory(array $categories)
    {
        $categoryIds = [];
        foreach ($categories as $category) {
            $categoryIds[] = CategoryModel::loadByName($category)->getId();
        }
        $idsString = implode(',', $categoryIds);
        if (!$stmt = $this->db->prepare("SELECT id FROM products WHERE categoryId IN (" . $idsString . ") 
        AND quantity!=0")) {
            die($this->db->error);
        }
        if (!$stmt->execute()) {
            $stmt->close();
            die($this->db->error);
        }
        $result = $stmt->get_result();
        if (!$result) {
            $stmt->close();
            die($this->db->error);
        }
        $ids = array_column($result->fetch_all(), 0);
        $stmt->close();

        foreach ($ids as $id) {
            // Use a generator to save on memory/resources
            // load products from DB one at a time only when required
            yield (new ProductModel())->load($id);
        }
    }

    /**
     * finds products that are out of stock with category specified
     * @param array $categories Category name
     * @return \Generator
     */
    public function findNotInStockProductsWithCategory(array $categories)
    {
        $categoryIds = [];
        foreach ($categories as $category) {
            $categoryIds[] = CategoryModel::loadByName($category)->getId();
        }
        $idsString = implode(',', $categoryIds);
        if (!$stmt = $this->db->prepare("SELECT id FROM products WHERE categoryId IN (" . $idsString . ") 
        AND quantity=0")) {
            die($this->db->error);
        }
        if (!$stmt->execute()) {
            $stmt->close();
            die($this->db->error);
        }
        $result = $stmt->get_result();
        if (!$result) {
            $stmt->close();
            die($this->db->error);
        }
        $ids = array_column($result->fetch_all(), 0);
        $stmt->close();

        foreach ($ids as $id) {
            // Use a generator to save on memory/resources
            // load products from DB one at a time only when required
            yield (new ProductModel())->load($id);
        }
    }
}

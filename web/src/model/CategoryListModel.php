<?php
/**
 * 159.339 Internet Programming 2018.2
 * 16192554, Junyi Chen
 * 15031719, Sven Gerhards
 * 16125296, Issac Clancy
 */
namespace jis\a3\model;


class CategoryListModel extends Model
{
    /**
     * Gets all categories
     */
    public function findAllCategories(): \Generator
    {

        if (!$stm = $this->db->prepare("SELECT id FROM categories")) {
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
            yield CategoryModel::loadById($id);
        }
    }
}

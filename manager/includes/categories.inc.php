<?php
//Helper functions for categories
//Kyle Jaebker - 08/07/06

/**
 * Create a new category
 * @param string $newCat
 * @return int
 */
function newCategory($newCat)
{
    $modx = evolutionCMS();
    $useTable = $modx->getFullTableName('categories');
    $categoryId = $modx->db->insert(
        array(
            'category' => $modx->db->escape($newCat),
        ), $useTable);
    if (!$categoryId) {
        $categoryId = 0;
    }

    return $categoryId;
}

/**
 * check if new category already exists
 *
 * @param string $newCat
 * @return int
 */
function checkCategory($newCat = '')
{
    $modx = evolutionCMS();
    $newCat = $modx->db->escape($newCat);
    $cats = $modx->db->select('id', $modx->getFullTableName('categories'), "category='{$newCat}'");
    if ($cat = $modx->db->getValue($cats)) {
        return (int)$cat;
    }

    return 0;
}

/**
 * Check for category, create new if not exists
 *
 * @param string $category
 * @return int
 */
function getCategory($category = '')
{
    $categoryId = checkCategory($category);
    if (!$categoryId) {
        $categoryId = newCategory($category);
    }

    return $categoryId;
}

/**
 * Get all categories
 *
 * @return array
 */
function getCategories()
{
    $modx = evolutionCMS();
    $useTable = $modx->getFullTableName('categories');
    $cats = $modx->db->select('id, category', $modx->getFullTableName('categories'), '', 'category');
    $resourceArray = array();
    while ($row = $modx->db->getRow($cats)) {
        $row['category'] = stripslashes($row['category']);
        $resourceArray[] = $row;
    }

    return $resourceArray;
}

/**
 * Delete category & associations
 *
 * @param int $catId
 */
function deleteCategory($catId = 0)
{
    $modx = evolutionCMS();
    if ($catId) {
        $resetTables = array(
            'site_plugins',
            'site_snippets',
            'site_htmlsnippets',
            'site_templates',
            'site_tmplvars',
            'site_modules'
        );
        foreach ($resetTables as $n => $v) {
            $useTable = $modx->getFullTableName($v);
            $modx->db->update(array('category' => 0), $useTable, "category='{$catId}'");
        }
        $catTable = $modx->getFullTableName('categories');
        $modx->db->delete($catTable, "id='{$catId}'");
    }
}

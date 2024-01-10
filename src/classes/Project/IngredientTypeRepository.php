<?php

namespace Project;

use ApCode\Web\Pagination;

class IngredientTypeRepository
{
    /**
     * @param array $params
     * @param Pagination $pagination
     * @return IngredientType[]
     */
    public static function findMany($params, Pagination $pagination = NULL)
    {
        $params = array_filter($params);

        $where   = self::buildWhere($params);
        $orderBy = self::buildOrderBy($params);

        if (!empty($params)) {
            throw new \Exception('Invalid param(s) ' . join(', ', array_keys($params)));
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS `id` FROM ' . Db()->quoteName(IngredientType::tableName());

        if ($where) {
            $sql .= ' WHERE ' . join(' AND ', $where);
        }

        if ($orderBy) {
            $sql .= ' ORDER BY ' . join(', ', $orderBy);
        }

        if ($pagination && $pagination->limit()) {
            $sql .= ' LIMIT ' . intval($pagination->startFrom()) . ', ' . intval($pagination->limit());
        }

        $idList = Db()->query($sql)->fetchColumn();

        if ($pagination) {
            $pagination->setTotalItems(Db()->query('SELECT FOUND_ROWS()')->fetchValue());
        }

        $result = [];

        foreach ($idList as $id) {
            $result[] = IngredientType::getInstance($id);
        }

        return $result;
    }

    /**
     * @param array $params
     * @return IngredientType
     */
    public static function findOne($params)
    {
        $pagination = new Pagination();
        $pagination->setLimit(1);

        $list = self::findMany($params, $pagination);

        return current($list);
    }

    /**
     * @param IngredientType $item
     */
    public static function drop(IngredientType $item)
    {
        $sql = 'DELETE FROM ' . Db()->quoteName($item::tableName()) . ' WHERE `id` = ?';
        Db()->query($sql, [$item->billetId()]);
    }

    private static function buildWhere(&$params)
    {
        $where = [];

        if (isset($params['id'])) {
            $where[] = '`id` = ' . Db()->quote($params['id']);
            unset($params['id']);
        }

        if (isset($params['code'])) {
            $where[] = '`code` LIKE ' . Db()->quote('%' . $params['code'] . '%');
            unset($params['code']);
        }

        return $where;
    }

    private static function buildOrderBy(&$params)
    {
        $orderBy = [];

        $ascdesc = function($var, $default) {
            switch (mb_strtolower($var)) {
                case 'asc': return 'ASC';
                case 'desc': return 'DESC';
            }
            return $default;
        };

        $orderBy[] = '`id` DESC';

        if (empty($params['order_by'])) {
            unset($params['order_by']);
        }

        return $orderBy;
    }
}
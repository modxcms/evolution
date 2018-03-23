<?php
/**
 * Filters DocLister results by value of a given MODx Template Variables (TVs).
 * Supported comparison operators:
 * - "="
 * - "IN"
 * - "LIKE" also "%LIKE" or "LIKE%"
 * @author kabachello <kabachnik@hotmail.com>
 * @param filter_tv tvname:operator:value
 *
 */
class content_DL_filter extends filterDocLister
{
    /**
     *
     */
    const TableAlias = 'c';

    /**
     * content_DL_filter constructor.
     */
    public function __construct()
    {
        $this->setTableAlias(self::TableAlias);
    }

    /**
     * @return string
     */
    public function get_where()
    {
        return $this->build_sql_where($this->getTableAlias(), $this->field, $this->operator, $this->value);
    }

    /**
     * @return string
     */
    public function get_join()
    {
        return '';
    }

}

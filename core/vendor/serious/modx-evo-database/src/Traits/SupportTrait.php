<?php namespace AgelxNash\Modx\Evo\Database\Traits;

use AgelxNash\Modx\Evo\Database\Exceptions;

trait SupportTrait
{
    /**
     * @param $value
     * @return mixed
     */
    protected function convertValue($value)
    {
        switch (true) {
            case (\is_numeric($value) && ! \is_float(1 * $value)):
                $value = (int)$value;
                break;
            case \is_numeric($value) && \is_float(1*$value):
                $value = (float)$value;
                break;
            default:
                break;
        }

        return $value;
    }

    /**
     * @param int $timestamp
     * @param string $fieldType
     * @return bool|false|string
     * @deprecated
     */
    public function convertDate($timestamp, $fieldType = 'DATETIME')
    {
        $date = false;
        if (! empty($timestamp) && $timestamp > 0) {
            $format = [
                'DATE' => 'Y-m-d',
                'TIME' => 'H:i:s',
                'YEAR' => 'Y',
                'DATETIME' => 'Y-m-d H:i:s',
            ];
            $use = isset($format[$fieldType]) ? $format[$fieldType] : 'Y-m-d H:i:s';
            $date = date($use, $timestamp);
        }

        return $date;
    }

    /**
     * @param string|array $data
     * @param bool $ignoreAlias
     * @return string
     */
    protected function prepareFields($data, $ignoreAlias = false)
    {
        if (\is_array($data)) {
            $tmp = [];
            foreach ($data as $alias => $field) {
                $tmp[] = ($alias !== $field && ! \is_int($alias) && $ignoreAlias === false) ?
                    ($field . ' as `' . $alias . '`') : $field;
            }

            $data = implode(',', $tmp);
        }
        if (empty($data)) {
            $data = '*';
        }

        return $data;
    }

    /**
     * @param string|null $value
     * @return string
     * @throws Exceptions\InvalidFieldException
     */
    protected function prepareNull($value)
    {
        switch (true) {
            case ($value === null || (\is_scalar($value) && strtolower($value) === 'null')):
                $value = 'NULL';
                break;
            case \is_scalar($value):
                $value = "'" . $value . "'";
                break;
            default:
                throw (new Exceptions\InvalidFieldException('NULL'))
                    ->setData($value);
        }

        return $value;
    }

    /**
     * @param string|array $data
     * @param int $level
     * @param bool $skipFieldNames
     * @return array|string
     * @throws Exceptions\InvalidFieldException
     * @throws Exceptions\TooManyLoopsException
     */
    protected function prepareValues($data, $level = 1, $skipFieldNames = false)
    {
        $fields = [];
        $values = [];
        $maxLevel = $level;
        $wrap = false;

        if (\is_array($data)) {
            foreach ($data as $key => $value) {
                if (\is_array($value)) {
                    if ($level > 2) {
                        throw new Exceptions\TooManyLoopsException('Values');
                    }
                    $maxLevel++;
                    $out = $this->prepareValues($value, $level + 1);
                    if (empty($fields)) {
                        $fields = $out['fields'];
                    } elseif ($fields !== $out['fields'] && $skipFieldNames === false) {
                        throw (new Exceptions\InvalidFieldException("Don't match field names"))
                            ->setData($data);
                    }
                    $wrap = true;
                    $values[] = $out['values'];
                } else {
                    $fields[] = $key;
                    $values[] = $this->prepareNull($value);
                }
            }
            $values = $this->withCommaSeparator($values);
            if ($wrap === false) {
                $values = '(' . $values . ')';
            }
        }

        if (! \is_scalar($values)) {
            throw (new Exceptions\InvalidFieldException('values'))
                ->setData($values);
        }

        if (($fields = $this->checkFields($fields, $maxLevel, $skipFieldNames)) === false) {
            throw (new Exceptions\InvalidFieldException('fields name'))
                ->setData($data);
        }

        if ($level === 2) {
            return compact('fields', 'values');
        }

        return (empty($fields) ? '' : $fields . ' VALUES ') . $values;
    }

    /**
     * @param mixed $fields
     * @param int $level
     * @param bool $skipFieldNames
     * @return bool|string
     */
    protected function checkFields($fields, $level = 1, $skipFieldNames = false)
    {
        if (\is_array($fields) && $skipFieldNames === false) {
            if ($this->arrayOnlyNumeric($fields) === true) {
                $fields = ($level === 2) ? false : '';
            } else {
                $fields = '(`' . implode('`, `', $fields) . '`)';
            }
        }

        return $fields;
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function arrayOnlyNumeric(array $data)
    {
        $onlyNumbers = true;
        foreach ($data as $value) {
            if (! \is_numeric($value)) {
                $onlyNumbers = false;
                break;
            }
        }

        return $onlyNumbers;
    }

    /**
     * @param string|array $data
     * @return string
     * @throws Exceptions\InvalidFieldException
     */
    protected function prepareValuesSet($data)
    {
        if (\is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->prepareNull($value);
            }

            foreach ($data as $key => $value) {
                $data[$key] = "`{$key}` = " . $value;
            }
        }

        return $this->withCommaSeparator($data);
    }

    /**
     * @param string|array $data
     * @param bool $hasArray
     * @return string
     * @throws Exceptions\TableNotDefinedException
     */
    protected function prepareFrom($data, $hasArray = false)
    {
        if (\is_array($data) && $hasArray === true) {
            $tmp = [];
            foreach ($data as $table) {
                $tmp[] = $table;
            }
            $data = implode(' ', $tmp);
        }
        if (! is_scalar($data) || empty($data)) {
            throw new Exceptions\TableNotDefinedException($data);
        }

        return $data;
    }

    /**
     * @param array|string $data
     * @return string
     * @throws Exceptions\InvalidFieldException
     */
    protected function prepareWhere($data)
    {
        if (\is_array($data)) {
            if ($this->arrayOnlyNumeric(array_keys($data)) === true) {
                $data = implode(' ', $data);
            } else {
                throw (new Exceptions\InvalidFieldException('WHERE'))
                    ->setData($data);
            }
        }
        $data = trim($data);
        if (! empty($data) && stripos($data, 'WHERE') !== 0) {
            $data = "WHERE {$data}";
        }

        return $data;
    }

    /**
     * @param string $data
     * @return string
     */
    protected function prepareOrder($data)
    {
        $data = trim($data);
        if (! empty($data) && stripos($data, 'ORDER') !== 0) {
            $data = "ORDER BY {$data}";
        }

        return $data;
    }

    /**
     * @param string $data
     * @return string
     */
    protected function prepareLimit($data)
    {
        $data = trim($data);
        if (! empty($data) && stripos($data, 'LIMIT') !== 0) {
            $data = "LIMIT {$data}";
        }

        return $data;
    }

    /**
     * @param $values
     * @return string
     */
    protected function withCommaSeparator($values)
    {
        if (\is_array($values)) {
            $values = implode(', ', $values);
        }

        return trim($values);
    }
}

<?php

namespace GuedesOrm;

class Builder
{
    protected $table;

    protected $select;

    protected $columns = ['*'];

    protected $where = [];

    public function __construct(string $table)
    {
        $this->table = $table;    
    }

    public function toSql()
    {
        if (!$this->select) {
            $this->initSelect();
        }
        return $this->buildQuery();
    }

    protected function initSelect()
    {
        $this->select = "SELECT {$this->getColumns()} FROM {$this->getTable()}";
    }

    protected function buildQuery()
    {
        $query = $this->select;

        if ($this->where) {
            $andWhere = array_map(function ($_where) {
                return implode(' ', $_where);
            }, $this->where['and'] ?? []);
            $orWhere = array_map(function ($_where) {
                return implode(' ', $_where);
            }, $this->where['or'] ?? []);

            $andWhere = trim(implode(' AND ', $andWhere));
            $orWhere = trim(implode(' OR ', $orWhere));

            $where = $andWhere . $orWhere;
            if ($andWhere && $orWhere) {
                $where =  implode(' OR ', [$andWhere, $orWhere]);
            } 

            $query .= " WHERE $where";
        }

        return $query;
    }

    protected function getColumns()
    {
        return implode(', ', $this->columns ?? ['*']);
    }

    protected function getTable()
    {
        return $this->table;
    }

    public function select(array $columns = ['*'])
    {
        $this->columns = $columns;
        return $this;
    }

    public function where(string $columns = '', string $operator = '=', $value)
    {
        $operator = strtoupper($operator);
        $this->where['and'][] = [$columns, $operator, $value];
        return $this;
    }

    public function orWhere(string $columns = '', string $operator = '=', $value)
    {
        $operator = strtoupper($operator);
        $this->where['or'][] = [$columns, $operator, $value];
        return $this;
    }
}
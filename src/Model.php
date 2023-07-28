<?php

namespace GuedesOrm;

class Model
{
    protected $table;

    protected $builder;

    public function __construct(string $table)
    {
        $this->table = $table;

        $this->builder = new Builder($table);
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->builder, $name)) {
            return call_user_func_array([$this->builder, $name], $arguments);
        }
        return call_user_func_array([$this, $name], $arguments);
    }
}
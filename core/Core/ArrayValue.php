<?php
namespace Core;

class ArrayValue extends Value
{
    /// 迭代器扩展
    public function current()
    {
        return new Value(current($this->var));
    }
}
<?php

namespace common\models;

use ReflectionClass;

class Status
{

    public const brandnew  = 10;
    public const published  = 20;
    public const rejected = 30;


    public function getConstants()
    {
//        $reflectionClass = new ReflectionClass($this);
//        return $reflectionClass->getConstants();

        return array( self::brandnew=>"Новый",  self::published=>"Опубликован", self::rejected=>"Отклонен");
    }
}
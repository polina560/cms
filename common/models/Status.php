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


    public function getStatusName($id)
    {
        if($id == self::brandnew)
            return "Новый";
        if($id == self::published)
            return "Опубликован";
        if($id == self::rejected)
            return "Отклонен";
    }

}
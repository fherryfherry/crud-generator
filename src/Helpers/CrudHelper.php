<?php

namespace CrudGenerator\Helpers;

class CrudHelper
{

    public static function nameField(string $table) {
        $candidates = ['name','nama','alias','sku','title','no_','_no','number'];
        $columns = db()->listColumn($table);
        $word = "id";
        foreach ($columns as $column) {
            foreach($candidates as $candidate) {
                if(stripos($candidate, $column) !== false) {
                    $word = $column;
                    break;
                }
            }
        }
        return $word;
    }

}
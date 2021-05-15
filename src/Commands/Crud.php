<?php


namespace CrudGenerator\Commands;

use SuperFrameworkEngine\Commands\OutputMessage;

class Crud
{
    use OutputMessage;

    /**
     * @description Create table`s crud module
     * @command make:crud
     * @param $table
     */
    public function run($table) {

        $this->success("CRUD table `{$table}` has been created!");
    }
}
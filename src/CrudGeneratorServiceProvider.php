<?php


namespace CrudGenerator;


use CrudGenerator\Commands\AdminInit;
use CrudGenerator\Commands\Crud;

class CrudGeneratorServiceProvider
{
    public function commands() {
        return [
            AdminInit::class,
            Crud::class
        ];
    }

    public function boots() {
        return [];
    }

    public function middlewares() {
        return [];
    }
}
<?php


namespace CrudGenerator;


use CrudGenerator\Commands\AdminInit;
use CrudGenerator\Commands\Crud;
use CrudGenerator\Commands\DefaultUser;

class CrudGeneratorServiceProvider
{
    public function commands() {
        return [
            AdminInit::class,
            Crud::class,
            DefaultUser::class
        ];
    }

    public function boots() {
        return [];
    }

    public function middlewares() {
        return [];
    }
}
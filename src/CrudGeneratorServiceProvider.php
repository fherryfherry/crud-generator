<?php


namespace CrudGenerator;


use CrudGenerator\Commands\AdminInit;
use CrudGenerator\Commands\Crud;
use CrudGenerator\Commands\DefaultUser;
use CrudGenerator\Commands\PublishAsset;

class CrudGeneratorServiceProvider
{
    public function commands() {
        return [
            AdminInit::class,
            PublishAsset::class,
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
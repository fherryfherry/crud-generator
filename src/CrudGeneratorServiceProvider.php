<?php


namespace CrudGenerator;


use CrudGenerator\Commands\AdminInit;
use CrudGenerator\Commands\BackupDb;
use CrudGenerator\Commands\BackupScript;
use CrudGenerator\Commands\BackupWebsite;
use CrudGenerator\Commands\Crud;
use CrudGenerator\Commands\DefaultUser;
use CrudGenerator\Commands\PublishAsset;

class CrudGeneratorServiceProvider
{
    public function commands() {
        return [
            AdminInit::class,
            PublishAsset::class,
            BackupDb::class,
            BackupScript::class,
            BackupWebsite::class,
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
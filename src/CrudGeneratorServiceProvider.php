<?php


namespace CrudGenerator;


use CrudGenerator\Commands\AdminInit;

class CrudGeneratorServiceProvider
{
    public function command() {
        return AdminInit::class;
    }
}
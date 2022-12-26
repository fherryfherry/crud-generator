<?php


namespace CrudGenerator\Commands;


use CrudGenerator\Traits\CommandTrait;
use SuperFrameworkEngine\Commands\OutputMessage;
use SuperFrameworkEngine\Helpers\ShellProcess;

class PublishAsset
{
    use OutputMessage, CommandTrait;

    /**
     * @description To publish the asset
     * @command admin:publish-asset
     */
    public function run() {
        // Publish asset
        $this->publishAssets();
    }
}
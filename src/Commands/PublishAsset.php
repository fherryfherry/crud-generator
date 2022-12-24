<?php


namespace CrudGenerator\Commands;


use CrudGenerator\Traits\CommandTrait;
use SuperFrameworkEngine\Commands\OutputMessage;
use SuperFrameworkEngine\Helpers\ShellProcess;

class PublishAsset
{
    use OutputMessage, CommandTrait;

    /**
     * @description Init admin area
     * @command admin:publish-asset
     */
    public function run() {
        // Publish asset
        $this->publishAssets();
    }
}
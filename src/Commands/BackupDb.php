<?php


namespace CrudGenerator\Commands;


use CrudGenerator\Helpers\CrudBackup;
use CrudGenerator\Traits\CommandTrait;
use SuperFrameworkEngine\Commands\OutputMessage;
use SuperFrameworkEngine\Helpers\ShellProcess;

class BackupDb
{
    use OutputMessage, CommandTrait;

    /**
     * @description To Back Up database only
     * @command admin:backup-db
     */
    public function run() {
        $this->info("Backup database starting...");
        try {
            CrudBackup::backupDatabase();
            $this->success("Backup finished!");
        } catch (\Exception $e) {
            $this->warning("Backup: ". $e->getMessage());
        }
    }
}
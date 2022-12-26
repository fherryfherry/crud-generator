<?php


namespace CrudGenerator\Commands;


use CrudGenerator\Helpers\CrudBackup;
use CrudGenerator\Traits\CommandTrait;
use SuperFrameworkEngine\Commands\OutputMessage;
use SuperFrameworkEngine\Helpers\ShellProcess;

class BackupWebsite
{
    use OutputMessage, CommandTrait;

    /**
     * @description To back up the whole DB + Script
     * @command admin:backup-web
     */
    public function run() {
        $this->info("Backup website starting...");
        try {
            $this->info("Backup database...");
            CrudBackup::backupDatabase();
            $this->info("Backup script...");
            CrudBackup::backupScript(base_path(),base_path("Backup-".str_slug($_ENV['APP_NAME']).'-'.date('YmdHis').".zip"));
            $this->success("Backup finished!");
        } catch (\Exception $e) {
            $this->warning("Backup: ". $e->getMessage());
        }
    }

}
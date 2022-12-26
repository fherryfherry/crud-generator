<?php


namespace CrudGenerator\Commands;


use CrudGenerator\Helpers\CrudBackup;
use CrudGenerator\Traits\CommandTrait;
use SuperFrameworkEngine\Commands\OutputMessage;
use SuperFrameworkEngine\Helpers\ShellProcess;

class BackupScript
{
    use OutputMessage, CommandTrait;

    /**
     * @description To back up script only
     * @command admin:backup-script
     */
    public function run() {
        $this->info("Backup script starting...");
        try {
            CrudBackup::backupScript(base_path(),base_path("Backup-Script-".date('YmdHis').".zip"));
            $this->success("Backup finished!");
        } catch (\Exception $e) {
            $this->warning("Backup: ". $e->getMessage());
        }
    }

}
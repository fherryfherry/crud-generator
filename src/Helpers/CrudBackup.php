<?php

namespace CrudGenerator\Helpers;

use Ifsnop\Mysqldump\Mysqldump;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class CrudBackup
{

    /**
     * @throws \Exception
     * @return string
     */
    public static function backupDatabase($path = null)
    {
        ini_set('max_execution_time', 600);
        ini_set('memory_limit','2048M');

        $dbhost = $_ENV['DB_HOST'].':'.$_ENV['DB_PORT'];
        $dbuser = $_ENV['DB_USERNAME'];
        $dbpass = $_ENV['DB_PASSWORD'];
        $dbname = $_ENV['DB_DATABASE'];

        $fileName = "Backup-DB-". str_slug($_ENV['APP_NAME']) . date("Y-m-d-H-i-s") . '.sql';
        $backup_file = base_path($path.$fileName);

        $dump = new Mysqldump('mysql:host='.$dbhost.';dbname='.$dbname, $dbuser, $dbpass);
        $dump->start($backup_file);

        return base_path($path.$fileName);
    }

    public static function backupScript($source, $destination, $exceptionDir = []) {
        $exceptionDir = array_merge($exceptionDir, [base_path("vendor"),base_path(".git"),base_path(".idea")]);
        if (extension_loaded('zip') === true) {
            ini_set('max_execution_time', 600);
            ini_set('memory_limit','2048M');
            $lenDir = count(explode(DIRECTORY_SEPARATOR,$source));
            if (file_exists($source) === true) {
                $zip = new ZipArchive();

                if ($zip->open($destination, ZIPARCHIVE::CREATE) === true) {
                    $source = realpath($source);

                    if (is_dir($source) === true) {
                        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::CHILD_FIRST);

                        foreach ($files as $file) {
                            $file = realpath($file);
                            $lenFile = count(explode(DIRECTORY_SEPARATOR,$file));
                            if(!self::match($file,$exceptionDir) && $lenFile >= $lenDir) {
                                if (is_dir($file) === true && !preg_match('/^Backup-.+\.zip$/',$file)) {
                                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                                } else if (is_file($file) === true && !preg_match('/\.log$/',$file) && !preg_match('/^Backup-.+\.zip$/',$file)) {
                                    $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                                }
                            }
                        }
                    } else if (is_file($source) === true) {
                        $zip->addFromString(basename($source), file_get_contents($source));
                    }
                }
                $zip->close();
                return $destination;
            }
        }
        return false;
    }

    private static function match($item, $array)
    {
        $matching = array("\\" => "[\/|\\\]", "/" => "[\/|\\\]");
        foreach($array as $i)
        {
            $str = strtr($i, $matching); //creates the regex
            if(preg_match("/".$str."/i", $item))
                return true;
        }

        return false;
    }

}
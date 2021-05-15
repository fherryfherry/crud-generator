<?php


namespace CrudGenerator\Traits;


use SuperFrameworkEngine\Commands\OutputMessage;

trait CommandTrait
{
    use OutputMessage;

    private function copyViews($from,$to)
    {
        $data = glob(__DIR__.'/../Stubs/Views/'.$from.'/*.php.stub');
        foreach($data as $file) {
            copy($file, base_path($to."/".str_replace(".stub","",basename($file))));
        }
    }

    private function copyHelpers($from,$to)
    {
        $data = glob(__DIR__.'/../Stubs/Helpers/'.$from.'/*.php.stub');
        foreach($data as $file) {
            copy($file, base_path($to."/".str_replace(".stub","",basename($file))));
        }
    }

    private function copyControllers($from,$to)
    {
        $data = glob(__DIR__.'/../Stubs/Controllers/'.$from.'/*.php.stub');
        foreach($data as $file) {
            copy($file, base_path($to."/".str_replace(".stub","",basename($file))));
        }
    }

    private function makeDirectory($path) {
        if(!file_exists(base_path($path))) {
            mkdir(base_path($path));
        }
        file_put_contents(base_path($path."/index.html"),"");
    }

    private function publishAssets()
    {
        if(!file_exists(public_path("vendor"))) {
            mkdir(public_path("vendor"));
        }

        if(!file_exists(public_path("vendor/fherryfherry"))) {
            mkdir(public_path("vendor/fherryfherry"));
        }

        if(!file_exists(public_path("vendor/fherryfherry/crud_generator"))) {
            mkdir(public_path("vendor/fherryfherry/crud_generator"));
        }

        $this->success("Copying asset...");
        $this->recurseCopy(__DIR__.'/../Assets', public_path('vendor/fherryfherry/crud_generator'));
        $this->success("Asset has been copied!");
    }

    private function recurseCopy($src,$dst, $childFolder='') {

        $dir = opendir($src);
        mkdir($dst);
        if ($childFolder!='') {
            mkdir($dst.DIRECTORY_SEPARATOR.$childFolder);

            while(false !== ( $file = readdir($dir)) ) {
                if (( $file != '.' ) && ( $file != '..' )) {
                    if ( is_dir($src . DIRECTORY_SEPARATOR . $file) ) {
                        $this->recurseCopy($src . DIRECTORY_SEPARATOR . $file,$dst.DIRECTORY_SEPARATOR.$childFolder . DIRECTORY_SEPARATOR . $file);
                    }
                    else {
                        copy($src . DIRECTORY_SEPARATOR . $file, $dst.DIRECTORY_SEPARATOR.$childFolder . DIRECTORY_SEPARATOR . $file);
                    }
                }
            }
        }else{
            // return $cc;
            while(false !== ( $file = readdir($dir)) ) {
                if (( $file != '.' ) && ( $file != '..' )) {
                    if ( is_dir($src . DIRECTORY_SEPARATOR . $file) ) {
                        $this->recurseCopy($src . DIRECTORY_SEPARATOR . $file,$dst . DIRECTORY_SEPARATOR . $file);
                    }
                    else {
                        copy($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
                    }
                }
            }
        }

        closedir($dir);
    }

}
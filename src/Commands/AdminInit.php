<?php


namespace CrudGenerator\Commands;


use CrudGenerator\Traits\CommandTrait;
use SuperFrameworkEngine\Commands\OutputMessage;
use SuperFrameworkEngine\Helpers\ShellProcess;

class AdminInit
{
    use OutputMessage, CommandTrait;

    /**
     * @description Init admin area
     * @command admin:init
     */
    public function run() {
        // Publish asset
        $this->publishAssets();

        // Re run migration
        ShellProcess::run("cd ".base_path()." && ".PHP_BINARY." super migrate --ignore-header");

        // Re-Run make model
        ShellProcess::run("cd ".base_path()." && ".PHP_BINARY." super make:model --ignore-header");

        // Prepare directories
        $this->prepareDirectories();

        // Make Admin auth module
        $this->makeAuth();

        // Make profile
        $this->makeProfile();

        // Make dashboard
        $this->makeDashboard();

        // Compile
        ShellProcess::run("cd ".base_path()." && ".PHP_BINARY." super compile --ignore-header");

        $this->success("Admin area has been initialized!");
        $this->success("You can visit: /admin/auth/login");
    }

    private function prepareDirectories()
    {
        // Prepare directories
        $this->makeDirectory("app/Modules/Admin");
        $this->makeDirectory("app/Modules/Admin/Configs");
        $this->makeDirectory("app/Modules/Admin/Controllers");
        $this->makeDirectory("app/Modules/Admin/Helpers");
        $this->makeDirectory("app/Modules/Admin/Views");
        $this->copyViews("Layout","app/Modules/Admin/Views");
    }

    private function makeAuth()
    {
        $this->makeDirectory("app/Modules/Admin/Views/auth");

        // Copy Auth Controller & Helper
        $this->copyHelpers("Auth","app/Modules/Admin/Helpers");
        $this->copyControllers("Auth","app/Modules/Admin/Controllers");
        $this->copyHelpers("General","app/Helpers");

        // Copy views
        $this->makeDirectory("app/Modules/Admin/Views/auth");
        $this->copyViews("Auth","app/Modules/Admin/Views/auth");
    }

    private function makeProfile()
    {
        // copy controller
        $this->copyControllers("Profile","app/Modules/Admin/Controllers");

        // make profile views
        $this->makeDirectory("app/Modules/Admin/Views/profile");
        $this->copyViews("Profile","app/Modules/Admin/Views/profile");
    }

    private function makeDashboard()
    {
        // copy controller
        $this->copyControllers("Dashboard","app/Modules/Admin/Controllers");

        // make profile views
        $this->makeDirectory("app/Modules/Admin/Views/dashboard");
        $this->copyViews("Dashboard","app/Modules/Admin/Views/dashboard");
    }


}
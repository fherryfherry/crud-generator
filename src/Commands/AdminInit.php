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

        // Publish Migration
        $this->publishMigration();

        // Re run migration
        ShellProcess::run("cd ".base_path()." && ".PHP_BINARY." super migrate --ignore-header");

        // Re-Run make model
        ShellProcess::run("cd ".base_path()." && ".PHP_BINARY." super make:model --ignore-header");

        // Prepare directories
        $this->prepareDirectories();

        // Copy Repo And Model
        $this->prepareModelAndRepository();

        // Make Admin auth module
        $this->makeAuth();

        // Make profile
        $this->makeProfile();

        // Make dashboard
        $this->makeDashboard();

        // Make user
        $this->makeUser();

        // Make roles
        $this->makeRoles();

        // Make menu
        $this->makeAdminMenus();

        // Make setting
        $this->makeSetting();

        // Create Dummy User
        $newDummyUser = false;
        if(db("users")->where("name = 'superadmin' or email = 'superadmin@example.com'")->count() == 0) {
            $newDummyUser = true;
            ShellProcess::run("cd ".base_path()." && ".PHP_BINARY." super make:user --email=\"superadmin@example.com\" --password=\"123456\" --role=\"Super Admin\" --ignore-header");
        }

        // Compile
        ShellProcess::run("cd ".base_path()." && ".PHP_BINARY." super compile --ignore-header");

        $this->success("Admin area has been initialized!");
        $this->success("You can visit: /admin/auth/login");

        if($newDummyUser) {
            $this->success("Email: superadmin@example.com");
            $this->success("Password: 123456");
        }

    }

    private function makeAdminMenus()
    {
        // copy controller
        $this->copyControllers("AdminMenus","app/Modules/Admin/Controllers");

        // make views
        $this->makeDirectory("app/Modules/Admin/Views/admin_menu");
        $this->copyViews("AdminMenus","app/Modules/Admin/Views/admin_menu");
    }

    private function makeRoles()
    {
        // copy controller
        $this->copyControllers("Roles","app/Modules/Admin/Controllers");

        // make views
        $this->makeDirectory("app/Modules/Admin/Views/roles");
        $this->copyViews("Roles","app/Modules/Admin/Views/roles");

        // make super admin role
        if(db("roles")->where("name = 'Super Admin'")->count() == 0) {
            db("roles")->insert([
                "name"=> "Super Admin"
            ]);
        }

    }

    private function makeSetting()
    {
        // copy controller
        $this->copyControllers("Setting","app/Modules/Admin/Controllers");

        // make views
        $this->makeDirectory("app/Modules/Admin/Views/setting");
        $this->copyViews("Setting","app/Modules/Admin/Views/setting");
    }

    private function makeUser()
    {
        // copy controller
        $this->copyControllers("Users","app/Modules/Admin/Controllers");

        // make views
        $this->makeDirectory("app/Modules/Admin/Views/users");
        $this->copyViews("Users","app/Modules/Admin/Views/users");
    }

    private function publishMigration()
    {
        $this->copyMigrations("app/Migrations/Databases");
    }

    private function prepareModelAndRepository()
    {
        $this->copyModels("app/Models");
        $this->copyRepositories("app/Repositories");
        $this->copyServices("app/Services");
    }

    private function prepareDirectories()
    {
        // Prepare directories
        $this->makeDirectory("app/Models");
        $this->makeDirectory("app/Repositories");
        $this->makeDirectory("app/Services");
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
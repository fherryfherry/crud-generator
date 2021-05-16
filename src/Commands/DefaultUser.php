<?php


namespace CrudGenerator\Commands;

use SuperFrameworkEngine\App\UtilSecurity\Hash;
use SuperFrameworkEngine\Commands\CommandArguments;
use SuperFrameworkEngine\Commands\OutputMessage;

class DefaultUser
{
    use OutputMessage, CommandArguments;

    /**
     * @description Create a user admin
     * @command make:user
     */
    public function run() {
        try {
            $email = $this->getArgument(func_get_args(), "email");
            $password = $this->getArgument(func_get_args(), "password");

            if(!$email) throw new \Exception("Please enter email argument");
            if(!$password) throw new \Exception("Please enter password argument");

            db("users")->insert([
                "name"=> strtok($email,"@"),
                "email"=> $email,
                "password"=> Hash::make($password)
            ]);

            $this->success("New user has been created!");
        } catch (\Exception $e) {
            $this->danger($e->getMessage());
        }
    }
}
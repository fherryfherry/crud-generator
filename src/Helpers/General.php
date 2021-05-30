<?php

if(!function_exists("get_cpu")) {
    function get_cpu(){
        if(function_exists("sys_getloadavg")) {
            $load = sys_getloadavg();
            return $load[0];
        } else {
            return 0.0;
        }
    }
}

if(!function_exists("get_ram")) {
    function get_ram(){
        $free = shell_exec('free');
        $free = (string)trim($free);
        $free_arr = explode("\n", $free);
        $mem = explode(" ", $free_arr[1]);
        $mem = array_filter($mem);
        $mem = array_merge($mem);
        $memory_usage = $mem[2]/$mem[1]*100;
        return $memory_usage;
    }
}
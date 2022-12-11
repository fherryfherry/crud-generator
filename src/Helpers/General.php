<?php

if(!function_exists('show_image_html')) {
    function show_image_html($urlImage,$width="150px",$height="auto",$class="",$style="") {
        if($urlImage) {
            return "<img src='".asset($urlImage)."' width='".$width."' height='".$height."' class='".$class."' style='".$style."'/>";
        }
        return null;
    }
}

if(!function_exists('show_downloadable_link')) {
    function show_downloadable_link($url,$label="",$target="_blank",$class="",$style="") {
        if($url) {
            $label = $label ?: basename($url);
            return "<a href='".asset($url)."' class='".$class."' target='".$target."' style='".$style."'>".$label."</a>";
        }
        return null;
    }
}

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
        $memory_usage = 0;
        if ($mem && is_array($mem) && isset($mem[2]) && isset($mem[1])) {
            $memory_usage = $mem[2]/$mem[1]*100;
        }
        return $memory_usage;
    }
}
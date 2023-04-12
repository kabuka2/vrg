<?php


function debug($data = '')
{
    $trace = debug_backtrace();
    $trace = array_shift($trace);
    printf(
        '<br><br><b>Call file: %s  line: %s <br> type: %s <br> ' ,
        $trace['file'],
        $trace['line'],
        gettype($data),
    );

    if (is_bool($data)) {
        var_dump($data);
    } else if (is_string($data) || is_numeric($data)) {
        printf('string length: %s symbols <br>  string: %s <br><br><br>',strlen($data),$data);
    } else {
        echo '<pre>';
        print_r($data);
        echo '</pre> <br>';
    }
}

function dd($data = '')
{
    $trace = debug_backtrace();
    $trace = array_shift($trace);
    printf(
        '<br><br><b>Call file: %s  line: %s <br> type: %s <br> ' ,
        $trace['file'],
        $trace['line'],
        gettype($data),
    );

    if (is_bool($data)) {
        var_dump($data);
    } else if (is_string($data) || is_numeric($data)) {
        printf('string length: %s symbols <br>  string: %s',strlen($data),$data);
    } else {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
    exit();
}
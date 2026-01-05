<?php

$value = $_POST["value"];

$math_map = array("+","-","*","/");
$functions_map = [
    "+" => add($value),
    "-" => subtract($value),
    "*" => multiply($value),
    "/" => divide($value)
];
echo filter_data($value);
function filter_data($value){
    global $math_map, $functions_map;
    $value = trim($value);
    for($i=0; $i<strlen($value); $i++){
        if(in_array($value[$i],$math_map)){
            echo $functions_map[$value[$i]];
        }
    }
}



function add($value){
    $numbers = (explode("+", $value));
    return $numbers[0] + $numbers[1];
}

function subtract($value){
    $numbers = (explode("-", $value));
    return $numbers[0] - $numbers[1];
}

function multiply($value){
    $numbers = (explode("*", $value));
    return $numbers[0] * $numbers[1];
}

function divide($value){
    $numbers = (explode("/", $value));
    return $numbers[0] / $numbers[1];
}

?>
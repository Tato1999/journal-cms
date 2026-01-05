

<?php

    $value = $_POST["value"];

    $math_map = array("+","-","*","/");

    // echo $value;
    echo filter_data($value,$math_map);
    function filter_data($value,$math_map ){
        $value = trim($value);
        for($i=0; $i<strlen($value); $i++){
            if(in_array($value[$i],$math_map)){
                echo calculation($value,$value[$i]);
            }
        };
    };

    function calculation($value, $type){
        $numbers = (explode($type, $value));
        // echo $numbers[0]." asdfsa ".$numbers[1];
        return eval("return $numbers[0] $type $numbers[1];");
    }
    

?>
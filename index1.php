

<?php

    $value = $_POST["value"];
    // echo $value;
    $elements = (explode(" ",$value));
    print_r($elements);
    // echo filter_data($value,$math_map);
    // function filter_data($value,$math_map ){
    //     $value = trim($value);
    //     for($i=0; $i<strlen($value); $i++){
    //         if(in_array($value[$i],$math_map)){
    //             echo calculation($value,$value[$i]);
    //         }else{
    //             return "Invalid Operator";
    //         }
    //     };
    // };

    // function calculation($value, $type){
    //     $numbers = (explode($type, $value));
    //     return eval("return $numbers[0] $type $numbers[1];");;
    // }
    

?>
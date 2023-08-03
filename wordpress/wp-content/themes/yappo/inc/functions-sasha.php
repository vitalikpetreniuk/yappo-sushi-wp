<?php
function pr($arr, $field = '', $pre = true)	{
    if($pre) echo "<pre>";
    if(isset($field)&&empty($field))	print_r($arr);
    else
        foreach($arr as $item) {
            if(gettype($field) == 'string')	$arrfields = array($field); else $arrfields = $field;
            $row = array();
            if(gettype($item)=='object') foreach($arrfields as $f) $row[] = "<b>".$f."</b> => ".$item->$f;
            else foreach($arrfields as $f) $row[] = "<b>".$f."</b> => ".$item[$f];
            echo implode(" | ",$row)."<br>";
        }
    if($pre) echo "</pre>";
}
?>
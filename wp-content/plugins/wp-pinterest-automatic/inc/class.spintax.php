<?php

if(! class_exists('Spintax')){

class Spintax {

   function spin($string, $view=false)
   {
      $z=-1;
      $input = $this->bracketArray($string);
      for($i=0; $i<count($input);$i++){
         for($x=0; $x<count($input[$i]);$x++) {
            if(!$input[$i][$x]==""||"/n"){
               $z++;
               if(strstr($input[$i][$x], "|")){
                  $out = explode("|", $input[$i][$x]);
                  $output[$z] = $out[rand(0, count($out)-1)];
               } else {
                  $output[$z] = $input[$i][$x];
               }
            }
         }
      }
      $res='';
      for($i=0;$i<count($output);$i++){
        $res .=  $output[$i];
      }
      return $res;
      if($view==true){
         echo "<hr>";
         $output = $this->cleanArray($output);
         $this->printArray($output);
      }
   }
   
   
   function bracketArray($str, $view=false)
   {
      @$string = split("{", $str);
      for($i=0;$i<count($string);$i++){
         @$_string[$i] = split("}", $string[$i]);
      }
      if($view){
         $this->printArray($_string);
      }
      return $_string;
   }
   
   function cleanArray($array){
      for($i=0;$i<count($array);$i++){
         if($array[$i]!=""){
            $cleanArray[$i] = $array[$i];
         }
      }
      return $cleanArray;
   }
   
   function printArray($array)
   {
      echo '<pre>';
      print_r($array);
      echo '</pre>';
   }
}

}

?>
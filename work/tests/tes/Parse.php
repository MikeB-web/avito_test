<?php

class Parse
{

 public function __construct(&$json1, &$arr1)
 {
  if(is_array($json1) && count($json1)==1 && count($json1["params"])>0 && is_array($arr1) && count($arr1)==1 && count($arr1["values"])>0)
  {
   $this->parsing($json1["params"], $arr1["values"]);
  }
  else
  {
    $this->error();
  }
 }

 private function parsing(&$x, &$ars)
 {

  foreach($x as &$d)
  { 
   $j=1;
   if(count($d)==3 && is_int($d["id"]) && is_string($d["title"]) && is_string($d["value"]) && $d["value"]=="")
   {
    foreach($ars as $ard => $ardk)
    {
     if(isset($ars[$ard]["id"]) || isset($ars[$ard]["value"]))
     {
     if($d["id"]==$ars[$ard]["id"])
     {
      $d["value"]=$ars[$ard]["value"];
      unset($ars[$ard]);
      break;
     }
     }
     else
     {
       $this->error();
     }
    }
   }


  elseif(count($d)==4 && is_int($d["id"]) && is_string($d["title"]) && is_string($d["value"]) && $d["value"]=="" && is_array($d["values"]) && count($d["values"])>0)
  {
  foreach($d["values"] as &$mas)
  {
   if(isset($mas["id"]) && isset($mas["title"]) || is_array($mas["params"]))
   {
   foreach($ars as $ard => $ardk)
    {
     if(isset($ars[$ard]["id"]) || isset($ars[$ard]["value"]))
     {
     if($d["id"]==$ars[$ard]["id"] && $mas["id"]==$ars[$ard]["value"])
     {
      $d["value"]=$mas["title"];
      unset($ars[$ard]);
      break;
     }
     }
     else
     {
      $this->error();
     }
    }

   if($mas["params"]!=NULL)
   {
   $this->parsing($mas["params"], $ars);
   }

   }
  }
}
   else
    {
     $this->error();
    }

 }
}
 
 private function error()
 {
  echo "File error";
  $errors=array("error"=>array("message"=>"Входные файлы некорректны"));
  $err=json_encode($errors, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
 
  $a=array();
  $a[0]="/{(\s+)/";
  $a[1]="/\"message\"/";
  $a[2]="/(\s+)}{1}/";

  $b=array();
  $b[0]="{\r\n\${1}      ";
  $b[1]="    \"message\"";
  $b[2]="\r\n\${1}}";

  $err2=preg_replace($a, $b, $err);

   
   file_put_contents(dirname(__FILE__).'\error.json', $err2);
 
 }

 public function message()
 {
  if(count($ars["values"])!==0)
  {
    echo "В файле Values.json остались значения!";
  }
 }
 


 public function result($res)
 {
  
  $out=json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

  $search=array();
  $search[0]="/{
(\s+)\"params\"/";
  $search[1]="/\[(\s+){(\s+)/";
  $search[2]="/,/";
  $search[3]="/(\s+)},(\s+){(\s+)/";
  $search[4]="/(\s+)}(\s+)]/";
  $search[5]="/}]
}/";

  $r=array();
  $r[0]="{\r\n\${1}    \"params\"";
  $r[1]="[{\r\n\${2}    ";
  $r[2]=",\r\n    ";
  $r[3]="\r\n\${1}}, {\r\n\${3}    ";
  $r[4]="\r\n\${1}}]";
  $r[5]="}]
\r\n}";

  $rep=preg_replace($search, $r, $out);

  if (is_writable(dirname(__FILE__)."\StructureWithValues.json")) {
    file_put_contents(dirname(__FILE__)."\StructureWithValues.json", $rep);
  } else {
    echo 'Файл недоступен для записи';
  }
 
 }

}

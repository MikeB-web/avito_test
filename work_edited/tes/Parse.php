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
  $errors=array("error"=>array("message"=>"Входные файлы некорректны"));
  $err=json_encode($errors, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  $err=str_replace("\n", "\r\n", $err);
   
   file_put_contents(dirname(__FILE__).'\error.json', $err);
 
 }

 public function message($res_arr)
 {
  if(count($res_arr)!==0)
  {
    echo "В файле Values.json остались значения!";
    return 1;
  }
 }
 


 public function result($res)
 {
  $out=json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
   
  $out=str_replace("\n", "\r\n", $out);

  if (is_writable(dirname(__FILE__)."\StructureWithValues.json")) {
    file_put_contents(dirname(__FILE__)."\StructureWithValues.json", $out);
  } else {
    echo 'Файл недоступен для записи';
  }
 
 }

}
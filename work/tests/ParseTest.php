<?php
set_include_path(dirname(__FILE__)."/tes/");
require 'Parse.php';
use PHPUnit\Framework\TestCase;

class ParseTest extends TestCase
{

  private $parse;
  public $msg;

    public function setUp()
    {
    $json2=file_get_contents(dirname(__FILE__)."\TestcaseStructure.json");
     $arr2=file_get_contents(dirname(__FILE__)."\Values.json");

     if(substr($json2, 0, 3) == pack("CCC", 0xEF, 0xBB, 0xBF)) {
      $json2 = substr($json2, 3);
      }

      if(substr($arr2, 0, 3) == pack("CCC", 0xEF, 0xBB, 0xBF)) {
       $arr2 = substr($arr2, 3);
       }



        $json=json_decode($json2, true);
        $arr=json_decode($arr2, true);

        $this->parse=new Parse($json, $arr);
        $this->parse->result($json);
        $this->msg=$this->parse->message();
        

    }
 
    public function tearDown()
    {
        $this->n = NULL;
    }
 
    public function testresult()
    {

        $this->assertJsonFileEqualsJsonFile(dirname(__FILE__).'\tes\Example.json', dirname(__FILE__).'\tes\StructureWithValues.json');
    }

     public function testmessage()
     {
        
        $this->assertEquals(0, $this->msg);
     }
    
    public function testerror()
    {
      $this->assertJsonFileEqualsJsonFile(dirname(__FILE__).'\tes\error_example.json', dirname(__FILE__).'\tes\error.json');
    }

}
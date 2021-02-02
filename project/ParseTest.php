<?php
set_include_path(dirname(__FILE__)."/test/");

$f=file_get_contents(dirname(__FILE__).'\test\Parse.php');
if(substr($f, 0, 3) == pack("CCC", 0xEF, 0xBB, 0xBF)) {
    	$f = substr($f, 3);
file_put_contents(dirname(__FILE__).'\test\Parse.php', $f);
	}

$s=file_get_contents(dirname(__FILE__).'\test\Example.json');
if(substr($s, 0, 3) == pack("CCC", 0xEF, 0xBB, 0xBF)) {
    	$s = substr($s, 3);
file_put_contents(dirname(__FILE__).'\test\Example.json', $s);
	}

require 'Parse.php';
use PHPUnit\Framework\TestCase;

class Parsetest extends TestCase
{

  private $parse;
  private $json;
  private $arr;

    public function setUp()
    {

        $json2=file_get_contents(dirname(__FILE__).'\testcaseStructure.json');
        $arr2=file_get_contents(dirname(__FILE__).'\Values.json');

	if(substr($json2, 0, 3) == pack("CCC", 0xEF, 0xBB, 0xBF)) {
    	$json2 = substr($json2, 3);
	}

	if(substr($arr2, 0, 3) == pack("CCC", 0xEF, 0xBB, 0xBF)) {
   	$arr2 = substr($arr2, 3);
	}

        


	$this->json=json_decode($json2, true);
        $this->arr=json_decode($arr2, true);


	$this->parse = new Parse($this->json, $this->arr);

    }
 
    public function tearDown()
    {
        $this->parse = NULL;
    }
 
    public function testresult()
    {

        $this->parse->result($this->json);
        $this->assertJsonFileEqualsJsonFile(dirname(__FILE__).'\test\Example.json', dirname(__FILE__).'\test\StructureWithValues.json');
    }

     public function testmessage()
     {
        
        $this->assertEquals(1, $this->parse->message($this->arr));
     }
    
    public function testerror()
    {
      $this->assertJsonFileEqualsJsonFile(dirname(__FILE__).'\test\error_example.json', dirname(__FILE__).'\test\error.json');
    }

}
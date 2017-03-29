<?php
  /**
  * @backupGlobals disabled
  * @backupStaticAttributes disabled
  */

  require_once "src/Cuisine.php";
  require_once "src/Restaurant.php";

  $server = 'mysql:host=localhost:8889;dbname=best_restaurants_test';
  $user = 'root';
  $pass = 'root';
  $db = new PDO($server,$user,$pass);

  class ClassTest extends PHPUnit_Framework_TestCase {

    protected function tearDown(){
      Cuisine::deleteAll();
      Restaurant::deleteAll();
    }

    function testSave(){
      $name = "Chinese";
      $test_cuisine = new Cuisine($name);
      $test_cuisine->save();
      $cuisine_id = $test_cuisine->getId();

      $rest = "Chicken Feet";
      $test_rest = new Restaurant($rest, $cuisine_id);
      $executed = $test_rest->save();

      $this->assertTrue($executed, "Task not successfully saved to database");
    }

  }

?>

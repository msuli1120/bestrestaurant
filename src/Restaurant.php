<?php
  class Restaurant {
    private $id;
    private $name;
    private $cuisine_id;

    function __construct ($name, $cuisine_id, $id=null){
      $this->name = $name;
      $this->cuisine_id = $cuisine_id;
      $this->id = $id;
    }

    function getId(){
      return $this->id;
    }

    function setName($new_name){
      $this->name = (string) $new_name;
    }

    function getName(){
      return $this->name;
    }

    function setCuisineId($new_cuisine_id){
      $this->cuisine_id = (int) $new_cuisine_id;
    }

    function getCuisineId(){
      return $this->cuisine_id;
    }

    function save(){
      $executed = $GLOBALS['db']->exec("INSERT INTO restaurants (name, cuisine_id) VALUES ('{$this->getName()}', {$this->getCuisineId()});");
      if($executed){
        $this->id = $GLOBALS['db']->lastInsertId();
        return true;
      } else {
        return false;
      }
    }

    static function getRestaurants($id){
      $executed = $GLOBALS['db']->prepare("SELECT * FROM restaurants WHERE cuisine_id = :id;");
      $executed->bindParam(':id', $id, PDO::PARAM_STR);
      $executed->execute();
      $results= $executed->fetchAll(PDO::FETCH_OBJ);
      return $results;
    }

    static function find($id){
      $new_rest = null;
      $executed = $GLOBALS['db']->prepare("SELECT * FROM restaurants WHERE id = :id;");
      $executed->bindParam(':id', $id, PDO::PARAM_STR);
      $executed->execute();
      $result = $executed->fetch(PDO::FETCH_ASSOC);
      if($result['id']==$id){
        $new_rest = new Restaurant($result['name'], $result['cuisine_id'], $result['id']);
        return $new_rest;
      }
    }

    static function getAll(){
      $return = $GLOBALS['db']->query("SELECT * FROM restaurants;");
      $results = $return->fetchAll(PDO::FETCH_OBJ);
      return $results;
    }

    static function deleteAll(){
      $GLOBALS['db']->exec("DELETE FROM restaurants;");
    }
  }
?>

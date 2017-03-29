<?php

  class Cuisine {

    private $id;
    private $name;

    function __construct ($name, $id=null) {
      $this->name = $name;
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

    function save(){
      $executed = $GLOBALS['db']->exec("INSERT INTO cuisines (name) VALUES ('{$this->getName()}');");
      if($executed){
        $this->id = $GLOBALS['db']->lastInsertId();
        return true;
      } else {
        return false;
      }
    }

    static function getAll(){
      $returned = $GLOBALS['db']->query("SELECT * FROM cuisines;");
      $results = $returned->fetchAll(PDO::FETCH_OBJ);
      return $results;
    }

    static function find($id){
      $executed = $GLOBALS['db']->prepare("SELECT * FROM cuisines WHERE id = :id;");
      $executed->bindParam(':id', $id, PDO::PARAM_STR);
      $executed->execute();
      $result = $executed->fetch(PDO::FETCH_ASSOC);
      if($result['id']==$id){
        $new_cuisine = new Cuisine($result['name'],$result['id']);
      }
      return $new_cuisine;
    }

    static function deleteAll(){
      $GLOBALS['db']->exec("DELETE FROM cuisines;");
    }


  }
?>

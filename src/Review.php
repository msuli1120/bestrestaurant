<?php
  class Review {
    private $id;
    private $review;
    private $restaurant_id;

    function __construct($review, $restaurant_id, $id=null){
      $this->review = $review;
      $this->restaurant_id = $restaurant_id;
      $this->id = $id;
    }

    function getId(){
      return $this->id;
    }

    function setReview($new_review){
      $this->review = (string) $new_review;
    }

    function getReview(){
      return $this->review;
    }

    function setRestaurantId($new_restaurant_id){
      $this->restaurant_id = (int) $new_restaurant_id;
    }

    function getRestaurantId(){
      return $this->restaurant_id;
    }

    function save(){
      $executed = $GLOBALS['db']->exec("INSERT INTO reviews (review, restaurant_id) VALUES ('{$this->getReview()}', {$this->getRestaurantId()});");
      if($executed){
        $this->id = $GLOBALS['db']->lastInsertId();
        return true;
      } else {
        return false;
      }
    }

    static function find($id){
      $returned_array = array ();
      $executed = $GLOBALS['db']->prepare("SELECT * FROM reviews WHERE restaurant_id = :id;");
      $executed->bindParam(':id', $id, PDO::PARAM_STR);
      $executed->execute();
      $results = $executed->fetchAll(PDO::FETCH_ASSOC);
      foreach($results as $result){
        $new_review = new Review($result['review'], $result['restaurant_id'], $result['id']);
        array_push($returned_array,$new_review);
      }
      return $returned_array;
    }

    static function findReview($id){
      $return = null;
      $execute = $GLOBALS['db']->prepare("SELECT * FROM reviews WHERE id=:id;");
      $execute->bindParam(':id', $id, PDO::PARAM_STR);
      $execute->execute();
      $result = $execute->fetch(PDO::FETCH_ASSOC);
      $return = new Review($result['review'], $result['restaurant_id'], $result['id']);
      return $return;
    }

    function update($new_review){
      $executed = $GLOBALS['db']->exec("UPDATE reviews SET review = '{$new_review}' WHERE id = {$this->getId()};");
      if ($executed){
        $this->setReview($new_review);
        return true;
      } else {
        return false;
      }
    }

    function delete(){
      $executed = $GLOBALS['db']->exec("DELETE FROM reviews WHERE id = {$this->getId()};");
      if(!$executed){
        return false;
      } else {
        return true;
      }
    }

  }
?>

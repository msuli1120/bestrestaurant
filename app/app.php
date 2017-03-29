<?php
  date_default_timezone_set('America/Los_Angeles');
   require_once __DIR__."/../vendor/autoload.php";
   require_once __DIR__."/../src/Cuisine.php";
   require_once __DIR__."/../src/Restaurant.php";
   require_once __DIR__."/../src/Review.php";

  use Symfony\Component\Debug\Debug;
  Debug::enable();

  $app = new Silex\Application();

  $app['debug'] = true;

  $server = 'mysql:host=localhost:8889;dbname=best_restaurants';
  $user = 'root';
  $pass = 'root';
  $db = new PDO($server, $user, $pass);

  $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
  ));

  use Symfony\Component\HttpFoundation\Request;
  Request::enableHttpMethodParameterOverride();

  $app->get("/", function () use ($app) {
    return $app['twig']->render('index.html.twig');
  });

  $app->post("/addcuisine", function () use ($app) {
    if(!empty($_POST['cuisine'])){
      $new_cuisine = new Cuisine($_POST['cuisine']);
      $new_cuisine->save();
      $results = Cuisine::getAll();
      return $app['twig']->render('cuisines.html.twig', array ('results'=>$results));
    } else {
      return $app['twig']->render('warning.html.twig');
    }
  });

  $app->post("/cuisines", function () use ($app) {
    $results = Cuisine::getAll();
    if(!empty($results)){
      return $app['twig']->render('cuisines.html.twig', array ('results'=>$results));
    } else {
      return $app['twig']->render('empty.html.twig');
    }
  });

  $app->get("/cuisine/{id}", function ($id) use ($app) {
    $cuisine = Cuisine::find($id);
    return $app['twig']->render('addrest.html.twig', array ('cuisine'=>$cuisine));
  });

  $app->post("/addrest", function () use ($app) {
    if(!empty($_POST['rest'])){
      $new_rest = new Restaurant($_POST['rest'], $_POST['cuisine_id']);
      $new_rest->save();
      return $app['twig']->render('restaurant.html.twig', array ('restaurant'=>$new_rest));
    } else {
      return $app['twig']->render('warning.html.twig');
    }
  });

  $app->get("/cuisinerest/{id}", function ($id) use ($app) {
    $restaurants = Restaurant::getRestaurants($id);
    $cuisine = Cuisine::find($id);
    return $app['twig']->render('restlists.html.twig', array ('restaurants'=>$restaurants, 'cuisine'=>$cuisine));
  });

  $app->get("/restaurant/{id}", function ($id) use ($app) {
    $restaurant = Restaurant::find($id);
    return $app['twig']->render('restaurant.html.twig', array('restaurant'=>$restaurant));
  });

  $app->get("/byname", function () use ($app) {
    $restaurants = Restaurant::getAll();
    return $app['twig']->render('restaurants.html.twig', array ('restaurants'=>$restaurants));
  });

  $app->post("/addreview", function () use ($app) {
    $new_review = new Review($_POST['review'], $_POST['restid']);
    $new_review->save();
    $restid = $new_review->getRestaurantId();

    return $app['twig']->render('reviews.html.twig', array('restid'=>$restid));
  });

  $app->get("/seereviews/{id}", function ($id) use ($app) {
    $restaurant = Restaurant::find($id);
    $reviews = Review::find($id);
    print_r($reviews);
    return $app['twig']->render('allreviews.html.twig', array('restaurant'=>$restaurant, 'reviews'=>$reviews));
  });

  $app->get("/review/{id}/edit", function ($id) use ($app) {
    $review = Review::findReview($id);
    return $app['twig']->render('edit.html.twig', array('review'=>$review));
  });

  $app->patch("/review/{id}", function ($id) use ($app) {
    $new_review = $_POST['review'];
    $review = Review::findReview($id);
    $review->update($new_review);
    return $app['twig']->render('index.html.twig');
  });

  $app->delete("/review/{id}", function ($id) use ($app) {
    $review = Review::findReview($id);
    $review->delete();
    return $app['twig']->render('index.html.twig');
  });

  return $app;
?>

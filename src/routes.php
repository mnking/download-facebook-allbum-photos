<?php
// Routes

$app->get('/', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->get('/album/{id}', function ($request, $response, $args) {

    $fb = new Facebook\Facebook([
        'app_id' => '944849028931682',
        'app_secret' => 'f72405fa16dfaf7e3ce83f4a470a1d0e',
        'default_graph_version' => 'v2.5',
        'default_access_token' => 'CAANbVc68YGIBAM6utZBbLa8Jip4ZAZCdskVLX9L5q5Q85QxZAyLUdeI1XMPYj8jw7CTll2xpjUu5BBtKbwSoWsV9VXEA9oeOY19YZCqmexHl2LP0OBRu46ryhW48wUyMK0IjPLBkJhLbtDbzSEPqJje8VA8xfp91l5YRAwtdKOJb9oZCzF8exFxS54JoMiJ6Kz1DV5mqTk0AZDZD'
    ]);


    try {
        $response = $fb->get('/'.$args['id'].'/photos?fields=images&limit=100');
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }

    $this->page->setZipName($args['id']);

    $this->page->get($response);
});
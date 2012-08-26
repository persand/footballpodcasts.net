<?php
require 'vendor/autoload.php';

$source = 'https://docs.google.com/spreadsheet/pub?key=0AnhHhmlRnJsSdEtuZGZJNU43eFlxclg0aXdkTXNPZlE&output=csv';
$yml_dir = __DIR__ . '/jekyll/_podcast/';
$image_dir = __DIR__ . '/jekyll/img/podcasts/';

$podcast_generator = new \FootballPodcasts\PodcastGenerator($source, $yml_dir, $image_dir);

$podcast_generator->readSource();

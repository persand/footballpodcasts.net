<?php
require 'vendor/autoload.php';

//require_once 'lib/YMLCreator.php';

$source = 'https://docs.google.com/spreadsheet/pub?key=0AnhHhmlRnJsSdEtuZGZJNU43eFlxclg0aXdkTXNPZlE&output=csv';
$yml_dir = 'jekyll/_podcast/';
$image_dir = 'jekyll/img/podcasts/';

$yml_creator = new Footballpodcasts\YMLCreator($source, $yml_dir, $image_dir);

$yml_creator->readSource();

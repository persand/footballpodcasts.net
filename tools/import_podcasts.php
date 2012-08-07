<?php

function create_slug($string) {
  $slug = trim($string);
  $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $slug);
  $slug = strtolower($slug);

  return $slug;
}

function clean_up_field($string) {
  $string = str_replace('"', '\"', $string);

  return $string;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Convert Podcast CSV to YML</title>
</head>
<body>
<?php

if (!empty($_GET['convert'])) {
  $fields = array();
  $fields[0] = 'title';
  $fields[1] = 'url';
  $fields[2] = 'feed';
  $fields[3] = 'itunes';
  $fields[4] = 'description';
  $fields[5] = 'language';
  $fields[6] = 'image';

  $row = 1;
  $file = 'https://docs.google.com/spreadsheet/pub?key=0AnhHhmlRnJsSdEtuZGZJNU43eFlxclg0aXdkTXNPZlE&output=csv';
  if (($handle = fopen($file, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      $num = count($data);

      if ($row > 1) {
        echo "<p> $num fields in line $row: <br /></p>\n";

        $podcast = '';
        $slug = '';

        for ($c=0; $c < $num; $c++) {
          if (empty($slug)) {
            $slug = create_slug($data[$c]);
          }

          $field = clean_up_field($data[$c]);

          $podcast .= $fields[$c] . ': "' . $field . '"' . "\n";
        }
        echo $podcast;

        $yml_file = 'files/'.$slug.'.yml';
        $yml_file = fopen($yml_file, 'w') or die('Cannot open file:  '.$yml_file);
        fwrite($yml_file, $podcast);
        fclose($yml_file);

        unset($slug);
        unset($podcast);
      } else {
        echo '<p>Skipped row ' . $row . '</p>';
      }

      $row++;
    }
    fclose($handle);

    echo '<a href="podcast.php">Back</a>';
  }
} else {
  echo '<a href="?convert=1">Convert</a>';
}
?>
</body>
</html>

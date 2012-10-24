<?php
namespace FootballPodcasts;

Class PodcastGenerator {
  private $source;
  private $yml_directory;
  private $image_directory;
  private $fields;
  private $row = 1;
  private $skip_rows = 1;
  private $handle;
  private $data;
  private $data_num;
  private $slug;
  private $content;

  function __construct($source, $yml_directory, $image_directory)
  {
    $this->source = $source;
    $this->yml_directory = $yml_directory;
    $this->image_directory = $image_directory;

    $this->fields = array(
      'title',
      'url',
      'feed',
      'itunes',
      'description',
      'language',
      'image',
    );
  }

  public function readSource()
  {
    if ($this->sourceIsValid()) {
      $this->removeYML();

      $this->readData();
    }
  }

  private function sourceIsValid() {
    try {
        $this->handle = fopen($this->source, "r");
    } catch (Exception $e) {
        throw new Exception('Invalid source: ' .$this->source);
    }

    return true;
  }

  private function removeYML() {
    foreach (new \DirectoryIterator($this->yml_directory) as $file) {
      if ($file->getExtension() == 'yml') {
        unlink($this->yml_directory . $file->getFilename());
      }
    }
  }

  private function readData() {
    while ($this->data = fgetcsv($this->handle, 1000, ",")) {
      $this->data_num = count($this->data);

      if ($this->row > $this->skip_rows) {
        $this->createFile();
      }

      $this->row++;
    }

    fclose($this->handle);
  }

  private function createFile() {
    for ($c = 0; $c < $this->data_num; $c++) {
      if (empty($this->slug)) {
        $this->slug = $this->createSlug($this->data[$c]);
      }

      $this->content .= $this->createValue($this->fields[$c], $this->data[$c]);
    }

    $yml_file = $this->yml_directory . $this->slug . '.yml';

    try {
      $yml_file = fopen($yml_file, 'w');
      fwrite($yml_file, $this->content);
      fclose($yml_file);
    } catch (Exception $e) {
      throw new Exception('Can\'t open file: ' . $yml_file);
    }

    $this->slug = '';
    $this->content = '';
  }

  private function createSlug($slug) {
    $slug = trim($slug);
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $slug);
    $slug = strtolower($slug);

    return $slug;
  }

  private function createValue($field, $data) {
    $data = $this->cleanUpField($data);

    if ($field == 'image' && !empty($data)) $data = $this->saveImage($data);

    if (empty($data)) return false;

    return $field . ': "' . $data . '"' . "\n";
  }

  private function cleanUpField($string) {
    $string = str_replace('"', '\"', $string);

    return $string;
  }

  private function saveImage($url) {
    $url = file_get_contents($url);

    if (empty($url)) return 'blank.gif';

    $img = $this->slug . '.' . $this->getExtension($url);

    $this->removeImage($img);

    try {
      file_put_contents($this->image_directory . $img, $url);
    } catch (Exception $e) {
      throw new Exception('Could not save file (' . $img . ') to ' . $this->image_directory);
    }

    return $img;
  }

  private function getExtension($file) {
    $file_info = new \finfo(FILEINFO_MIME);
    $mime_type = $file_info->buffer($file);
    $mime_type = explode(';', $mime_type);

    switch ($mime_type[0]) {
      case 'image/jpeg':
        $extension = 'jpg';
        break;

      case 'image/png':
        $extension = 'png';
        break;

      case 'image/gif':
        $extension = 'gif';
        break;

      default:
        $extension = 'jpg';
        break;
    }

    return $extension;
  }

  private function removeImage($image) {
    unlink($this->image_directory . $image);
  }
}

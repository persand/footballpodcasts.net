<?php

Class YMLCreator {
  private $source;
  private $yml_directory;
  private $image_directory;
  private $fields;
  private $row;
  private $skip_rows = 1;
  private $handle;
  private $data;
  private $data_num;
  private $slug;
  private $content;
  private $log = array();

  function __construct($source, $yml_directory, $image_directory)
  {
    $this->source = $source;
    $this->yml_directory = $yml_directory;
    $this->image_directory = $image_directory;

    $this->fields = array();
    $this->fields[0] = 'title';
    $this->fields[1] = 'url';
    $this->fields[2] = 'feed';
    $this->fields[3] = 'itunes';
    $this->fields[4] = 'description';
    $this->fields[5] = 'language';
    $this->fields[6] = 'image';
  }

  public function getSource()
  {
    return $this->source;
  }

  public function setSource($source)
  {
    $this->source = $source;
  }

  public function getYMLDirectory()
  {
    return $this->yml_directory();
  }

  public function setYMLDirectory($directory)
  {
    $this->yml_directory = $directory;
  }

  public function getImageDirectory()
  {
    return $this->image_directory();
  }

  public function setImageDirectory($directory)
  {
    $this->image_directory = $directory;
  }

  public function setSkipRows($skip_rows)
  {
    $this->skip_rows = $skip_rows;
  }

  public function readSource()
  {
    $this->row = 1;

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
    foreach (new DirectoryIterator($this->yml_directory) as $file)
    if ($file->getExtension() == 'yml')
      unlink($this->yml_directory . $file->getFilename());
  }

  private function readData() {
    while (($this->data = fgetcsv($this->handle, 1000, ",")) !== FALSE) {
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
    $yml_file = fopen($yml_file, 'w') or die('Cannot open file:  '.$yml_file);
    fwrite($yml_file, $this->content);
    fclose($yml_file);

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
    $img = $this->slug . '.jpg';
    $url = file_get_contents($url);

    if (empty($url)) return false;

    $this->removeImage($img);

    try {
      file_put_contents($this->image_directory . $img, $url);
    } catch (Exception $e) {
      throw new Exception('Could not save file (' . $img . ') to ' . $this->image_directory);
    }

    return $img;
  }

  private function removeImage($image) {
    unlink($this->image_directory . $image);
  }
}

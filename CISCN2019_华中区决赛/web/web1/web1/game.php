<?php
include("liuliang.php");
// ini_set("display_errors", "On"); 
// error_reporting(E_ALL | E_STRICT);
class BlogLog {
  public $log_ = '/tmp/web_log';
  public $content = '[access] %s';

  public function __construct($data=null) {
    $temp = $this->init($data);
    $this->render($temp);
  }

  public function init($data) {
    // No, you can't control an object anymore!
    $format = '/O:\d:/';
    $flag = true;
    $flag = $flag && substr($data, 0, 2) !== 'O:';
    $flag = $flag && (!preg_match($format, $data));
    if(preg_match('/BlogLog/', $data)) $flag=false;
    if ($flag){
      	#return "1";
	return unserialize($data);
    }
    return [];
  }

  public function createLog($filename=null, $content=null) {
    if ($this->log_ != null)
      $filename = $this->log_;
    if ($this->content != null)
      $content = $this->content;
    file_put_contents($filename, $content);
  }

  public function render($k) {
    echo sprintf($this->content, $k['name']);
  }

  public function __destruct() {
    $this->createLog();
  }
}

$data = "";
if (isset($_GET['data'])){
  $data = $_GET['data'];
  new BlogLog($data);
}
else
  highlight_file(__FILE__);

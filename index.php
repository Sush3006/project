<?php


ini_set('display_errors', 'On');
error_reporting(E_ALL);

  class Manage {
   public static function autoload($class) {
     include $class . '.php';
   }
 }

spl_autoload_register(array('Manage', 'autoload'));


$obj = new main();


  class main {

   public function __construct(){
   $pageRequest = 'uploadform';
        
      if(isset($_REQUEST['page'])) {
         $pageRequest = $_REQUEST['page'];
        }
        
$page = new $pageRequest;
           if($_SERVER['REQUEST_METHOD'] == 'GET') {
             $page->get();
             } 
             else {
             $page->post();
             }

   }
  }

  abstract class page {
  protected $html;

   public function __construct(){
   $this->html .= '<html>';
   $this->html .= '<link rel="stylesheet" href="styles.css">';
   $this->html .= '<body>';
   }
   
   public function __destruct(){
   $this->html .= '</body></html>';
   print($this->html);
   }

   public function get() {
   echo 'default get message';
   }
   public function post() {
   print_r($_POST);
   }
  }

  class uploadform extends page{
   public function get(){
   $form = '<form action="index.php?page=uploadform" method="post"	enctype="multipart/form-data">';
   $form .= '<input type="file" name="fileToUpload" id="fileToUpload">';
   $form .= '<input type="submit" value="Upload Here" name="submit">';
   $form .= '</form> ';
   $this->html .= '<h1>Upload Form</h1>';
   $this->html .= $form;
  }

   public function post() {
   $target_dir = "uploads/";
   $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
   $uploadOk = 1;
   $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
      if ($uploadOk == 0) {
          echo "Sorry, your file was not uploaded.";
          }
          else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                header('Location:index.php?page=htmlTable&filename='.$target_file);
                echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                }
                else {
                echo "Sorry, there was an error uploading your file.";
                }
          }
   }
  }

  class htmlTable extends page {
   public function get(){
   $row = 1;
      if (($handle = fopen($_GET['filename'], "r")) !== FALSE) {
          echo '<table border="1">';
          while (($data = fgetcsv($handle)) !== FALSE) {
                 $num = count($data);
                 if ($row == 1) {
                     echo '<thead><tr>';
                 }
                 else{
                      echo '<tr>';
                 }
                 for ($a=0; $a < $num; $a++) {
                      if(!isset($data[$a])) {
                        $value = "&nbsp;";
                      }
                      else{
                           $value = $data[$a];
                      }
                      if ($row == 1) {
                          echo '<th>'.$value.'</th>';
                          }
                          else{
                               echo '<td>'.$value.'</td>';
                          }
                                    
                 }
                 if ($row == 1) {
                     echo '</tr></thead><tbody>';
                 }
                 else{
                      echo '</tr>';
                 }
                  $row++;
          }
          echo '</tbody></table>';
          fclose($handle);
      }
   }
 }
?>
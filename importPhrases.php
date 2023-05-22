<!DOCTYPE html>


<!--  WORKSHEET 10 - Form Navigation 
                     Using mySQL and PHP
     
      IMPORTANT:  Pre-Condition:  You must have an ID field in your 
                  table which is an integer primary key.  The 
                      navigation below DEPENDS on this ID field.

       Author: Dave Slemon
       Updated by Luca Quacquarelli, 2022-04-27
       
-->

<html lang="en">

<head>
     <style>
          * {
               box-sizing: border-box;
               font-size: 16px;

          }

          body {
               background-color: #435165;
          }
     </style>

     <!-- 
    Ask user for file upload containing data for langmatch DB
     -->

     <!-- TEXT INSERTION -->

     <form method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
          <label for="category">Category</label>
          <input type="text" id="data" name='category'>
          <input type="submit" name="submit" value="submit">
     </form>

</head>



<?php
session_start();

$version = "1.01";
$num = $_POST['num'] ?? "";

//if there is data in textfield and category has been set
if (isset($_POST) && isset($_POST['category'])) {
     $category = $_POST['category'];
     //read the file
     $data = file_get_contents($category . '.json');

     //make json string into array of php objects`
     $data = json_decode($data);
}

//key values
$host = "localhost";
$user = "root";
$password = "";
$dbname = "langmatch"; //Name of Your DB


//set data source name
$dsn = "mysql:host=" . $host . ";dbname=" . $dbname;


//create a PDO instance
$pdo = new PDO($dsn, $user, $password);
//Setting fetches to return an object
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

foreach ($data as $obj) {
     $eng = $obj->eng;
     $cay = $obj->cay;
     $moh = $obj->moh;
     $sql = "insert into phrases values(?, ?, ?, ?, ?);";
     $stmt = $pdo->prepare($sql);
     $stmt->execute([null, $eng, $cay, $moh, $category]);

}



?>

</html>
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
	</head>
	
	<body>
	
		<?php	
        session_start();
		
        $version = "1.01";
        $title = $_POST['title']??"";
        $num= $_POST['num']??"";
        $amount = $_POST['amount']??"";
        

        
        //key values
        $host = "localhost";
        $user = "root";
        $password = "";
        $dbname = "langmatch";     //Name of Your DB
                            
                            
        //set data source name
        $dsn = "mysql:host=".$host.";dbname=".$dbname;


        //create a PDO instance
        $pdo = new PDO($dsn, $user, $password);
        //Setting fetches to return an object
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);		

$data = file_get_contents("phrases.json");
$data = json_decode($data);
foreach($data as $obj){
     foreach($obj as $key=>$val){
        $stmt= $pdo->prepare("INSERT INTO $dbname ($key) VALUES ($val);");
				$stmt->execute();
				$result = $stmt->fetch();
     }
}


			
		?>
			
		
		
		
		
		
	</body>
	
</html>
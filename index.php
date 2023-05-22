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

			.container {
   				width: 400px;
    			background-color: #ffffff;
    			box-shadow: 0 0 9px 0 rgba(0, 0, 0, 0.3);
    			margin: 25px auto;
			}

			.container h2 {
    			text-align: center;
    			color: #5b6574;
    			font-size: 24px;
    			padding: 20px 0 20px 0;
    			border-bottom: 1px solid #dee0e4;
			}

			.container form {
    			display: flex;
   				flex-wrap: wrap;
   				justify-content: center;
    			padding-top: 20px;
			}

			.container form label {
    			display: flex;
    			justify-content: center;
    			align-items: center;
    			width: 70px;
    			height: 50px;
    			background-color: #3274d6;
    			color: #ffffff;
			}

			.container form input[type="text"] {
    			width: 310px;
    			height: 50px;
   				border: 1px solid #dee0e4;
    			margin-bottom: 20px;
   				padding: 0 15px;
			}

			#red {
				color: red
			}

			.container form input[type="image"] {
				display: flex;
    			width: 20%;
    			padding: 10px;
   				margin-top: 20px;
    			background-color: #3274d6;
    			border: 0;
    			cursor: pointer;
    			font-weight: bold;
    			color: #ffffff;
    			transition: background-color 0.2s;
				}

			.container form input[type="image"]:hover {
  				background-color: #2868c7;
    			transition: background-color 0.2s;
			}

			input[name=nextbutton] {
				background:url("img/B1.png");
				border: none;
				height: 67px;
				width: 67px;
				color: rgba(0,0,0,0);
			}

			input[name=previousbutton] {
				background:url("img/B2.png");
				border: none;
				height: 67px;
				width: 67px;
				color: rgba(0,0,0,0);
			}
			input[name=addbutton] {
				background:url("img/B5.png");
				border: none;
				height: 67px;
				width: 67px;
				color: rgba(0,0,0,0);
			}
			input[name=deletebutton] {
				background:url("img/B6.png");
				border: none;
				height: 67px;
				width: 67px;
				color: rgba(0,0,0,0);
			}
			input[name=savebutton] {
				background:url("img/B3.png");
				border: none;
				height: 67px;
				width: 67px;
				color: rgba(0,0,0,0);
			}


		</style>
	</head>
	
	<body>
	
		<?php
			//The $id variable is the only variable maintained within the
			//the session file
			session_start();
		
			$version = "1.01";
			$num= $_POST['num']??"";
			$eng = $_POST['eng']??"";
			$cay = $_POST['cay']??"";
			$moh = $_POST['moh']??"";
			$category = $_POST['category']??"";
			

			
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

/*			
			//These lines are useful for DEBUGGING
			$stmt = $pdo->prepare('SELECT * FROM pieces');
			$stmt->execute();
			$allrecs = $stmt->fetchAll();

			//Note: within the database, the ID field is called, b_id
			foreach ($allrecs as $x) {
				echo $x->num. "  " . $x->title  . " ". $x->amount . "<br>";
			}
*/			
			
		
			
			//if this is the first time you've started up the program
			if (empty($num)) 
			{
				$stmt= $pdo->prepare("select * from phrases order by num+0. limit 1");
				$stmt->execute();
				$result = $stmt->fetch();
				
				
				$num = $result->num;
				$_SESSION['num'] = $num;
				$eng = $result->eng;
				$cay = $result->cay;
				$moh = $result->moh;
				$category = $result->category;
				
			}
	
			//if the nextbutton is pressed
			else if (isset( $_POST['nextbutton'] ))
			{
				
				$sql = "select * from phrases where num > ? limit 1";
				$stmt= $pdo->prepare($sql);
				$stmt->execute([$num]);
				$result = $stmt->fetch();
				//a guard so that you don't go past the last record
				if (!empty($result))
				{
					$num = $result->num;
					$_SESSION['num'] = $num;
					$eng = $result->eng;
					$cay = $result->cay;
					$moh = $result->moh;
					$category = $result->category;
				}
			
			}

			//if the previous button is pressed
			else if (isset( $_POST['previousbutton'] ))
			{
				$sql = "select * from phrases where num < ? order by num+0 desc limit 1";
				$stmt= $pdo->prepare($sql);
				$stmt->execute([$num]);
				$result = $stmt->fetch();
				//a guard so that you don't go past the first record
				if (!empty($result))
				{
					$num = $result->num;
					$_SESSION['num'] = $num;
					$eng = $result->eng;
					$cay = $result->cay;
					$moh = $result->moh;
					$category = $result->category;
				}
			
			}

			//if the delete button is pressed
			else if (isset( $_POST['deletebutton'] ))
			{
				$sql = "delete from phrases where num = ? ";
				$stmt= $pdo->prepare($sql);
				$stmt->execute([$num]);
			
			}
			//if the save button is pressed
			else if (isset( $_POST['savebutton'] ))
			{
				$sql = "update phrases set eng = ?, cay = ?, moh = ?, category = ? where num = ? ";
				$stmt= $pdo->prepare($sql);
				$stmt->execute([$eng,$cay,$moh,$category,$num]);
			
			}						
						
			//if the add button is pressed
			else if (isset( $_POST['addbutton'] ))
			{
				$sql = "insert into phrases values(?, ?, ?, ?, ?)";
				$stmt= $pdo->prepare($sql);
				$stmt->execute([ $_POST['num'],$_POST['eng'], $_POST['cay'], $_POST['moh'], $_POST['category']]);
				
			}	
		
	
		?>
		
		

		<div class="container">
			<h2 class=hcenter>My Personal Collection <em class=smallfont><?= $version ?></em></h2>
		
		
			<form method="POST" action = "<?= $_SERVER['PHP_SELF']; ?>" name=regform>
				
				<label>num:</label>
				<input class= "text" type="text" name="num"  size="45" value="<?= $num ?>"><br><br>
				
				<label>eng:</label>
				<input class= "text" type="text" name="eng"  value="<?= $eng ?>"><br><br>
				
				<label>cay:</label>
				<input  class= "text" type="text" name="cay"  value="<?= $cay ?>"><br><br>

				<label>moh:</label>
				<input  class= "text" type="text" name="moh"  value="<?= $moh ?>"><br><br>

				<label>category:</label>
				<input  class= "text" type="text" name="category"  value="<?= $category ?>"><br><br>
				
				
				<!-- These buttons need to be changed to  type="image" -->
				<input type="submit"  name="previousbutton" value="Previous">
				<input type="submit"  name="deletebutton" value="Delete">
				<input type="submit"  name="savebutton" value="Save">
				<input type="submit"  name="addbutton" value="Add">
				<input type="submit"  name="nextbutton" value="Next">
		
				
		
			</form>
			<?php		
				echo "<h2 id='red'>current record: ".$num."</h2>";
			?>
		
		</div>
		
		
		
	</body>
	
</html>
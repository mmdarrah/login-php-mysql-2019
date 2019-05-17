<?php
  require 'partials/session.php'; 
  require 'partials/connect.php'; 
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    
    
  <title>Login</title>
</head>
<body>
  <?php
    
    if(isset($_GET['action'])){
        
      
      // If the user takes the action "register", register here
      if($_GET['action'] == 'register'  ) {

        if(empty($_POST['username']) || empty($_POST['password'])){
            header('Location: index.php');
        }
        else{

        // Create a statement for inserting user into database
        $statement = $pdo->prepare(
          "INSERT INTO users (username, password)
          VALUES (:username, :password)" // Using named placeholders here, easier to read
        );
        $statement->execute([
          ":username" => $_POST['username'], // Use username as a regular string
          ":password" => password_hash($_POST['password'], PASSWORD_BCRYPT) // Make a hashed password
        ]);
        // Tell the user that the new user was added
        echo "The user {$_POST['username']} was created.";

        }
        
      }


      // If the user takes the action "login", log the user in
      if($_GET['action'] == 'login') {
        $statement = $pdo->prepare("SELECT * FROM users WHERE username = :username"); 
        $statement->execute([
          ":username" => $_POST['username']
        ]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        
        
        if (password_verify($_POST['password'], $user["password"])) {
          // Set our session to "loggedIn"
          $_SESSION["loggedIn"] = true;
          $_SESSION['username'] = $user['username'];
          $_SESSION['userID'] = $user['userID'];
        } else {
          // Tell the user that the username and password was wrong
          echo "Wrong password.";
        }
      }

      // If the user takes the action "submit"
      if($_GET['action'] == 'submit' && ($_POST['title'] != "") && ($_POST['content'] != "")) {
        
       
        $query = 'INSERT INTO entries (title, content, createdAt, userID) 
        VALUES (:title, :content, NOW(), :userID)';
        $statement = $pdo->prepare($query);
        $statement->execute([
          ":title" =>   $_POST['title'], 
          ":content" => $_POST['content'],
          "userID" => $_SESSION['userID'],
          
        ]);
        
        echo "The title is {$_POST['title']} and the content are {$_POST['content']}";
        

          $query = "SELECT * FROM entries WHERE userID = {$_SESSION["userID"]}";
          $statement = $pdo->prepare($query); 
          $statement->execute();
          $db_data = $statement->fetchAll(PDO::FETCH_ASSOC); 
          ?>

          <table>
        <thead>
            <th>Title </th>
            <th> Content</th>
            <th> Created at</th>
            
            
           
          </thead>
          <tbody>

           <?php
            foreach ($db_data as $info) {
            ?>
                <tr>
                    <td><?= $info['title']?></td>
                    <td><?= $info['content']?></td>
                    <td><?= $info['createdAt'] ?></td>
                   <td><a href="../New/views/delete.php?id=<?= $info['entryID'] ?>">delete</a></td>  
                    
                </tr>
                <?php
            }
            ?>
            </tbody>
            </table>
            

            <?php

      }

       if($_GET['action'] == 'logout') {

        session_destroy();
        header('Location: index.php');
       }
    }


    // Check whether the user is logged in or not
    // Show different views depending on the users login status
    if(isset($_SESSION["loggedIn"])) {
      echo "<h4>Welcome {$_SESSION['username']} To your journal.</h4>";
      
      
      require 'views/greeting.php';

      
    }
    else {
      require 'views/login.php';
      require 'views/register.php';
    }    
    ?>
    
    
</body>
</html>
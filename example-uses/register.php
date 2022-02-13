<?php
  require_once '../chrono-crud.php';
  require_once 'database.php';

  const table_name = 'account';
  
  $user_data = @[
    'uid' => uniqid(),
    'name' => trim($_POST['name']),
    'email' => trim($_POST['email']),
    'password' => trim(password_hash($_POST['password'], PASSWORD_DEFAULT))
  ];

  if(isset($_POST['register'])):
    
    $statement = createQuery($db, table_name, $user_data);

    if(isExists($db, table_name, 'email', $user_data['email'])):
      echo "{$user_data['email']} is already exising in the database.";
      return false;
    endif;
      
    $isExecuted = execQuery($db, $statement);
    
    if($isExecuted ):
      echo "Successfully registered!";
    else: 
      echo "Operation failed.";
    endif;

  endif;

  closeConnection($db);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Example</title>
</head>

<body>
  <form action="" method="POST">
    <label for="name">Name</label><br>
    <input type="text" name="name" id="name"><br>

    <label for="email">Email</label><br>
    <input type="email" name="email" id="email"><br>

    <label for="password">Password</label><br>
    <input type="password" name="password" id="password"><br><br>

    <button type="submit" name="register">Register an Account</button>
  </form>
</body>

</html>
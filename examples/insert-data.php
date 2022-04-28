<?php
  include_once '../chrono-crud.php';
  include_once 'database.php';
  
  $response = null;
  $table_name = 'account';
  // Getting user data
  $user_data = [
    'user_id' => uniqid(),
    'username' => 'heychrono',
    'email' => 'coad@azoolpe.td',
    'password' => password_hash('pass123', PASSWORD_DEFAULT)
  ];
  
  // Checking if email is already existed in the database.
  $is_email_existing = isExists($database, $table_name, 'email', $user_data['email']);
  
  if(!$is_email_existing) {
    // Creating insert query.
    $insert_query = createQuery($database, $table_name, $user_data);
    // Execute query function.
    $is_executed = execQuery($database, $insert_query);

    // Execute validation.
    if($is_executed)  {
      echo "Data insert success.";
    } else {
      echo "Data insert failed.";
    }
  } else {
    $response = "\"{$user_data['email']}\" is already on the database.";
  }
  
  echo $response ?? null;

  closeConnection($database);
?>
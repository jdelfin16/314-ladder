<?php
  // Checks for minor errors for each function

  /*$USER = "username";
  $NAME = "name";
  $PASS = "password";
  $PHONE = "phone";
  $EMAIL = "email";
  $RANK = "rank";

  $USERNAME = [$USER];
  $ALL = [$USER, $NAME, $PASS, $PHONE, $EMAIL];
  */
  function missing_error($vars, $array)
  {
    // If there are no variables (i.e., if $vars is empty)...
    // then provide a warning...
    if (!$vars)
    {
      echo "PLEASE enter some data!";
    }

    // If the variable is in the array...
    if (in_array($vars, $array))
    {
      echo "All set!";
    }
    else
    {
      echo "$vars is missing...";
    }

    // If the variable is a username and is not in the array...
    // then provide a warning...
    if ($vars = $USER && in_array($vars, $array))
    {
      echo "$vars exists!";
    }
    else
    {
      echo "ERROR - you are missing $vars";
    }
    // If the variable is an email, is in the array, and
    // does include an '@' symbol...
    if ($vars = $EMAIL && in_array($vars, $array) && preg_match('/@/', $vars))
    {
      echo "$vars is a valid email!";
    }
    else
    {
      echo "$vars is an invalid email!";
    }

    // If the variable is a phone number,
    // is an the array, and is 10 digits long...
    if ($vars = $PHONE && in_array($vars, $array) &&
     preg_match('/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/', $vars))
    {
      echo "$vars is a valid phone number!";
    }
    else
    {
      echo "$vars is not a valid phone number!";
    }
  }

?>

<?php
  include "../database.php";
  include "../rest.php";

  $request = new RestRequest();
  $method = $request->getRequestType();
  $request_vars = $request->getRequestVariables();

  $response = $request_vars;
  $response["service"] = "player";
  $response["method"] = $method;

  // Arrays for valid_keys() function - based on methods
  $POST_CHECK = array("name", "email", "phone", "username", "password");
  $GET_CHECK = array("username");
  $UPDATE_CHECK = array("username", "name", "email", "rank", "phone");
  $DELETE_CHECK = array("username");

  // Values
  $name = $response["name"];
  $email = $response["email"];
  $phone = $response["phone"];
  $username = $response["username"];
  $password = $response["password"];
  $rank = $response["rank"];

  // Function validating the phone number
  function validate_phone($phone)
  {
    if (preg_match('/^[0-9]{3}[0-9]{3}[0-9]{4}$/',$phone, $matches) == 1)
    {
      return true;
    }
    else
    {
      return false;
    }
  }
  // echo json_encode(validate_phone($phone));

  // Function validating the email
  function validate_email($email)
  {
    if (filter_var($email, FILTER_VALIDATE_EMAIL))
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  // Validating keys - using $response value, $response array, and constant arrays set prior
    // Checking if the key is in the given parameters
  function valid_keys($value, $response_array, $constant_array)
  {
    // Enter the parameter and acquire the key - FALSE if empty
    if (empty($value))
    {
      return false;
    }
    else
    {
      $key = array_search($value, $response_array);
      if (in_array($key, $constant_array))
      {
        return true;
      }
      else
      {
        return false;
      }
    }
  }

  /*
    Function counting the number of parameters needed for specific methods
    - CREATE needs 5 parameters
    - GET and DELETE needs 1 parameter
    - UPDATE needs 4 parameters
  */
  function verify_parameters($method, $response_array)
  {

    // Counting number of parameters
    $num_of_param = count($response_array);

    // If the method is for INSERT...
    if ($method == "POST" && $num_of_param == 5)
    {
      return true;
    }

    // Else, if the method is for SELECT...
    else if ($method == "GET" && $num_of_param == 1)
    {
      return true;
    }

    // Else, if the method is for UPDATE...
    else if ($method == "PUT" && $num_of_param == 4)
    {
      return true;
    }

    // Else, if the method is for DELETE...
    else if ($method == "DELETE" && $num_of_param == 1)
    {
      return true;
    }

    // Else...
    else
    {
      return false;
    }
  }

?>

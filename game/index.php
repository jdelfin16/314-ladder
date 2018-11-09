<?php

        include "../database.php";
      	include "../rest.php";
      	include "insert.php";
      	include "select.php";
      	include "delete.php";

        $request = new RestRequest();
        $method = $request->getRequestType();
        $request_vars = $request->getRequestVariables();

        $response = $request_vars;
        $response["service"] = "game";
        $response["method"] = $method;

        // D - Delete the Player
      	if ($request->isDelete())
      	{
          echo "This is delete!";
      	}

      	// A - Add the player
      	else if ($request->isPost())
      	{
          echo "This is post!";
      	}

      	// V - View the Player
      	else if ($request->isGet())
      	{
          echo "This is get!";

      	}

        /*
        ======== PSEUDOCODE!!! ====================
        Create constants to check for:
          - Username
          - Winner
          - Loser
          - Date of Game played
          - Winner's Score
          - Loser's Score

        REMEMBER: Add try/catch in functions to catch PHP errors...

        Create global variables/arrays for the functions:
          - Create --> [Winner, Loser, played, Winner's Score, Loser's Score]
          - Delete --> [Winner, Loser, played]
          - Select --> [Username, played]

        Receive inputs from the user...
        === Q: What does this look like? ===
        ===   $_POST? $_GET? $_DELETE?   ===
        === Check the $response variable  ===

        === For if-elseif statements: ===
        - Need to understand rest.php functions...

        If the user wants to create a new game:
          Check for missing errors by comparing the inputs and Create
          Run the insert function

        Else, if the user wants to select a game:
          Check for missing errors by comparing the inputs and Select
          Run the select function

        Else, if the user wants to delete a game:
          Check for missing errors by comparing the inputs and Delete
          Run the delete function

        */
?>

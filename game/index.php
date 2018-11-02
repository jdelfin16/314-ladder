<?php

        include "../rest.php";
        $request = new RestRequest();
        $method = $request->getRequestType();
        $request_vars = $request->getRequestVariables();

        $response = $request_vars;
        $response["service"] = "game";
        $response["method"] = $method;

        /*
        ======== PSEUDOCODE!!! ====================
        Include the other PHP/Function files (CRD)

        Create constants to check for:
          - Username
          - Winner
          - Loser
          - Game # (number)
          - Date of Game played
          - Winner's Score
          - Loser's Score
          -

        Create global variables/arrays for the functions:
          - Create --> [Winner, Loser, played, Winner's Score, Loser's Score]
          - Delete --> [Winner, Loser, number, played]
          - Select --> [Username, played]

        Receive inputs from the user...
        === Q: What does this look like? ===
        ===   $_POST? $_GET? $_DELETE?   ===
        === Check the $request variable  ===

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

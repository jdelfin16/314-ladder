<?php

        include "../rest.php";
        $request = new RestRequest();
        $method = $request->getRequestType();
        $request_vars = $request->getRequestVariables();

        $response = $request_vars;
        $response["service"] = "game";
        $response["method"] = $method;

        $echo json_encode($response);
?>


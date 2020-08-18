<?php

$dbhost = "";
$dbuser = "";
$dbname = "";
$dbpass = "";


try {
    $db = new PDO("pgsql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
} catch (\PDOException $e) {
    echo "error: $e";
}

$create = $db->prepare(" CREATE TABLE IF NOT EXISTS cookieusers (id Serial,username character varying(25) NOT NULL,password character varying(255) NOT NULL,cookies bigint NOT NULL,CONSTRAINT cookieusers_pkey PRIMARY KEY (id),CONSTRAINT cookieusers_unique UNIQUE (username))");
$create->execute();

$res = $db->query('SELECT * FROM cookieusers');

$all = $res->fetchall();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"])) {
        echo "Name required";
        exit();
    } else {
        $username = $_POST["username"];    
    }

    if (empty($_POST["password"])) {
        echo "password required";
        exit();
    } else {
        $password = hash('sha256', $_POST['password']);
    }
    

    if (isset($username,$password)) {

        $res = $db ->prepare("INSERT INTO cookieusers (username, password, cookies) VALUES ('$username','$password', 0)");

        try {
            $res->execute();
        } catch (\PDOException $e) {
            echo "error: $e";
        }
        
        $errormssg = $res->errorInfo();

        if (isset($errormssg[2])) {
            if ($errormssg[1] == 7) {
                if ($errormssg[0] == 23505) {
                    echo "<br>";
                    echo "Error: ";
                    echo "Username in use";
                    exit();
                }

            }

        } else {

            echo "Registration Successful";
            echo '<style type="text/css">
                .bruh {
                    visibility: visible;
                }
            </style>';
            
        }
    }
    
}


?>

<html>
    <head>
        <script>
        
            function login() {
                location.href = '/games/cookie/login.php';
            }
            
        </script>
    </head>
    <body>

        <form action="register.php" method="post">
            Username <input type="text" name="username"><br>
            Password <input type="text" name="password"><br>
            <input type='submit' name='submit' value='Submit'>
        </form >
        <button onclick="login()">log in</button>
    </body>
</html>
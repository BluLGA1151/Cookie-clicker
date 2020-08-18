<?php

if (isset($_COOKIE['username'])) {
    echo "";
}else{
    echo "
    <style>
    .bruh {
        visibility: hidden;
    }
</style>
    
    ";
}

$dbhost = "localhost";
$dbuser = "blutonium";
$dbname = "blutopia";
$dbpass = "train";
$dbcharset = "utf8mb4";

try {
    $db = new PDO("pgsql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
} catch (\PDOException $e) {
    echo "error: $e";
}

$create = $db->prepare(" CREATE TABLE IF NOT EXISTS cookieusers (id Serial,username character varying(25) NOT NULL,password character varying(255) NOT NULL,cookies bigint NOT NULL,CONSTRAINT cookieusers_pkey PRIMARY KEY (id),CONSTRAINT cookieusers_unique UNIQUE (username))");
$create->execute();

$res = $db->query('SELECT * FROM cookieusers');

$all = $res->fetchall();



if (isset($_COOKIE['password']) && isset($_COOKIE['username'])) {

    $userexist = false;

    foreach ($all as $user) {
        if ($user['username'] == $_COOKIE['username']) {
            $userexist = true;
        }
    }

    if ($userexist == false) {
        setcookie('username', "", time() - 3600);
        setcookie('password', "", time() - 3600);
        echo "cookie found but user does not exist! Deleted cookie.";

    } else {

        echo "Logged in as: ";
        echo $_COOKIE['username'];
    }


} else {
    echo "Please log in";
}

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


    if (isset($password,$username)) {

        foreach ($all as $user) {
            if ($user['username'] == $username && $user['password'] == $password) {
                echo "<br>";
                echo "login Successful!";
                $cookie_name = "username";
                $cookie_value = $username;
                
                $cookie2_name = "password";
                $cookie2_value = $password;

                setcookie($cookie_name, $cookie_value, time()+86400, "/games/cookie");
                setcookie($cookie2_name, $cookie2_value, time()+86400, "/games/cookie");
            
            }
        }


    }

}

?>

<html>
    <head>
        <script>
                        
            function setCookie(cname, cvalue, exdays) {
                var d = new Date();
                d.setTime(d.getTime() + (exdays*24*60*60*1000));
                var expires = "expires="+ d.toUTCString();
                document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/games/cookie";
            }

            function logout() {
                setCookie('username', '', -1)
                setCookie('password', '', -1)
                location.href='/games/cookie/login.php';
            }

            function register() {
                location.href = '/games/cookie/register.php'
            }

            function entergame() {
                location.href = '/games/cookie'
            }
            
        </script>
    </head>
    <body>
        <form action="login.php" method="post">
            Username <input type="text" name="username"><br>
            Password <input type="text" name="password"><br>
            <input type='submit' name='submit' value='Submit'>
        </form >
        <button class='bruh' onclick="logout()">Log out</button>
        <button onclick="register()">Register</button>
        <button onclick="entergame()">Enter game</button>
    </body>
</html>
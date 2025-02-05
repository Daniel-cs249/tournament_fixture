<?php

if(isset ($_POST['submit']))
{
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "tournament";
    $conn = new mysqli($host, $user, $pass, $db, 3307);
    if($conn->connect_error)
    {
        die("failed to connect db".$conn->connect_error);
    }
    $username=$_POST['username'];
    $password=$_POST['password'];
    $query="select password from create_tournament where court_name='$username'";
    $result=$conn->query($query);
    if($result->num_rows>0)
    {
        $row=$result->fetch_assoc();
        if($password==$row['password'])
        {
            header("location: poll.php");
            exit();
        }
        else{
            echo "<script>alert('incorrect password'); window.location.href='login.php';</script>";
            exit();
        }
    }
    else{
        echo "<script>alert('invalid username ');window.location.href='login.php';</script>";
        exit();
    }
    $conn->close();
}
?>

<html>
            <head>
                <link rel="stylesheet" href="style.css">
                <title>login</title>
            </head>
            <body id="test">
    <form action="" method="post">
                <div class="body">

                <fieldset id ="login">
                    <h1> login</h1>
                    <div class="input-group">
                    <label for="username">Username :</label>
                    <input type="username" id="username" name="username" placeholder="Your Username" autocomplete="off" required>
                    <i class='bx bx-user'></i>
                    <br><br>
                    </div>
                    <div class="input-group">
                    <label for="password">Password :</label>
                    <input type="password" id="password" name="password" placeholder="Password" autocomplete="off" required><br><br>
                    </div>
                    <input type="submit" value="Submit" id="lsubmit" name="submit">
                    
                    
                </fieldset>
            
        </div>
    </form>
    </body>
        </html>



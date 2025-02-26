<?php
    session_start();
    $host = "localhost"; 
    $user = "root";
    $pass = "";
    $db = "tournament";
    $conn = new mysqli($host, $user, $pass, $db, 3307);

    if ($conn->connect_error) {
        die("Failed to connect to DB: " . $conn->connect_error);
    }

    // Fetch courts and store them in session
    $_SESSION['court']=[];
    $fetch_tteams = "SELECT court_name FROM create_tournament";
    $tteams = $conn->query($fetch_tteams);
        while ($rows = $tteams->fetch_assoc()) {
            $_SESSION['court'][] = $rows['court_name'];
        }
    

    // Store selected court in session
    if (isset($_POST['tcourt'])) {
        $_SESSION['scourt'] = $_POST['tcourt'];
    }

    if (isset($_POST['submit'])) {
        $name_1 = $_POST['p1name'];
        $name_2 = $_POST['p2name'];
        $gender_1 = $_POST['gender1'];
        $gender_2 = $_POST['gender2'];
        $homecourt = $_POST['homecourt'];
        $age_1 = $_POST['p1age'];
        $age_2 = $_POST['p2age'];

        $selectedType = isset($_POST['typen']) ? ($_POST['typen'] === 'feather' ? "Feather" : "Nylon") : null;
        $selectedPaidStatus = isset($_POST['paid']) ? ($_POST['paid'] === 'paid' ? "Paid" : "Not-Paid") : null;
        
        // Ensure a valid tournament court is selected

        $insert = "INSERT INTO players (player_1, player_2, age_1, age_2, gender_1, gender_2, shuttle_type, homecourt, tcourt, payment) 
                VALUES ('$name_1','$name_2','$age_1','$age_2','$gender_1','$gender_2','$selectedType','$homecourt','{$_SESSION['scourt']}','$selectedPaidStatus')";
        
        if ($conn->query($insert) === TRUE) {
            echo "<script>alert('Data successfully submitted!');</script>";
        } else {
            echo "Error: " . $conn->error;
        }
    }
    ?>

    <!DOCTYPE html>
    <html>
    <head>
    
        <title>Registration</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body id="namentrybody">

        <form method="post">
            <div id="homecourt">
                <h3 style="color: antiquewhite">Tournament Court:</h3>
                <select name="tcourt" id="tcourt">
                    <option value="">-- Select Court --</option>
                    <?php
                    
                        foreach ($_SESSION['court'] as $value) {
                            echo "<option value='$value'>$value</option>";
                        }
                    
                    ?>
                </select>
                <input type="submit" name="tcselect" value="a">
            </div>
        </form>

        <form action="" method="post">
            <fieldset id="namentryfieldset">
                <div id="playerheading">
                    <h2 id="playerheading1">Player 1</h2>
                    <h2 id="playerheading2">Player 2</h2>
                </div>

                <div id="name">
                    <h3 style="color: antiquewhite">Name:</h3>
                    <input type="text" name="p1name" required placeholder="Player 1 Name">
                    <input type="text" name="p2name" required placeholder="Player 2 Name">
                </div>

                <div id="homecourt">
                    <h3 style="color: antiquewhite">Home Court:</h3>
                    <input type="text" name="homecourt" required placeholder="Home Court">
                </div>

                <div id="age">
                    <h3 style="color: antiquewhite">Age:</h3>
                    <input type="text" name="p1age" required placeholder="Player 1 Age">
                    <input type="text" name="p2age" required placeholder="Player 2 Age">
                </div>

                <div id="gender">
                    <h3 style="color: antiquewhite">Gender:</h3>
                    <select name="gender1" required>
                        <option value="" disabled selected>Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                    <select name="gender2" required>
                        <option value="" disabled selected>Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>

                <div id="shuttletype">
                    <h3 style="color: antiquewhite">Shuttle Type:</h3>
                    <input type="radio" name="typen" value="feather" required> Feather
                    <input type="radio" name="typen" value="nylon" required> Nylon
                </div>

                <div id="payment">
                    <h3 style="color: antiquewhite">Payment:</h3>
                    <input type="radio" name="paid" value="paid" required> Paid
                    <input type="radio" name="paid" value="not_paid" required> Not Paid
                </div>

                <div id="twobuttons">
                    <input type="submit" name="submit" value="Submit">
                    <a href="http://localhost:3000/view_team.php"><input type="button" value="View Teams"></a>
                </div>
            </fieldset>
        </form>

    </body>
    <?php $conn->close(); ?>
    </html>

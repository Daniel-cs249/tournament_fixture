<?php
session_start();
    $host = "localhost";    
    $user = "root";
    $pass = "";
    $db = "tournament";
    $port = 3307;

    $conn = new mysqli($host, $user, $pass, $db, $port);

    if ($conn->connect_error) {
        die("Failed to connect to database: " . $conn->connect_error);
    }
    
?>

<html>
<head>
    <title>Poll</title>
    <style>
        input
        {
            border-radius: 10px;
            border: 1px solid rgba(182, 141, 216, 0.9);
            background-color: aliceblue;
            font-weight: bolder;
        }
        body {
            background-image: url(login3.jpg);
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
        }
        #table-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px; /* Spacing between tables */
            margin: 20px 0;
        }
        table {
            border-collapse: collapse;
            width: 300px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: rgba(255, 255, 255, 0.5);
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: rgba(182, 141, 216, 0.9);
        }
        tr:nth-child(odd) {
            background-color: rgba(99, 141, 216, 0.9);
        }
        #fixtures_a
        {
        text-decoration:none;
        list-style: none;
        font-weight: bolder;
        color: blanchedalmond;
        }
        h2{
            color: rgb(212, 233, 142);
        }
    </style>
</head>
<body>
    
    <form method="post" action="">

        <?php 
            echo"<h2>Court :</h2>";
            echo"<h3 style='color: yellow;'>{$_SESSION['username']}</h3>";
            echo"<h2>Total number of teams </h2>";
            echo "<input type='submit' name='totteams' value='check total teams'>";
        ?>
    </form>
    <?php
    if(isset($_POST['totteams']))
    {
       
        $query = "SELECT COUNT(*) AS total_teams FROM players WHERE tcourt = '{$_SESSION['username']}'";
        $result = $conn->query($query);
        if ($result && $row = $result->fetch_assoc()) {
            $_SESSION['totalTeams'] = $row['total_teams'];
            echo "<h3 style='color : yellow'>Total teams: {$_SESSION['totalTeams']}</h3>";
        }
    }
    ?>
        <form method="post" action="">
        
        <h2>Number of teams in each poll:</h2>
        <input type="number" name="limit" min="2" required>
        <h2>No.of teams to be qualified in each poll</h2>
        <input type="number" name="top" min="1" required>
        <input type="submit" name="generate" value="Generate">
        
        </form>
        <form action='' method="POST">
            <input type='submit' name ='view_fixtures' value='view_fixtures'>
        </form>
        
    <?php
    

    if (isset($_POST['generate'])) 
    {
        $limit = $_POST['limit'];
        $_SESSION['top']=$_POST['top'];
        $query0="update create_tournament set top='{$_SESSION['top']}' where court_name ='{$_SESSION['username']}'";
        $ex=$conn->query($query0);
        
        // Get total teams
        $query = "SELECT COUNT(*) AS total_teams FROM players WHERE tcourt = '{$_SESSION['username']}'";
        $result = $conn->query($query);

        
        echo "<h3 style='color: yellow;'>Court: {$_SESSION['username']}</h3>";


            if ( $_SESSION['totalTeams'] > 0) {
                
                $select = "SELECT player_1 FROM players WHERE tcourt = '{$_SESSION['username']}'";                   // Fetch players
                $result = $conn->query($select);

                $poll = 1;
                $teamCount = 1;
                $i = 0;

                echo "<div id='table-container'>";

                while ($row = $result->fetch_assoc()) {
                    
                    if ($i % $limit == 0) {
                        if ($i > 0) echo "</table>";
                        echo "<table>";
                        echo "<tr><th colspan='2'><b>Poll $poll</b></th></tr>";
                        echo "<tr><th colspan='2'>Teams</th></tr>";
                        $poll++;
                        $teamCount = 1;
                    }
                    $poll=$poll-1;
                    echo "<tr><td>$teamCount</td><td>" . $row['player_1'] . "</td></tr>";
                    $update="update players set poll ='$poll' where player_1='{$row['player_1']}' ";
                    $results=$conn->query($update);
                    $poll=$poll+1;
                    $teamCount++;
                    $i++;
                }

                echo "</table>"; 
                echo "</div>";

            } else {
                echo "<h3>No teams found for the court: {$_SESSION['username']}</h3>";
            }
        } 

        if(isset($_POST['view_fixtures']))
        {
            header('location:Points_Upload.php');
        }

        $conn->close();
    
    ?>
</body>
</html>

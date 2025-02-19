<?php
    $host = "localhost";    
    $user = "root";
    $pass = "";
    $db = "tournament";
    $port = 3307;

    $conn = new mysqli($host, $user, $pass, $db, $port);

    if ($conn->connect_error) {
        die("Failed to connect to database: " . $conn->connect_error);
    }
    //getting the t_court names of the tournament
    $query_total_tournament="SELECT court_name from create_tournament";
        $query_total_tournament_result=$conn->query($query_total_tournament);
        if($query_total_tournament_result)
        {
            $t_count=[];
            while($arr = $query_total_tournament_result->fetch_assoc())
            {
                
                $t_count[]=$arr['court_name'];                                                       //array stores the t_court name
            }
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
            align-items: center;
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
    </style>
</head>
<body>
    
    <form method="post" action="">

        <?php 
            echo "<h2>select the court</h2>";
            echo "<select name ='courtname'>";
                foreach($t_count as $court)
                {
                    echo "<option value='$court'>$court</option>";
                }
            echo "</select>";
        ?>
        <h2>Number of teams in each poll:</h2>
        <input type="number" name="limit" min="1" required>
        <h2>No.of teams to be qualified in each poll</h2>
        <input type="number" name="top" min="1" required>
        <input type="submit" name="submit" value="Generate">
        <a href="test.php" id="fixtures_a" >  
        View-fixtures
        </a>
    </form>

    <?php
    

    if (isset($_POST['submit'])) {
        $courtname = $_POST['courtname'];
        $limit = $_POST['limit'];
        $top=$_POST['top'];
        $query0="update create_tournament set top='$top' where court_name ='$courtname'";
        $ex=$conn->query($query0);
        
        // Get total teams
        $query = "SELECT COUNT(*) AS total_teams FROM players WHERE tcourt = '$courtname'";
        $result = $conn->query($query);

        if ($result && $row = $result->fetch_assoc()) {
            $totalTeams = $row['total_teams'];
            echo "<h3>Total teams: $totalTeams</h3>";
            echo "<h3>Court : $courtname</h3>";

            if ($totalTeams > 0) {
                
                $select = "SELECT player_1 FROM players WHERE tcourt = '$courtname'";                   // Fetch players
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
                echo "<h3>No teams found for the court: $courtname</h3>";
            }
        } else {
            echo "<h3>Error fetching total teams.</h3>";
        }

        $conn->close();
    }
    ?>
</body>
</html>

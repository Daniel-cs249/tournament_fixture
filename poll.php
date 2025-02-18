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
        <h2>Select the court :</h2>
        <select name="courtname">                               //complete 
            <option value=></option>
        </select>

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
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "tournament";
    $port = 3307;

    $conn = new mysqli($host, $user, $pass, $db, $port);

    if ($conn->connect_error) {
        die("Failed to connect to database: " . $conn->connect_error);
    }

    if (isset($_POST['submit'])) {
        $courtname = $_POST['courtname'];
        $limit = $_POST['limit'];
        $top=$_POST['top'];
        $query0="update create_tournament set top='$top' where court_name ='$courtname'";
        $ex=$conn->query($query0);
        // Get total teams
        $query = "SELECT COUNT(*) AS total_teams FROM players WHERE tcourt = '$courtname'";
        $result = $conn->query($query);
        //getting the team which are registered in tournament for the dropdown list
        $reg_teams="select court_name from create_tournament";
        $reg_teams_view=$conn->query($reg_teams);
        if($reg_teams_view && $dropdown_arr=$reg_teams_view->fetch_assoc())
        {
            $reg_teams=$dropdown_arr['court_name']; //all the t_court names are stored not_checked

        }
        if ($result && $row = $result->fetch_assoc()) {
            $totalTeams = $row['total_teams'];
            echo "<h3>Total teams: $totalTeams</h3>";
            echo "<h3>Court : $courtname</h3>";

            if ($totalTeams > 0) {
                // Fetch players
                $select = "SELECT player_1 FROM players WHERE tcourt = '$courtname'";
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

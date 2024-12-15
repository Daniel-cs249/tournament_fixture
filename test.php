<!DOCTYPE html>
<html>
<head>
    <style>
        input {
            border-radius: 5px;
            border: 1px solid rgba(182, 141, 216, 0.9);
            background-color: aliceblue;
            font-weight: bolder;
            width: 40px;
        }
        body {
            background-image: url(login3.jpg);
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        #next, #submit {
            width: 55px;
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
    </style>
    <title>Fixtures</title>
</head>
<body>
<?php
$conn = new mysqli('localhost', 'root', 'abc123', 'tournament', 3307);
if ($conn->connect_error) {
    die("Connection failure: " . $conn->connect_error);
}

echo "<form action='' method='post'>";

$pollQuery = "SELECT DISTINCT(poll) FROM players";
$pollResult = $conn->query($pollQuery);

$pollNumber = 1;
while ($pollRow = $pollResult->fetch_assoc()) {
    $poll = $pollRow['poll'];
    $playerQuery = "SELECT player_1 FROM players WHERE poll = '$poll' AND tcourt = 'pegasus'";
    $playerResult = $conn->query($playerQuery);

    $players = [];
    while ($playerRow = $playerResult->fetch_assoc()) {
        $players[] = $playerRow['player_1'];
    }

    echo "<table id='table-container'>";
    echo "<tr><th colspan='5'>Poll: $pollNumber</th></tr>";

    if (count($players) == 1) {
        echo "<tr><td colspan='5'>{$players[0]}</td></tr>";
    } else {
        for ($i = 0; $i < count($players); $i++) {
            for ($j = $i + 1; $j < count($players); $j++) {
                $player1 = $players[$i];
                $player2 = $players[$j];
                echo "<tr>
                        <td>$player1</td>
                        <td><input type='number' name='scores[$player1][$player2][score1]'></td>
                        <td>Vs</td>
                        <td>$player2</td>
                        <td><input type='number' name='scores[$player1][$player2][score2]'></td>
                    </tr>";
            }
        }
    }

    echo "</table>";
    $pollNumber++;
}

echo "<input type='submit' name='submit' id='submit' value='Submit All'>";
echo "</form>";

if (isset($_POST['submit'])) {
    if (isset($_POST['scores'])) {
        foreach ($_POST['scores'] as $player1 => $matches) {
            foreach ($matches as $player2 => $scores) {
                $score1 = (int)$scores['score1'];
                $score2 = (int)$scores['score2'];

                $updatePlayer1 = "UPDATE players SET points = points + $score1 WHERE player_1 = '$player1'";
                $updatePlayer2 = "UPDATE players SET points = points + $score2 WHERE player_1 = '$player2'";

                $conn->query($updatePlayer1);
                $conn->query($updatePlayer2);
            }
        }
        echo "<p>Scores updated successfully!</p>";
        header('location: fixtures2.php');
    } else {
        echo "<p>No scores submitted.</p>";
    }
}

$conn->close();
?>
</body>
</html>

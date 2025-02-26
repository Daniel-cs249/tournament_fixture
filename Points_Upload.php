<?php
ob_start(); // Prevents "headers already sent" errors
session_start();
$conn = new mysqli('localhost', 'root', '', 'tournament', 3307);
if ($conn->connect_error) {
    die("Connection failure: " . $conn->connect_error);
}

// Handle form submission BEFORE any HTML output
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

        $top = $_SESSION['top'];
        if ($top == 1) {
            header('Location: final_result.php'); // Corrected redirect
            exit();
        } elseif ($top == 2) {
            header('Location: top2.php'); // Corrected redirect
            exit();
        } elseif ($top == 3) {
            $_SESSION['message'] = "TOP 3 CAN BE GENERATED LATER."; 
        }
    } else {
        $_SESSION['message'] = "No scores submitted.";
    }
}

// HTML output starts after logic
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            background-image: url('login3.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: Arial, sans-serif;
            color: white;
        }

        #table-container {
            width: 90%;
            max-width: 800px;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            border-radius: 8px;
            overflow: hidden;
            background-color: rgba(255, 255, 255, 0.1);
        }

        th, td {
            padding: 12px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }

        th {
            background-color: rgba(255, 255, 255, 0.3);
            font-size: 18px;
        }

        tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.2);
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.4);
            transition: 0.3s;
        }

        input[type="number"] {
            width: 50px;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            text-align: center;
        }

        input[type="number"]:focus {
            background-color: rgba(255, 255, 255, 0.3);
            outline: none;
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.6);
        }

        #submit {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            transition: 0.3s;
        }

        #submit:hover {
            background-color: #45a049;
        }
    </style>
    <title>Fixtures</title>
</head>
<body>

    <?php
    if (isset($_SESSION['message'])) {
        echo "<p>{$_SESSION['message']}</p>";
        unset($_SESSION['message']); // Remove message after displaying
    }
    ?>

    <form action="" method="post">
        <?php
        $pollQuery = "SELECT DISTINCT(poll) FROM players where tcourt='{$_SESSION['username']}'";
        $pollResult = $conn->query($pollQuery);

        $pollNumber = 1;
        while ($pollRow = $pollResult->fetch_assoc()) {
            $poll = $pollRow['poll'];
            $playerQuery = "SELECT player_1 FROM players WHERE poll = '$poll' AND tcourt = '{$_SESSION['username']}'";
            $playerResult = $conn->query($playerQuery);

            $players = [];
            while ($playerRow = $playerResult->fetch_assoc()) {
                $players[] = $playerRow['player_1'];
            }

            echo "<div id='table-container'><table>";
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
                                <td><input type='number' name='scores[$player1][$player2][score1]' required></td>
                                <td>Vs</td>
                                <td>$player2</td>
                                <td><input type='number' name='scores[$player1][$player2][score2]' required></td>
                            </tr>";
                    }
                }
            }
            echo "</table></div>";
            $pollNumber++;
        }
        ?>
        <input type="submit" name="submit" id="submit" value="Submit All">
    </form>

</body>
</html>

<?php
$conn->close();
ob_end_flush(); // Flush output buffer
?>

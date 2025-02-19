<html>
    <head>
        <title>Standings</title>
        <style>
            /* Add general body styling */
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
                background-color: #f4f4f9;
                background-image: url(login3.jpg);
            }

            /* Styling the form container */
            form {
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                max-width: 800px;
                margin: 0 auto;
            }

            /* Styling the table */
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            table th, table td {
                padding: 10px;
                text-align: center;
                border: 1px solid #ddd;
            }

            table th {
                background-color: #4CAF50;
                color: white;
            }

            table tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            table tr:hover {
                background-color: #ddd;
            }

            /* Style the input fields */
            input[type="number"] {
                width: 60px;
                padding: 5px;
                text-align: center;
                border-radius: 4px;
                border: 1px solid #ccc;
            }

            /* Style the submit button */
            input[type="submit"] {
                background-color: #4CAF50;
                color: white;
                border: none;
                padding: 10px 20px;
                text-transform: uppercase;
                font-size: 16px;
                border-radius: 5px;
                cursor: pointer;
                margin-top: 20px;
                display: block;
                width: 100%;
            }

            input[type="submit"]:hover {
                background-color: #45a049;
            }

            /* Style the winner announcement */
            p {
                font-size: 18px;
                font-weight: bold;
                color: #333;
                text-align: center;
            }
        </style>
       
    </head>
    <body>
        <form action="" method="post">
            <table border="1">
                <?php
                session_start();    
                // Connect to the database
                $conn = new mysqli('localhost', 'root', '', 'tournament', '3307');
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch distinct polls for the specified court
                $distinctpoll = "SELECT DISTINCT(poll) AS poll FROM players WHERE tcourt='venba'";
                $selected = $conn->query($distinctpoll);

                // Start table with header
                echo "<tr><th colspan=5>Standings</th></tr>";
                $arr = [];
                $count = 0;

                while ($row = $selected->fetch_assoc()) {
                    $dpoll = $row['poll'];
                    // Get top players for the current poll
                    $top = "SELECT player_1 FROM players WHERE points = (SELECT MAX(points) FROM players WHERE poll='$dpoll')";
                    $ttop = $conn->query($top);

                    while ($printtop = $ttop->fetch_assoc()) {
                        $arr[$count] = $printtop['player_1'];
                        $count++;
                    }
                }

                // Generate "Player 1 VS Player 2" combinations
                for ($i = 0; $i < count($arr) - 1; $i++) {
                    for ($j = $i + 1; $j < count($arr); $j++) {
                        echo "<tr>
                                <td>{$arr[$i]}</td>
                                <td><input type='number' name='score[{$arr[$i]}][{$arr[$j]}][score1]' ></td>
                                <td>VS</td>
                                <td>{$arr[$j]}</td>
                                <td><input type='number' name='score[{$arr[$i]}][{$arr[$j]}][score2]' ></td>
                              </tr>";
                    }
                }
                ?>
            </table>
            <input type="submit" name="submit" value="Submit Scores">

            <input type="submit" name="restart" value="restart Tournament">
            <input type="submit" name="delete" value="delete Tournament">
        </form>

        <?php
        $conn=new mysqli('localhost','root','','tournament',3307);
        if($conn->connect_error)
        {
            die("failed to connect the database".$conn->connect_error);
        }
        if(!isset($_SESSION['winner']))
        {
            $_SESSION['winner']='';
        }
        // Process form submission
        if (isset($_POST['submit'])) {
            if (isset($_POST['score'])) {
                // Iterate over each match's score
                foreach ($_POST['score'] as $player1 => $matches) {
                    foreach ($matches as $player2 => $score) {
                        $score1 = (int)$score['score1'];
                        $score2 = (int)$score['score2'];

                        // Determine the winner based on the scores
                        if ($score1 > $score2) {
                            $_SESSION['winner']= $player1;
                        } else {
                            $_SESSION['winner'] = $player2;}


                        // Display the result of the match
                       

                            echo "<p>Winner of match between $player1 and $player2:". $_SESSION['winner']."</p>";
                
                    }
                }
            }
        }
        if(isset($_POST['restart']))
        {   
            $query_restart="update players set points=0";
            
            if($conn->query($query_restart)===true)
            {
                echo "<h3> all the points are set to zero</h3>";
            }
        }
        
        if(isset($_POST['delete']))
        {
            $winner = $_SESSION['winner'];
            $query_delete="delete from players where tcourt = (select tcourt from (select tcourt from players where player_1='$winner')as temp)";
            $ex=$conn->query($query_delete);
        }

        // Close the connection
        $conn->close();
        ?>
    </body>
</html>

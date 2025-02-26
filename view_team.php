<html>
    <head>
        <title>Admin</title>
        <style>
            body {
                background-image: url(2.jpg);
                background-size: cover;
                background-position: center;
                font-family: Arial, sans-serif;
                text-align: center;
            }
            table {
                border-collapse: collapse;
                margin: 20px auto;
                width: 80%;
            }
            th, td {
                padding: 10px;
                text-align: left;
                border: 1px solid #ddd;
            }
            th {
                background-color: rgba(255, 255, 255, 0.5);
            }
            tr:nth-child(even) {
                background-color: rgba(182, 141, 216, 0.9);
            }
            tr:nth-child(odd) {
                background-color: rgba(99, 141, 216, 0.9);
            }
            /* Button styling */
            .back-button {
                display: block;
                margin: 20px auto;
                padding: 12px 24px;
                font-size: 18px;
                color: white;
                background-color: #333;
                text-decoration: none;
                border-radius: 5px;
                transition: 0.3s;
                width: fit-content;
            }
            .back-button:hover {
                background-color: #555;
            }
        </style>
    </head>
    <body>

        <table>
            <?php
            $conn = mysqli_connect('localhost', 'root', '', 'tournament', 3307);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $select = "SELECT player_1, player_2, tcourt FROM players";
            $result = $conn->query($select);
            if ($result->num_rows > 0) {
                echo "<tr><th><h2>S.No</h2></th><th><h2>Player 1</h2></th> <th><h2>Player 2</h2></th> <th><h2>Tournament Court</h2></th></tr>";
                $i = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>$i</td> <td>" . $row['player_1'] . "</td> <td>" . $row['player_2'] . "</td><td>" . $row['tcourt'] . "</td></tr>";
                    $i++;
                }
                $conn->close();
            }
            ?>
        </table>

        <!-- Back Button centered below the table -->
        <a href="http://localhost:3000/namentry.php" class="back-button">⬅ Back</a>

    </body>
</html>

<html>
    <head>
        <title>admin</title>
        <style>
            body{
                background-image: url(2.jpg);
                background-size:cover;
                background-position: center;
            }
            table {
                border-collapse: collapse;
                margin: 20px auto;
            }
            th, td {
                padding: 10px;
                text-align: left;
                border: 1px solid #ddd;
            }
            th {
                background-color: rgb(255, 255, 255,0.5);
            }
            tr:nth-child(even)
            {
                background-color:  rgb(182, 141, 216,0.9);
            }
            tr:nth-child(odd)
            {
                background-color:  rgb(99, 141, 216,0.9);
            }

        </style>
</head>
<body>
<table>

<?php
$conn=mysqli_connect('localhost','root','','tournament',3307);
if($conn->connect_error)
{
    die("connection failed" .$conn->error);
}
$select= "select player_1,player_2,shuttle_type from players " ;
$result=$conn->query($select);
if($result->num_rows>0)
{
    echo "<tr><th><H2>S.No</h2></th><th><h2>Player 1</h2></th> <th><h2>Player 2</h2></th> <th><h2>Shuttle type</h2></th></tr>";
    $i=0;
    while($row = $result->fetch_assoc())
    {
        echo "<tr><td>$i</td> <td> ". $row['player_1'] ."</td> <td>". $row['player_2']."</td><td>".$row['shuttle_type']."</td> </t>";
        $i++;
    }
    $conn->close();
}
?>
</table>

</body>
</html>







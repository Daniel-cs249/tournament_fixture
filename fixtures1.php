<html>
    <head>
        <style>
            input
        {
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
       
        #next,#submit
        {
          width: 55px;
        }
        #edit
        {
          width: 45px;
        }
        button {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        button.edit {
            background-color: #f0ad4e;
            color: white;
        }

        button.submit {
            background-color: #5cb85c;
            color: white;
        }

        button:hover {
            opacity: 0.8;
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
    <body id=fixtures1>
        
<?php
echo "<form action='' method='post'>";
$conn= new mysqli('localhost','root','','tournament',3307);
if($conn->connect_error)
{
    die("connection failure".$conn->connect_error);
}

$pollcount="select distinct(poll) from players";
$ex1=$conn->query($pollcount);//got the count
$arr=[];
while($row1=$ex1->fetch_assoc())
{
    $arr[]=$row1['poll'];
}
$pollnumber=1;
for ($i = 0; $i < count($arr); $i++) 
{
    $pollno=$arr[$i];
    $select_pollno="select player_1 from players where poll='$pollno' and tcourt='venba'";
    $ex2=$conn->query($select_pollno);
    echo "<table id='table-container'>";
    echo "<tr><th colspan='7'>Poll : ".$pollnumber."</th></tr>";
    $inserta=0;
    $ar=[];
    while($row2=$ex2->fetch_assoc())
    {
        $ar[$inserta]=$row2['player_1'];
        $inserta++;
        
    }
    if(count($ar)==1)
    {
        echo "<tr><td>".$ar[0]."</td></tr>";
    }
    else
    {
        for($j=0;$j<count($ar);$j++)
        {
        
        for($k=$j+1;$k<count($ar);$k++)
        {
            echo "<tr><td>".$ar[$j]."</td><td><input type='number' name='score1'></td><td>Vs</td><td>".$ar[$k]."</td><td><input type='number' name='score2'></td>  
                    <td><input type='submit' name='edit' id='edit' value='edit'></td>
                    <td><input type='submit' name='submit'id='submit' value='submit'></td>
                </td></tr>";
        }   
        }
    }
    
    $pollnumber++;
}
echo "</table>";
echo "<a href='fixtures2.php'><input type='submit' name='next' id='next'></a>";
echo "</form>";
$conn->close();
?>
<?php
$conn= new mysqli('localhost','root','','tournament',3307);
if (isset($_POST['submit'])) {
    foreach ($_POST['score1'] as $player1 => $matches) {
        foreach ($matches as $player2 => $score1) {
            $score2 = $_POST['score2'][$player1][$player2];

            // Update the database for both players
            $update1 = "UPDATE players SET points = points + $score1 WHERE player_1 = '$player1'";
            $update2 = "UPDATE players SET points = points + $score2 WHERE player_1 = '$player2'";
            $conn->query($update1);
            $conn->query($update2);
        }}}
        $conn->close();
?>

</body>
</html>
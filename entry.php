<?php
include 'namentry.html';
if(isset($_POST['submit']))
{
    
    $name_1=$_POST['p1name'];//name of the first player
    $name_2=$_POST['p2name'];//name of the second player
    $gender_1=$_POST['gender1'];//gender detection part
    $homecourt=$_POST['homecourt'];
    $tcourt=$_POST['tcourt'];
    if($gender_1=="male")
    {
        $gender_1= "male";//player 1
    }
    else
    {
        $gender_1="female";
    }
    $gender_2=$_POST['gender2'];
    if($gender_2=="male")
    {
        $gender_2= "male";//player 2
    }
    else
    {
        $gender_2="female";
    }
    $age_1=$_POST['p1age'];
    $age_2=$_POST['p2age'];

    $type = isset($_POST['typen']) ? $_POST['typen'] : null;
    $selectedType = ($type === 'feather') ? "Feather" : (($type === 'nylon') ? "Nylon" : null);

    // Handle payment status selection
    $paidStatus = isset($_POST['paid']) ? $_POST['paid'] : null;
    $selectedPaidStatus = ($paidStatus === 'paid') ? "Paid" : (($paidStatus === 'not_paid') ? "Not-Paid" : null);
$host="localhost"; 
$user="root";
$pass="";
$db="tournament";
$conn=new mysqli($host,$user,$pass,$db,3307);
if($conn->connect_error)
{
    echo "failed to connect the db".$conn->connect_error;
    
}
else{
    $insert="insert into players(player_1,player_2,age_1,age_2,gender_1,gender_2,shuttle_type, homecourt,tcourt,payment) 
    values(' $name_1',' $name_2','$age_1','$age_2','$gender_1','$gender_2','$selectedType', '$homecourt','$tcourt','$selectedPaidStatus')";
    $result=$conn->query($insert);
    echo "<script>alert('Data successfully submitted!');</script>";
}
$conn->close();
  
}  
?>
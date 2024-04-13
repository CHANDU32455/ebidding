<?php
$conn = new mysqli("localhost","root","","ebidding");
if($conn->connect_error){
    die("Connection Failed : ".$conn->connect_error);
}else{
    echo "Successfully connected";
}
?>
<?php
$conn= new mysqli("127.0.0.1", "root", "", "MovieFlix","3308");
if( $conn->connect_error){
    echo"Once connect";
}else{
    echo("lol");
}

?>

<?php
$conn= new mysqli("127.0.0.1", "root", "root", "MovieFlix","3306");
if( $conn->connect_error){
    echo"Once connect";
}else{
    echo("lol");
}

?>

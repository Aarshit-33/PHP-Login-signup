<?php
    $connect = mysqli_connect("localhost","root","","ulimate_student");
    if(!($connect))
    {
        echo "Error to Connnect with Data-Base";
    }
?>
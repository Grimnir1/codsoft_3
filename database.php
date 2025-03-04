<?php 
    try {
        $conn = mysqli_connect('localhost', 'root', '', 'blog_db', );
    }
    catch(mysqli_sql_exception){
        echo"Could not connect";

    }

    if (!$conn) {
        echo"<script>alert('Could not connect to the database')</script>";
    }
?>
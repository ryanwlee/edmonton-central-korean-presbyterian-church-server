<?php
    function getConnection()
    {
        $host = 'localhost';
        $db_name = '';
        $username = '';
        $password = '';
        $conn= new mysqli($host, $username, $password, $db_name);
        if ($conn->connect_error) {
            $conn= null;
        }
        return $conn;
    }

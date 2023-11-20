<?php

function connectDB()
{
    return new PDO('mysql:host='.DB_HOST.';port=3307'.';dbname='.DB_DB, DB_USER, DB_PASS);
}


<?php
class MyDB extends SQLite3
{
    public function __construct()
    {
        $this->open('loginsystem.db');
    }
}

$db = new MyDB();

?>

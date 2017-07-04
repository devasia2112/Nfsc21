<?php
error_reporting(0);
<<<<<<< HEAD

// para faciliar nossas vidas vamos usar medoo para conexao com o banco.
include_once 'medoo.php';

// PDO via medoo (File Encode: ISO 8859 - 1 (Latin - 1) \ banco collation Latin1)
$database = new medoo(array(
    'database_type' => 'mysql',
    'database_name' => 'portal',
=======
// para faciliar nossas vidas vamos usar medoo para conexao com o banco.
include_once 'medoo.php';
// PDO via medoo (File Encode: ISO 8859 - 1 (Latin - 1) \ banco collation Latin1)
$database = new medoo(array(
    'database_type' => 'mysql',
    'database_name' => 'mydb',
>>>>>>> 91b22149f9eb0320dc40204e9386cdac86329220
    'server'        => 'localhost',
    'username'      => 'root',
    'password'      => '',
    'charset'       => 'latin1'
));

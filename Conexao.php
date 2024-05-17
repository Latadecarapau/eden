<?php
use FTP\Connection;

try{
    $conect = new PDO("mysql:host=localhost;port=3306;dbname=rir;","root","");
    echo 'conectado com sucesso';
}catch(PDOException $e){
    echo 'falhou'. $e->getMessage();
}

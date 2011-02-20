<?php
namespace Scripts;
require('../config.php');
extract($cfg['database']);
$pdo = new \PDO(sprintf("%s:host=%s;dbname=%s", $driver, $host, $database), $user, $password);
$date = new \DateTime("-1 week");

$sth = $pdo->prepare("
    DELETE FROM sessions
    WHERE latest < :date
    OR data = '[]'
");

$sth->execute(array(
    ':date' => $date->format('c')
));
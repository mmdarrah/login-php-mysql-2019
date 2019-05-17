<?php
require '../partials/connect.php';
if(isset($_GET['id'])){

$query = $pdo->prepare('DELETE FROM entries WHERE entryID = :entryID');
$query->execute([
    'entryID' => $_GET['id']
    ]);
header('Location: ../index.php');

}
?>
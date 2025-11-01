<?php
header('Content-Type: application/json');
$db = mysqli_connect('localhost', 'root', '', 'ajax');

if (!$db) {
    echo json_encode([]);
    exit;
}

$query = "SELECT * FROM posts ORDER BY id DESC";
$result = mysqli_query($db, $query);

if (!$result) {
    echo json_encode([]);
    exit;
}

$posts = [];
while($row = mysqli_fetch_assoc($result)){
    $posts[] = $row;
}

echo json_encode($posts);
mysqli_close($db);
?>
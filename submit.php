<?php
    header('Content-Type: application/json');
    $db = mysqli_connect('localhost', 'root', '', 'ajax');
    if(!$db){
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database Connection Failed']);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $title  = $input['title']  ?? '';
    $content= $input['content']?? '';

    if (empty($title) || empty($content)){
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all fields']);
        exit;
    }

    $title   = mysqli_real_escape_string($db, $title);
    $content = mysqli_real_escape_string($db, $content);
    $query   = "INSERT INTO posts (title, content) VALUES ('$title', '$content')";
    $r       = mysqli_query($db, $query);

    if(!$r){
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Query Failed']);
        exit;
    }

    echo json_encode(['status' => 'success', 'message' => 'Post Added Successfully']);
?>
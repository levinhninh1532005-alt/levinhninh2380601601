<?php

header("Content-Type: application/json");

require "../config/database.php";

$data = json_decode(
    file_get_contents("php://input"),
    true
);

$stmt = $conn->prepare(
"SELECT * FROM account
WHERE username=?"
);

$stmt->execute([
    $data['username']
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$user){
    http_response_code(401);

    echo json_encode([
        "message"=>"User not found"
    ]);

    exit;
}

if(
!password_verify(
    $data['password'],
    $user['password']
))
{
    http_response_code(401);

    echo json_encode([
        "message"=>"Wrong password"
    ]);

    exit;
}

echo json_encode([
    "message"=>"Login Success",
    "user"=>$user
]);

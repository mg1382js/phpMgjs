<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/Database.php';
include_once '../users/User.php';


$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $user->read();
        $num = $stmt->rowCount();
        if ($num > 0) {
            $user_array = array();
            $user_array['records'] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $user_item = array(
                    "id" => $id,
                    "name" => $name,
                    "email" => $email
                );
                array_push($user_array["records"], $user_item);
            }
            http_response_code(200);
            echo json_encode($user_array);
        } else {
            http_response_code(404);
            echo json_encode(array('message' => 'no user found'));
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'));
        if(!empty($data->name) && !empty($data->email)){
            $user->name = $data->name;
            $user->email = $data->email;

            if($user->create()){
                http_response_code(201);
                echo json_encode(array('message'=> 'user was created'));
            }else{
                http_response_code(503);
                echo json_encode(array('message'=> 'unable to create user'));

            }  
        }else{
            http_response_code(400);
            echo json_encode(array('message'=> 'incomplete data'));
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed."));
        break;
}

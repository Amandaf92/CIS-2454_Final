<?php
header("Content-Type: application/json");

require_once("../models/database.php");
require_once("../models/stores.php");

$db = (new Database())->connect();
$store = new Store($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case "GET":
        echo json_encode($store->getStores()->fetchAll(PDO::FETCH_ASSOC));
        break;

    case "POST":
        $data = json_decode(file_get_contents("php://input"));
        $store->createStore($data->name);
        echo json_encode(["message" => "Store created"]);
        break;

    case "DELETE":
        $id = $_GET['id'];
        $store->deleteStore($id);
        echo json_encode(["message" => "Store deleted"]);
        break;
}
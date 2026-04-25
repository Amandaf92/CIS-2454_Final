<?php
header("Content-Type: application/json");

require_once("../models/database.php");
require_once("../models/items.php");

$db = (new Database())->connect();
$item = new Item($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case "GET":
        $store_id = $_GET['store_id'];
        echo json_encode($item->getItemsByStore($store_id)->fetchAll(PDO::FETCH_ASSOC));
        break;

    case "POST":
        $data = json_decode(file_get_contents("php://input"));
        $item->createItem($data->store_id, $data->name, $data->quantity);
        echo json_encode(["message" => "Item added"]);
        break;

    case "DELETE":
        $id = $_GET['id'];
        $item->deleteItem($id);
        echo json_encode(["message" => "Item deleted"]);
        break;

    case "PUT":
        $data = json_decode(file_get_contents("php://input"));
        $item->updateItem($data->id, $data->checked, $data->name, $data->quantity);
        echo json_encode(["message" => "Item updated"]);
        break;
}
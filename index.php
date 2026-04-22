<?php

require_once "models/database.php";

$result = $conn->query("SELECT * FROM stores");

$stores = [];
while ($row = $result->fetch_assoc()) {
    $stores[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <title> Shopping List App </title>

</head>

<body>

<div>
    <div>
        <h1> Shopping List App </h1>
        <p> Create and manage your shopping lists by store </p>
    </div>

    <div>
        <h4> Add Store </h4>
        <form method="POST">
            <input type="text" placeholder="Enter Store Name">
            <button> Add Store </button>
        </form>
    </div>

    <div>
        <h4> Stores </h4>
        <ul>
            <?php foreach ($stores as $store): ?>
                <li> <?=$store['name'] ?> </li>
                <?php endforeach; ?>
        </ul>
    </div>
</div>

</body>
</html>

<?php
$db = new PDO('sqlite:C:/Users/HP/UKK_Villa/database/database.sqlite');
$result = $db->query('SELECT name, email, role FROM users');
$users = $result->fetchAll(PDO::FETCH_ASSOC);
echo "Total users: " . count($users) . "\n";
foreach ($users as $row) {
    echo $row['name'] . ' (' . $row['email'] . ') - ' . $row['role'] . PHP_EOL;
}
?>
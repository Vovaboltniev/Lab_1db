<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="keywords" content="Лабораторна робота, MySQL, з'єднання з базою даних">
    <meta name="description" content="Лабораторна робота. З'єднання з базою даних">
    <title>Таблиця з повідомленнями</title>

<style>
    td,th, table {
        border: 1px solid black
    }
</style>

</head>
<body>

    <h1>Всі повідомлення</h1>

<?php
// Параметри для з'єднання з базою даних
$host = 'localhost';
$username = 'root';
// В XAMPP пустий пароль '', в MAMP 'root'
$password = ''; 
$database = 'db';

try {
    // Підключення до бази даних з використанням PDO
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    
    // Встановлення режиму обробки помилок для PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Отримання всіх повідомлень від користувачів
    $sql = "SELECT u.user_name, m.message_text FROM users AS u INNER JOIN messages AS m ON u.user_id = m.user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    // Виведення результатів у вигляді HTML-таблиці
    if ($stmt->rowCount() > 0) {
        echo "<table><tr><th>Користувач</th><th>Повідомлення</th></tr>";


        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            printf("<tr><td>%s</td><td>%s</td></tr>", $row['user_name'], $row['message_text']);
        }

        echo "</table>";
    } else {
        echo "Немає даних у таблиці.";
    }
} catch (PDOException $e) {
    die("Помилка: " . $e->getMessage());
}

// Закриття підключення до бази даних
$pdo = null;

?>

</body>
</html>










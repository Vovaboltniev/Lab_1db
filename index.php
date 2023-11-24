<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="keywords" content="Лабораторна робота, MySQL, з'єднання з базою даних">
    <meta name="description" content="Лабораторна робота. З'єднання з базою даних">
    <title>Таблиця з повідомленнями</title>

    <style>
        td, th, table {
            border: 1px solid black;
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
    $sql = "SELECT * FROM messages"; // Змінено на messages
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    // Виведення результатів у вигляді HTML-таблиці
    if ($stmt->rowCount() > 0) {
        echo "<table><tr><th>ID</th><th>Message Text</th></tr>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            printf("<tr><td>%s</td><td>%s</td></tr>", $row['message_id'], $row['message_text']);
        }

        echo "</table>";
    } else {
        echo "Немає даних у таблиці.";
    }

    // Додавання користувача до таблиці `users`
    $username = 'Імя_користувача';
    $email = 'емейл@приклад.com';

    $sqlCheckUser = "SELECT * FROM users WHERE user_name = :username";
    $stmtCheckUser = $pdo->prepare($sqlCheckUser);
    $stmtCheckUser->bindParam(':username', $username, PDO::PARAM_STR);
    $stmtCheckUser->execute();

    if ($stmtCheckUser->rowCount() == 0) {
        // Якщо користувача ще немає, додаємо його
        $sqlAddUser = "INSERT INTO users (user_id, user_name) VALUES (:username, :email)";
        $stmtAddUser = $pdo->prepare($sqlAddUser);
        $stmtAddUser->bindParam(':username', $username, PDO::PARAM_STR);
        $stmtAddUser->bindParam(':email', $email, PDO::PARAM_STR);
        $stmtAddUser->execute();

        // Додаємо повідомлення від користувача
        $sqlAddMessage = "INSERT INTO messages (message_text, user_id) VALUES ('Повідомлення від користувача', LAST_INSERT_ID())";
        $pdo->query($sqlAddMessage);
    }

} catch (PDOException $e) {
    die("Помилка: " . $e->getMessage());
}

// Закриття підключення до бази даних
$pdo = null;
?>

</body>
</html>

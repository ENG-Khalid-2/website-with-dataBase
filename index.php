<?php
// الاتصال بقاعدة البيانات
$conn = new mysqli("localhost", "root", "", "info");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// إضافة بيانات عند الضغط على Submit
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $conn->query("INSERT INTO status (Name, Age) VALUES ('$name', $age)");
}

// تبديل الحالة (toggle)
if (isset($_GET['toggle'])) {
    $id = $_GET['toggle'];
    $result = $conn->query("SELECT Status FROM status WHERE ID = $id");
    $row = $result->fetch_assoc();
    $newStatus = $row['Status'] == 1 ? 0 : 1;
    $conn->query("UPDATE status SET Status = $newStatus WHERE ID = $id");
    header("Location: index.php"); // حتى يتم التحديث مباشرة
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Status Toggle</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 8px;
            text-align: center;
        }
        input {
            margin: 4px;
        }
        button {
            padding: 4px 8px;
        }
    </style>
</head>
<body>

    <form method="POST">
        Name: <input type="text" name="name" required>
        Age: <input type="number" name="age" required>
        <input type="submit" name="submit" value="Submit">
    </form>

    <br>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM status");
        while ($row = $result->fetch_assoc()) {
            $statusColor = $row['Status'] == 1 ? 'green' : 'red';
            $statusText = $row['Status'] == 1 ? 'Active' : 'Inactive';

            echo "<tr>
                    <td>{$row['ID']}</td>
                    <td>{$row['Name']}</td>
                    <td>{$row['Age']}</td>
                    <td style='color: $statusColor; font-weight: bold;'>$statusText</td>
                    <td><a href='?toggle={$row['ID']}'><button type='button'>Toggle</button></a></td>
                  </tr>";
        }
        ?>
    </table>

</body>
</html>

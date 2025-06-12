<?php
include '../../dbConn.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));

    if (empty($user) || empty($password)) {
        echo json_encode(["error" => "Please fill in all fields."]);
        exit;
    }

    $sql = "SELECT userID, role, fName, department FROM users_tbl WHERE email = '$user' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        $_SESSION['userID'] = $row['userID'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['fName'] = $row['fName'];
        $_SESSION['department'] = $row['department'];

        echo json_encode([
            "success" => true, 
            "role" => $row['role'],
            "fName" => $row['fName'],
            "department" => $row['department']
        ]);
    } else {
        echo json_encode(["error" => "Invalid username or password."]);
    }

    mysqli_close($conn);
}
?>

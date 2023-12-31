<?php

include 'config.php';

// Login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $email = $data['email'];
    $password = $data['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {

        $record = $result->fetch_assoc();
        $hashedPassword = $record['password'];

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $record['user_id'];
            $response = array(
                'success' => true,
                'message' => 'Login Successful'
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Password Incorrect'
            );
        }
    } else {
        $response = array(
            'success' => false,
            'message' => 'Account does not exist'
        );
    }
    echo json_encode($response);
}

// Session Management
if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['id'])) {
    $valid = false;

    if (isset($_SESSION['user_id'])) {
        $valid = true;
        $response = array(
            'success' => true,
            'valid' => $valid,
            'user_id' => $_SESSION['user_id']
        );
    } else {
        $response = array(
            'success' => false,
            'valid' => $valid
        );
    }

    echo json_encode($response);
}

// Get User
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM users WHERE user_id = '$id';";

    $result = $conn->query($sql);
    $record = $result->fetch_assoc();

    $user = array(
        'id' => $record['user_id'],
        'username' => $record['username'],
        'email' => $record['email']
    );

    $response = array(
        'success' => true,
        'user' => $user
    );

    echo json_encode($response);
}

?>
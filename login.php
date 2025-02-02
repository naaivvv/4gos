<?php
session_start();

include_once('db_connection.php');

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_input = $_POST["username"];
    $password_input = $_POST["password"];

    // Prepared statement for secure query
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username_input);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password_input, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php"); // Redirect to home page after successful login
            exit();
        } else {
            $error_message = "Incorrect password.";
        }
    } else {
        $error_message = "No user found with that username.";
    }
    
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - 2025 PIC18F4550 | ESP32 - 4GOS</title>
    <link href="src/output.css" rel="stylesheet">
</head>
<body class="bg-stone-100 text-stone-800">

    <!-- Top Navigation Bar -->
    <nav class="bg-white shadow-sm py-4">
        <div class="container mx-auto px-4">
            <h1 class="text-2xl font-semibold text-stone-700">2025 PIC18F4550 | ESP32 - 4GOS.</h1>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="bg-stone-50 py-12 shadow-sm">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl font-semibold text-stone-800">PIC18F4550-Based 4-Gang Outlet System</h2>
            <p class="text-stone-600 mt-2">with ESP32 Wi-Fi Control, Real-Time LCD Display, and <br> Temperature Monitoring</p>
        </div>
    </header>

    <!-- Login Form -->
    <main class="mt-10 min-h-[50vh]">
        <div class="container mx-auto max-w-md bg-white p-6 rounded-lg shadow">
            <form action="login.php" method="POST">
                <h2 class="text-2xl font-bold text-center mb-6">Login</h2>

                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-stone-700">Username</label>
                    <input type="text" id="username" name="username" required class="mt-1 block w-full px-3 py-2 border border-stone-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-stone-700">Password</label>
                    <input type="password" id="password" name="password" required class="mt-1 block w-full px-3 py-2 border border-stone-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <button type="submit" class="w-full bg-stone-500 text-white py-2 px-4 rounded-md shadow hover:bg-stone-600 transition">Login</button>

                <?php if (isset($error_message)): ?>
                    <div class="mt-4 text-red-500 text-sm text-center">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-10 bg-white py-4 shadow-t">
        <div class="container mx-auto text-center text-stone-600">
            <p>&copy; 2025 PIC18F4550 | ESP32 - 4GOS. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>

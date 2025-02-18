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
    <style>
    .bg-cyan-600 {
        background-color: var(--color-cyan-600) /* oklch(0.609 0.126 221.723) */;
    }
    .hover\:bg-cyan-700 {
        &:hover {
            @media (hover: hover) {
                background-color: var(--color-cyan-700) /* oklch(0.52 0.105 223.128) */;
            }
        }
    }
    </style>
</head>
<body class="bg-stone-100 text-stone-800">

    <!-- Top Navigation Bar -->
    <nav class="bg-white shadow-md sticky top-0 z-10">
        <div class="container mx-auto py-4 px-4">
                <a href="index.php" class="text-xl font-semibold text-stone-700 hover:text-stone-900 flex items-center gap-2">
                <svg fill="#000000" viewBox="0 0 24 24" id="processor" data-name="Flat Line" xmlns="http://www.w3.org/2000/svg" class="icon flat-line h-8 w-8 inline"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path id="secondary" d="M17,6H7A1,1,0,0,0,6,7V17a1,1,0,0,0,1,1H17a1,1,0,0,0,1-1V7A1,1,0,0,0,17,6Zm-3,8H10V10h4Z" style="fill: #2ca9bc; stroke-width: 2;"></path><path id="primary" d="M12,6V3m4,3V4M8,6V4m10,8h3m-3,4h2M18,8h2M12,18v3M8,18v2m8-2v2M6,12H3M6,8H4m2,8H4m14,1V7a1,1,0,0,0-1-1H7A1,1,0,0,0,6,7V17a1,1,0,0,0,1,1H17A1,1,0,0,0,18,17Zm-4-7H10v4h4Z" style="fill: none; stroke: #000000; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></path></g></svg>
                    PIC18F4550 | ESP32 - 4GOS
                </a>
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

                <button type="submit" class="w-full bg-cyan-600 text-white py-2 px-4 rounded-md shadow hover:bg-cyan-700 transition">Login</button>

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

<?php
include 'db.php'; // Ensure the db connection works
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user details from the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on the user's role
        if ($user['role'] === 'admin') {
            header("Location: admin_dashboard.php");  // Redirect to admin dashboard
        } elseif ($user['role'] === 'editor') {
            header("Location: post.php");  // Redirect to the post page
        } else {
            header("Location: view_post.php");  // Redirect to view posts for other roles
        }
        exit();
    } else {
        $error = "Invalid credentials!";
    }
}

// Array of quotes and facts
$quotes = [
    "Content is king, but engagement is queen, and the lady rules the house. — Mari Smith",
    "Creativity is intelligence having fun. — Albert Einstein",
    "Great things in business are never done by one person; they're done by a team of people. — Steve Jobs",
    "Without facts, you can’t have truth. Without truth, you can’t have trust. — Maria Ressa",
    "The best marketing doesn't feel like marketing. — Tom Fishburne",
    "Content is the atomic particle of all digital marketing. — Rebecca Lieb"
];

// Pick a random quote to display
$random_quote = $quotes[array_rand($quotes)];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .fade-in {
            opacity: 0;
            animation: fadeIn 1s ease-in forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-lg fade-in">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-4">Welcome to CMS</h1>
        
        <!-- Display a random thought or fact -->
        <div class="mb-6 text-center italic text-gray-600">
            <p>💡 <?= htmlspecialchars($random_quote) ?></p>
        </div>

        <!-- Login Form -->
        <form method="POST" action="login.php" class="space-y-4">
            <?php if (isset($error)): ?>
                <p class="text-red-500"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            
            <div>
                <label for="username" class="block text-gray-700">Username</label>
                <input type="text" name="username" id="username" placeholder="Enter your username" required
                    class="w-full p-2 border border-gray-300 rounded focus:ring focus:ring-blue-300 transition-all">
            </div>
            <div>
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required
                    class="w-full p-2 border border-gray-300 rounded focus:ring focus:ring-blue-300 transition-all">
            </div>
            
            <button type="submit"
                class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition-all">Login</button>
        </form>
        
        <p class="text-center text-gray-600 mt-6">
            Haven't registered yet? <a href="#" class="text-blue-500 hover:underline">Sign up</a>
        </p>
    </div>

    <!-- Footer -->
    <footer class="absolute bottom-4 w-full text-center text-gray-600">
        <p>&copy; <?= date('Y'); ?> CMS. All Rights Reserved.</p>
    </footer>
</body>
</html>

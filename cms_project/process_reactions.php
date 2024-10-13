
<?php
session_start();
require 'db_connection.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
    $reaction = $_POST['reaction'];

    // Check if user already reacted to the post
    $check_query = "SELECT * FROM user_likes WHERE user_id = ? AND post_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param('ii', $user_id, $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Insert new reaction
        $insert_query = "INSERT INTO user_likes (user_id, post_id, reaction) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param('iis', $user_id, $post_id, $reaction);
        $stmt->execute();

        // Update the likes/dislikes count in posts table
        if ($reaction == 'like') {
            $update_query = "UPDATE posts SET likes = likes + 1 WHERE id = ?";
        } else {
            $update_query = "UPDATE posts SET dislikes = dislikes + 1 WHERE id = ?";
        }
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param('i', $post_id);
        $stmt->execute();
    } else {
        // Optionally, handle cases where a user tries to react again
        echo "You have already reacted to this post.";
    }
}
?>

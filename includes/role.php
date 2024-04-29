<?php
/* Make connection with database before use functions */

// Return user role
function giveRole($id) {
    global $conn;
    try {
        // Query
        $sql = "SELECT role FROM users WHERE id = ?";

        // Set error mode
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare
        $stmt = $conn->prepare($sql);

        // Bind param and execute
        $stmt->execute([$id]);

        // Fetch result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result["role"];
    } catch (PDOException $e) {
        echo error("It was problem `{$e->getMessage()}` (Please contact with <a href='https://www.github.com/Arshia-Moharrary/aceBlog'>me</a> to report bug)");
    }
}
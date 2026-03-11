    <?php
session_start();
include 'db.php'; // Database connection

// Get tag ID from URL
$tagID = isset($_GET['id']) ? $_GET['id'] : '';

if(empty($tagID)){
    die("Invalid tag.");
}

// Look up the tag in the database
$query = mysqli_query($conn, "SELECT * FROM tags WHERE tag_id='$tagID'");
if(mysqli_num_rows($query) == 0){
    die("Tag not registered.");
}

$tag = mysqli_fetch_assoc($query);

// Check if tag is claimed
if($tag['user_id'] == NULL){
    // Redirect to signup page with tag parameter
    header("Location: signup.php?tag=$tagID");
    exit();
} else {
    // Tag has owner, get username
    $user_id = $tag['user_id'];
    $user_query = mysqli_query($conn, "SELECT username FROM users WHERE id='$user_id'");
    if(mysqli_num_rows($user_query) > 0){
        $user = mysqli_fetch_assoc($user_query);
        // Redirect to public profile page
        $username = $user['username'];
        header("Location: /u/$username");
        exit();
    } else {
        die("User not found.");
    }
}
?>
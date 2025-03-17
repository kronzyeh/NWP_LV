<?php
$database = new mysqli("localhost", "root", "", "nwplv2");
if ($database->connect_error) {
    die("Database connection failed: " . $database->connect_error);
}

$uploadDirectory = "uploads/";
$allowedExtensions = ['jpg', 'png', 'jpeg', 'pdf'];
$statusMessage = "";
$filePath = $_FILES["file"]["name"];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["file"])) {
    $originalFileName = basename($_FILES["file"]["name"]);
    $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    
    if (in_array($fileExtension, $allowedExtensions)) {
        $encryptedFileName = $uploadDirectory . uniqid("file_", true) . ".enc";
        
        $fileContent = file_get_contents($_FILES["file"]["tmp_name"]);
        $encryptionKey = openssl_random_pseudo_bytes(32);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length("aes-256-cbc"));
        $encryptedData = openssl_encrypt($fileContent, "aes-256-cbc", $encryptionKey, 0, $iv);
        
        if (file_put_contents($encryptedFileName, $iv . $encryptedData)) {
            $stmt = $database->prepare("INSERT INTO images (file_name, encryption_key) VALUES (?, ?)");
            $stmt->bind_param("ss", $encryptedFileName, base64_encode($encryptionKey));
            $stmt->execute();
            $stmt->close();
            
            $statusMessage = "File successfully uploaded and encrypted.";
        } else {
            $statusMessage = "Error encrypting file.";
        }
    } else {
        $statusMessage = "Invalid file format.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
</head>
<body>
    <h2>Upload and Encrypt File</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <button type="submit">Upload</button>
    </form>
    <p><?php echo $statusMessage; ?></p>
    <h2>Download Encrypted Files</h2>
    <ul>
        <?php
        $query = $database->query("SELECT file_name FROM images");
        if ($query->num_rows > 0) {
            while ($row = $query->fetch_assoc()) {
                echo '<li><a href="decrypt.php?id='.$row["file_name"].'">Download '.$row["file_name"].'</a></li>';
            }
        } else {
            echo "<p>No encrypted files found.</p>";
        }
        ?>
    </ul>
</body>
</html>

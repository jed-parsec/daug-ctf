<?php
if (isset($_FILES['file'])) {
    $upload_dir = "uploads/";
    $file_name = basename($_FILES['file']['name']);
    $upload_path = $upload_dir . $file_name;

    // Vulnerable: No file type or content validation
    if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_path)) {
        echo "File uploaded successfully: <a href='$upload_path'>$file_name</a>";
    } else {
        echo "Upload failed!";
    }
} else {
    echo "No file uploaded.";
}
?>

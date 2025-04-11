<!DOCTYPE html>
<html>
<head>
    <title>File Upload CTF</title>
</head>
<body>
    <h1>Upload Your File</h1>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="file">
        <input type="submit" value="Upload">
    </form>
    <p>Upload a file and see what happens!</p>
</body>
</html>

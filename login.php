<?php
// Secure session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Your sneaky password
$admin_password = "plase your own password"; 

// If already logged in, skip the login page and go to registry
if (isset($_SESSION['asb_admin']) && $_SESSION['asb_admin'] === true) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['login'])) {
    // Trim whitespace to prevent accidental "space" errors
    $input_password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($input_password === $admin_password) {
        $_SESSION['asb_admin'] = true;
        // Regenerate ID for security to prevent session fixation
        session_regenerate_id(true);
        header("Location: index.php");
        exit();
    } else {
        $error = "Incorrect Password. Access Denied.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASB | Admin Access</title>
    <style>
        body { 
            background: linear-gradient(135deg, #053317, #0a2014); 
            color: #fff; 
            font-family: 'Segoe UI', sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            margin: 0; 
        }
        .login-card { 
            background: rgba(255,255,255,0.03); 
            padding: 40px; 
            border-radius: 15px; 
            border: 1px solid #cfa650; 
            width: 340px; 
            text-align: center; 
            backdrop-filter: blur(15px); 
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        h2 { 
            color: #cfa650; 
            text-transform: uppercase; 
            letter-spacing: 3px; 
            margin-bottom: 25px; 
            font-family: 'Times New Roman', serif;
        }
        input { 
            width: 100%; 
            padding: 14px; 
            margin-bottom: 20px; 
            background: rgba(0,0,0,0.4); 
            border: 1px solid rgba(207,166,80,0.4); 
            color: #fff; 
            border-radius: 6px; 
            text-align: center; 
            font-size: 16px;
            outline: none;
            transition: 0.3s;
        }
        input:focus {
            border-color: #cfa650;
            background: rgba(0,0,0,0.6);
        }
        button { 
            width: 100%; 
            padding: 14px; 
            background: #cfa650; 
            color: #053317; 
            border: none; 
            border-radius: 6px; 
            font-weight: bold; 
            cursor: pointer; 
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
        }
        button:hover {
            background: #e6b85c;
            transform: translateY(-2px);
        }
        .error { 
            color: #ff4444; 
            font-size: 13px; 
            margin-bottom: 15px; 
            background: rgba(255,0,0,0.1);
            padding: 10px;
            border-radius: 4px;
            border: 1px solid rgba(255,0,0,0.2);
        }
        .back-link {
            display: block;
            margin-top: 20px;
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            font-size: 12px;
        }
        .back-link:hover {
            color: #cfa650;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Admin Portal</h2>
        <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
        <form method="POST">
            <input type="password" name="password" placeholder="••••••••" required autofocus>
            <button type="submit" name="login">Unlock Access</button>
        </form>
        <a href="index.php" class="back-link">Return to Public Registry</a>
    </div>
</body>
</html>
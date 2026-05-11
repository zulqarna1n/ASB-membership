<?php
// Secure the page so only you (Admin) can add members
include 'auth.php'; 
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['full_name'];
    $role = $_POST['role'];
    $phone = $_POST['phone'];
    $status = $_POST['status'];
    $year = date("Y");

    $isUnique = false;
    $generatedId = "";

    // Loop until a truly unique random ID is found
    while (!$isUnique) {
        $randomNum = str_pad(mt_rand(1, 99999), 5, "0", STR_PAD_LEFT);
        $generatedId = "ASB" . $year . "-" . $randomNum;

        // Check database to ensure this random ID isn't already taken
        $check = $pdo->prepare("SELECT id FROM members WHERE member_id = ?");
        $check->execute([$generatedId]);
        if (!$check->fetch()) {
            $isUnique = true;
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO members (member_id, full_name, role, phone, status) VALUES (?, ?, ?, ?, ?)");
        
        if ($stmt->execute([$generatedId, $name, $role, $phone, $status])) {
            header("Location: index.php");
            exit();
        }
    } catch (PDOException $e) {
        $error = "Error: Could not create member record.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASB | Add Member</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --asb-green: #053317; --asb-gold: #cfa650; }
        * { box-sizing: border-box; }
        body { background: #053317; color: #fff; font-family: 'Segoe UI', sans-serif; margin: 0; display: flex; flex-direction: column; min-height: 100vh; }
        
        header { background: rgba(0,0,0,0.3); padding: 20px 40px; border-bottom: 1px solid rgba(207,166,80,0.2); display: flex; align-items: center; }
        .back-link { color: var(--asb-gold); text-decoration: none; font-weight: bold; font-size: 14px; transition: 0.3s; }
        .back-link:hover { color: #fff; }
        
        .form-container { flex: 1; display: flex; justify-content: center; align-items: center; padding: 40px 20px; }
        .card { background: rgba(255,255,255,0.03); padding: 40px; border-radius: 15px; border: 1px solid rgba(207,166,80,0.3); width: 100%; max-width: 450px; backdrop-filter: blur(10px); }
        
        h2 { color: var(--asb-gold); text-align: center; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 25px; font-family: 'Times New Roman', serif; }
        label { display: block; margin-bottom: 5px; font-size: 11px; color: var(--asb-gold); text-transform: uppercase; }
        input, select { width: 100%; padding: 12px; margin-bottom: 20px; background: #0a2014; border: 1px solid rgba(207,166,80,0.5); color: #fff; border-radius: 6px; outline: none; }
        input:focus { border-color: var(--asb-gold); }
        
        button { width: 100%; padding: 15px; background: var(--asb-gold); color: #053317; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; text-transform: uppercase; transition: 0.3s; }
        button:hover { background: #f5e0a3; transform: translateY(-2px); }

        /* Footer matching the rest of the portal */
        footer {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            padding: 15px 40px;
            background: rgba(0, 0, 0, 0.7);
            border-top: 1px solid rgba(207, 166, 80, 0.3);
            color: #cfa650;
            font-size: 13px;
        }
        .footer-socials { display: flex; gap: 20px; font-size: 18px; }
        .footer-socials a { color: #cfa650; text-decoration: none; transition: 0.3s; }
        .footer-socials a:hover { color: #fff; }
        .whatsapp-icon { color: #25D366; }

        @media (max-width: 768px) {
            footer { flex-direction: column; gap: 10px; text-align: center; }
        }
    </style>
</head>
<body>

<header>
    <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i> VIEW REGISTRY</a>
</header>

<div class="form-container">
    <div class="card">
        <h2>Secure Entry</h2>
        <?php if(isset($error)) echo "<p style='color:#ff4444; font-size:12px; text-align:center;'>$error</p>"; ?>
        <form method="POST">
            <label>Member Name</label>
            <input type="text" name="full_name" placeholder="Enter Full Name" required>
            
            <label>Phone Number</label>
            <input type="text" name="phone" placeholder="+92 ..." required>
            
            <label>Designation</label>
            <select name="role">
                <option value="Member">General Member</option>
                <option value="Director">Director</option>
                <option value="Founder">Founder</option>
                <option value="Doctor">Medical Partner (Doctor)</option>
                <option value="PetShop">Affiliate PetShop</option>
                <option value="Volunteer">Volunteer</option>
            </select>
            
            <label>Initial Status</label>
            <select name="status">
                <option value="Active">Active</option>
                <option value="Pending">Pending</option>
                <option value="Inactive">Inactive</option>
            </select>
            
            <button type="submit">Assign ID & Save Record</button>
        </form>
    </div>
</div>

<footer>
    <div class="footer-left">
        <span>© 2026 Aash's Sanctuary & Boarding</span>
    </div>

    <div class="footer-socials">
        <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
        <a href="#" title="TikTok"><i class="fab fa-tiktok"></i></a>
        <a href="https://wa.me/923330483192" title="WhatsApp">
            <i class="fab fa-whatsapp whatsapp-icon"></i>
        </a>
        <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
    </div>

    <div class="footer-right">
        Peshawar, KPK
    </div>
</footer>

</body>
</html>
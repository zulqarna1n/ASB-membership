<?php 
include 'auth.php'; 
include 'config.php';

// Get ID and ensure it is an integer
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch current data
$stmt = $pdo->prepare("SELECT * FROM members WHERE id = ?");
$stmt->execute([$id]);
$member = $stmt->fetch();

// Redirect if member doesn't exist
if (!$member) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['full_name'];
    $role = $_POST['role'];
    $phone = $_POST['phone'];
    $status = $_POST['status'];

    $update = $pdo->prepare("UPDATE members SET full_name = ?, role = ?, phone = ?, status = ? WHERE id = ?");
    if ($update->execute([$name, $role, $phone, $status, $id])) {
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASB | Edit Member</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --asb-gold: #cfa650; --asb-green: #053317; }
        * { box-sizing: border-box; }
        body { background: #053317; color: #fff; font-family: 'Segoe UI', sans-serif; display: flex; flex-direction: column; min-height: 100vh; margin: 0; }
        
        .form-container { flex: 1; display: flex; justify-content: center; align-items: center; padding: 40px 20px; }
        .card { background: rgba(255,255,255,0.03); padding: 40px; border-radius: 15px; border: 1px solid var(--asb-gold); width: 100%; max-width: 450px; backdrop-filter: blur(10px); }
        
        label { display: block; margin-bottom: 8px; font-size: 11px; color: var(--asb-gold); text-transform: uppercase; letter-spacing: 1px; }
        input, select { width: 100%; padding: 12px; margin-bottom: 20px; background: #0a2014; border: 1px solid rgba(207,166,80,0.4); color: #fff; border-radius: 6px; outline: none; }
        input:focus, select:focus { border-color: var(--asb-gold); }

        input[readonly] { background: rgba(255,255,255,0.05); border-color: rgba(207,166,80,0.1); color: #888; cursor: not-allowed; font-family: monospace; }

        button { width: 100%; padding: 15px; background: var(--asb-gold); color: #053317; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; text-transform: uppercase; transition: 0.3s; }
        button:hover { background: #fff; }
        .cancel-link { display: block; text-align: center; margin-top: 15px; color: #fff; text-decoration: none; font-size: 13px; opacity: 0.6; }

        /* Footer matching index.php */
        footer { display: flex; justify-content: space-between; align-items: center; padding: 15px 40px; background: rgba(0, 0, 0, 0.7); border-top: 1px solid rgba(207, 166, 80, 0.3); color: var(--asb-gold); font-size: 13px; }
        .footer-socials a { color: var(--asb-gold); margin: 0 10px; text-decoration: none; }
    </style>
</head>
<body>

<div class="form-container">
    <div class="card">
        <h2 style="color:var(--asb-gold); margin-bottom:25px; text-align:center; text-transform:uppercase;">Update Record</h2>
        <form method="POST">
            <label>Unique ID (Read-Only)</label>
            <input type="text" value="<?php echo htmlspecialchars($member['member_id']); ?>" readonly>
            
            <label>Full Name</label>
            <input type="text" name="full_name" value="<?php echo htmlspecialchars($member['full_name']); ?>" required>
            
            <label>Phone Number</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($member['phone']); ?>" required>
            
            <label>Designation</label>
            <select name="role">
                <option value="Founder" <?php if($member['role'] == 'Founder') echo 'selected'; ?>>Founder</option>
                <option value="Director" <?php if($member['role'] == 'Director') echo 'selected'; ?>>Director</option>
                <option value="Doctor" <?php if($member['role'] == 'Doctor') echo 'selected'; ?>>Medical Partner (Doctor)</option>
                <option value="PetShop" <?php if($member['role'] == 'PetShop') echo 'selected'; ?>>Affiliate PetShop</option>
                <option value="Volunteer" <?php if($member['role'] == 'Volunteer') echo 'selected'; ?>>Volunteer</option>
                <option value="Member" <?php if($member['role'] == 'Member') echo 'selected'; ?>>General Member</option>
            </select>
            
            <label>Status</label>
            <select name="status">
                <option value="Active" <?php if($member['status'] == 'Active') echo 'selected'; ?>>Active</option>
                <option value="Pending" <?php if($member['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="Inactive" <?php if($member['status'] == 'Inactive') echo 'selected'; ?>>Inactive</option>
            </select>
            
            <button type="submit">Save Changes</button>
            <a href="index.php" class="cancel-link">Discard and Return</a>
        </form>
    </div>
</div>

<footer>
    <div>© 2026 Aash's Sanctuary & Boarding | Peshawar, KPK</div>
    <div class="footer-socials">
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-tiktok"></i></a>
        <a href="https://wa.me/923330483192"><i class="fab fa-whatsapp"></i></a>
        <a href="#"><i class="fab fa-youtube"></i></a>
    </div>
</footer>

</body>
</html>
<?php
include 'auth.php'; 
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $area = $_POST['area'];
    $phone = $_POST['phone'];
    $maps_link = $_POST['maps_link']; // New field for Google Maps

    try {
        $stmt = $pdo->prepare("INSERT INTO partners (name, category, area, phone, maps_link) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $category, $area, $phone, $maps_link])) {
            header("Location: index.php");
            exit();
        }
    } catch (PDOException $e) {
        $error = "Error: Could not add partner.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ASB | Add Partner</title>
    <style>
        :root { --asb-green: #053317; --asb-gold: #cfa650; }
        body { background: var(--asb-green); color: #fff; font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: rgba(255,255,255,0.03); padding: 30px; border-radius: 15px; border: 1px solid rgba(207,166,80,0.3); width: 100%; max-width: 400px; backdrop-filter: blur(10px); }
        h2 { color: var(--asb-gold); text-align: center; text-transform: uppercase; margin-bottom: 20px; }
        input, select { width: 100%; padding: 12px; margin-bottom: 15px; background: #0a2014; border: 1px solid rgba(207,166,80,0.5); color: #fff; border-radius: 6px; }
        button { width: 100%; padding: 15px; background: var(--asb-gold); color: #053317; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; text-transform: uppercase; }
        .back { display: block; text-align: center; color: var(--asb-gold); text-decoration: none; margin-top: 15px; font-size: 13px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Add Affiliate</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Clinic or Shop Name" required>
            <select name="category">
                <option value="Clinic">Clinic</option>
                <option value="PetShop">Pet Shop</option>
            </select>
            <input type="text" name="area" placeholder="Area (e.g. Hayatabad, Peshawar)" required>
            <input type="text" name="phone" placeholder="Contact Number" required>
            <input type="url" name="maps_link" placeholder="Google Maps URL (Optional)">
            <button type="submit">Save Partner</button>
        </form>
        <a href="index.php" class="back">← Back to Registry</a>
    </div>
</body>
</html>
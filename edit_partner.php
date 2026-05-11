<?php
include 'auth.php';
include 'config.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM partners WHERE id = ?");
$stmt->execute([$id]);
$partner = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $area = $_POST['area'];
    $phone = $_POST['phone'];
    $maps_link = $_POST['maps_link'];

    $update = $pdo->prepare("UPDATE partners SET name=?, category=?, area=?, phone=?, maps_link=? WHERE id=?");
    if ($update->execute([$name, $category, $area, $phone, $maps_link, $id])) {
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ASB | Edit Partner</title>
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
        <h2>Edit Partner</h2>
        <form method="POST">
            <input type="text" name="name" value="<?php echo htmlspecialchars($partner['name']); ?>" required>
            <select name="category">
                <option value="Clinic" <?php if($partner['category'] == 'Clinic') echo 'selected'; ?>>Clinic</option>
                <option value="PetShop" <?php if($partner['category'] == 'PetShop') echo 'selected'; ?>>Pet Shop</option>
            </select>
            <input type="text" name="area" value="<?php echo htmlspecialchars($partner['area']); ?>" required>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($partner['phone']); ?>" required>
            <input type="url" name="maps_link" value="<?php echo htmlspecialchars($partner['maps_link']); ?>">
            <button type="submit">Update Partner</button>
        </form>
        <a href="index.php" class="back">← Cancel Changes</a>
    </div>
</body>
</html>
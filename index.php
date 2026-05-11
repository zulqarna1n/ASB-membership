<?php
include 'auth.php'; 
include 'config.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$searchTerm = "%$search%";

// 1. Fetch Members
$query = "SELECT * FROM members WHERE full_name LIKE ? OR member_id LIKE ? OR role LIKE ? ORDER BY id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute([$searchTerm, $searchTerm, $searchTerm]);

// 2. Fetch Partners (Clinics & PetShops)
$partnerQuery = "SELECT * FROM partners ORDER BY category ASC, name ASC";
$partnerStmt = $pdo->prepare($partnerQuery);
$partnerStmt->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ASB | Member Registry</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --asb-green: #053317; --asb-gold: #cfa650; --glass: rgba(255, 255, 255, 0.03); }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body { 
            background: linear-gradient(135deg, #053317, #0a2014); 
            color: #fff; 
            font-family: 'Segoe UI', sans-serif; 
            min-height: 100vh; 
            display: flex; 
            flex-direction: column; 
        }

        header { 
            background: rgba(0,0,0,0.3); 
            padding: 20px 40px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            border-bottom: 1px solid rgba(207, 166, 80, 0.2); 
        }
        .logo { font-family: 'Times New Roman', serif; color: var(--asb-gold); font-size: 24px; text-decoration: none; font-weight: bold; text-transform: uppercase; }
        .nav-link { color: #fff; text-decoration: none; border: 1px solid var(--asb-gold); padding: 8px 15px; border-radius: 5px; font-size: 14px; transition: 0.3s; }
        .nav-link:hover { background: var(--asb-gold); color: var(--asb-green); }

        .main-content { padding: 40px; max-width: 1200px; margin: 0 auto; width: 100%; flex: 1; }
        .search-box { width: 100%; padding: 15px; background: var(--glass); border: 1px solid rgba(207,166,80,0.3); color: #fff; border-radius: 8px; margin-bottom: 25px; outline: none; font-size: 16px; }
        .search-box:focus { border-color: var(--asb-gold); }

        .table-container { background: var(--glass); border-radius: 15px; border: 1px solid rgba(207,166,80,0.1); overflow-y: auto; max-height: 600px; backdrop-filter: blur(10px); margin-bottom: 40px; }
        table { width: 100%; border-collapse: collapse; }
        th { position: sticky; top: 0; background: #081a10; color: var(--asb-gold); padding: 15px; text-align: left; font-size: 12px; text-transform: uppercase; z-index: 10; }
        td { padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 14px; vertical-align: middle; }

        .role-badge { padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; text-transform: uppercase; border: 1px solid; display: inline-block; }
        .role-founder { border-color: var(--asb-gold); color: var(--asb-gold); }
        .role-doctor, .role-clinic { border-color: #4da3ff; color: #4da3ff; }
        .role-petshop { border-color: #25D366; color: #25D366; }
        .role-volunteer { border-color: #ffcc00; color: #ffcc00; }
        .role-member { border-color: #aaa; color: #aaa; }

        .status-pill { padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: bold; background: rgba(255,255,255,0.1); display: inline-block; }
        .Active { color: #4ade80; border: 1px solid #4ade80; }
        .Pending { color: #ffcc00; border: 1px solid #ffcc00; }
        .Inactive { color: #ff4444; border: 1px solid #ff4444; }

        footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 40px;
            background: rgba(0, 0, 0, 0.7);
            border-top: 1px solid rgba(207, 166, 80, 0.3);
            color: #cfa650;
            font-size: 13px;
            margin-top: auto;
        }
        .footer-left { display: flex; align-items: center; gap: 25px; }
        .contact-highlight { color: #fff; font-weight: 700; font-size: 14px; text-decoration: none; transition: 0.3s; }
        .footer-socials { display: flex; gap: 20px; font-size: 18px; }
        .footer-socials a { color: var(--asb-gold); text-decoration: none; transition: 0.3s ease; }
        .footer-socials a:hover { color: #fff; transform: translateY(-2px); }

        @media (max-width: 900px) {
            footer { flex-direction: column; gap: 15px; padding: 25px; text-align: center; }
            .footer-left { flex-direction: column; gap: 5px; }
            header { padding: 15px 20px; }
            .logo { font-size: 18px; }
        }
    </style>
    <script>
        function confirmAction(url) {
            const masterPassword = "ashuni"; 
            let password = prompt("Action Restricted: Please enter the Admin Password to proceed:");

            if (password === masterPassword) {
                window.location.href = url;
            } else if (password !== null) {
                alert("Incorrect password. Access denied.");
            }
        }
    </script>
</head>
<body>

<header>
    <a href="index.php" class="logo">Aash's Sanctuary & Boarding</a>
    <a href="add_member.php" class="nav-link">+ Add Member</a>
</header>

<div class="main-content">
    <form method="GET">
        <input type="text" name="search" class="search-box" placeholder="Search by name, ID, or designation..." value="<?php echo htmlspecialchars($search); ?>">
    </form>

    <h2 style="color:var(--asb-gold); text-transform:uppercase; font-size: 18px; margin-bottom:15px; letter-spacing: 1px; font-family: 'Times New Roman', serif;">
        <i class="fas fa-users" style="margin-right: 10px;"></i> Member Registry
    </h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Member ID</th>
                    <th>Full Name</th>
                    <th>Designation</th>
                    <th>Status</th>
                    <th>Contact Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch()): ?>
                <tr>
                    <td style="color:var(--asb-gold); font-family:monospace;"><?php echo htmlspecialchars($row['member_id']); ?></td>
                    <td><strong><?php echo htmlspecialchars($row['full_name']); ?></strong></td>
                    <td>
                        <span class="role-badge role-<?php echo strtolower($row['role']); ?>">
                            <?php echo htmlspecialchars($row['role']); ?>
                        </span>
                    </td>
                    <td><span class="status-pill <?php echo $row['status']; ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                    <td>
                        <a href="tel:<?php echo $row['phone']; ?>" class="contact-highlight" style="text-decoration:none; margin-right:8px;">
                            <i class="fas fa-phone-alt" style="font-size:12px;"></i> <?php echo htmlspecialchars($row['phone']); ?>
                        </a>
                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $row['phone']); ?>" target="_blank" style="color:#25D366; text-decoration:none;">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </td>
                    <td>
                        <a href="javascript:void(0);" onclick="confirmAction('edit_member.php?id=<?php echo $row['id']; ?>')" style="color:var(--asb-gold); text-decoration:none; margin-right:15px;">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="javascript:void(0);" onclick="confirmAction('manage_status.php?action=delete&id=<?php echo $row['id']; ?>')" style="color:#ff4444; text-decoration:none;">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 50px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h2 style="color:var(--asb-gold); text-transform:uppercase; font-size: 18px; letter-spacing: 1px; font-family: 'Times New Roman', serif;">
            <i class="fas fa-handshake" style="margin-right: 10px;"></i> Affiliated Clinics & Pet Shops
        </h2>
        <a href="add_partner.php" class="nav-link" style="font-size: 12px; border-style: dashed;">+ Add Partner</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Entity Name</th>
                    <th>Location</th>
                    <th>Contact Number</th>
                    <th>Quick Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($partner = $partnerStmt->fetch()): ?>
                <tr>
                    <td>
                        <span class="role-badge role-<?php echo strtolower($partner['category']); ?>">
                            <?php echo htmlspecialchars($partner['category']); ?>
                        </span>
                    </td>
                    <td>
                        <?php if (!empty($partner['maps_link'])): ?>
                            <a href="<?php echo htmlspecialchars($partner['maps_link']); ?>" target="_blank" style="color:#fff; text-decoration:none; border-bottom: 1px dotted var(--asb-gold);">
                                <strong><?php echo htmlspecialchars($partner['name']); ?></strong>
                            </a>
                        <?php else: ?>
                            <strong><?php echo htmlspecialchars($partner['name']); ?></strong>
                        <?php endif; ?>
                    </td>
                    <td><i class="fas fa-map-marker-alt" style="font-size: 11px; opacity: 0.6;"></i> <?php echo htmlspecialchars($partner['area']); ?></td>
                    <td>
                        <a href="tel:<?php echo $partner['phone']; ?>" style="color:#fff; text-decoration:none; margin-right:8px; font-family: monospace;">
                            <i class="fas fa-phone-alt" style="font-size:12px; color:var(--asb-gold);"></i> <?php echo htmlspecialchars($partner['phone']); ?>
                        </a>
                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $partner['phone']); ?>" target="_blank" style="color:#25D366; text-decoration:none;">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </td>
                    <td>
                        <a href="javascript:void(0);" onclick="confirmAction('edit_partner.php?id=<?php echo $partner['id']; ?>')" style="color:var(--asb-gold); text-decoration:none; margin-right:15px; font-size: 13px;">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="javascript:void(0);" onclick="confirmAction('manage_partner.php?action=delete&id=<?php echo $partner['id']; ?>')" style="color:#ff4444; text-decoration:none; font-size: 13px;">
                            <i class="fas fa-trash-alt"></i> Delete
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<footer>
    <div class="footer-left">
        <span>© 2026 Aash's Sanctuary & Boarding</span>
        <a href="tel:+923330483192" class="contact-highlight">
            <i class="fas fa-phone-alt" style="font-size: 12px; margin-right: 5px;"></i> +92 333 0483192
        </a>
    </div>
    <div class="footer-socials">
        <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
        <a href="#" target="_blank"><i class="fab fa-tiktok"></i></a>
        <a href="https://wa.me/923330483192" target="_blank"><i class="fab fa-whatsapp" style="color:#25D366;"></i></a>
        <a href="#" target="_blank"><i class="fab fa-youtube"></i></a>
    </div>
    <div class="footer-right">
        <i class="fas fa-map-marker-alt" style="margin-right: 5px;"></i> Peshawar, KPK
    </div>
</footer>

</body>
</html>
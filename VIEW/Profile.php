<?php
session_start();
 
// Only logged-in users can access this page
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}
 
require_once __DIR__ . '/../MODEL/db.php';
require_once __DIR__ . '/../MODEL/Users.php';
 
$db      = new Database();
$conn    = $db->getConnection();
$profile = Users::getProfile($conn, $_SESSION['user']);
 
// ── Feedback messages ────────────────────────────────────────────────────────
$updateMsg  = '';
$pwdMsg     = '';
$deleteMsg  = '';
 
if (isset($_GET['updated'])) {
    $updateMsg = $_GET['updated'] === '1'
        ? ['type' => 'success', 'text' => '✅ Profile updated successfully!']
        : ['type' => 'error',   'text' => '❌ Error updating profile. Try again.'];
}
 
if (isset($_GET['pwd'])) {
    $pwdMsg = $_GET['pwd'] === 'ok'
        ? ['type' => 'success', 'text' => '✅ Password changed successfully!']
        : ['type' => 'error',   'text' => '❌ Current password is incorrect.'];
} elseif (isset($_GET['pwd_error'])) {
    $msgs = [
        'empty'    => '❌ Please fill in all password fields.',
        'mismatch' => '❌ New passwords do not match.',
        'short'    => '❌ Password must be at least 6 characters.',
    ];
    $pwdMsg = ['type' => 'error', 'text' => $msgs[$_GET['pwd_error']] ?? '❌ Unknown error.'];
}
 
if (isset($_GET['del_error'])) {
    $deleteMsg = ['type' => 'error', 'text' => '❌ Incorrect password. Account not deleted.'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub – My Profile</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
 
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f4ff;
            color: #333;
            padding-top: 70px;
        }
 
        /* ── Header ── */
        header {
            background: linear-gradient(135deg, #295be2 0%, #0579ec 100%);
            color: white;
            padding: 16px 0;
            box-shadow: 0 4px 15px rgba(0,0,0,.15);
            position: fixed;
            top: 0; width: 100%;
            z-index: 100;
        }
        .header-content {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-logo {
            font-weight: 800;
            font-size: 1.6em;
            letter-spacing: -0.5px;
        }
        .header-nav { display: flex; gap: 12px; align-items: center; }
        .header-nav a {
            color: rgba(255,255,255,.85);
            text-decoration: none;
            font-size: .9em;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 20px;
            transition: background .2s;
        }
        .header-nav a:hover { background: rgba(255,255,255,.2); color: white; }
        .header-nav a.active { background: rgba(255,255,255,.25); color: white; }
 
        /* ── Layout ── */
        .page {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 24px;
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 24px;
        }
 
        /* ── Card base ── */
        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,.07);
            padding: 28px;
        }
        .card h2 {
            font-size: 1.1em;
            color: #295be2;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e8edff;
            display: flex;
            align-items: center;
            gap: 8px;
        }
 
        /* ── Sidebar ── */
        .sidebar { display: flex; flex-direction: column; gap: 20px; }
 
        .avatar-wrap {
            text-align: center;
        }
        .avatar-img {
            width: 110px; height: 110px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #295be2;
            box-shadow: 0 4px 15px rgba(41,91,226,.25);
            margin-bottom: 14px;
        }
        .avatar-placeholder {
            width: 110px; height: 110px;
            border-radius: 50%;
            background: linear-gradient(135deg, #295be2, #0579ec);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.8em;
            color: white;
            border: 4px solid #295be2;
            box-shadow: 0 4px 15px rgba(41,91,226,.25);
            margin-bottom: 14px;
        }
        .sidebar-username {
            font-size: 1.2em;
            font-weight: 700;
            color: #222;
        }
        .sidebar-since {
            font-size: .8em;
            color: #888;
            margin-top: 4px;
        }
 
        .tab-list { display: flex; flex-direction: column; gap: 6px; }
        .tab-btn {
            background: none;
            border: none;
            text-align: left;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: .95em;
            font-weight: 500;
            color: #555;
            cursor: pointer;
            transition: all .2s;
            display: flex; align-items: center; gap: 10px;
        }
        .tab-btn:hover { background: #f0f4ff; color: #295be2; }
        .tab-btn.active { background: #e8edff; color: #295be2; font-weight: 700; }
        .tab-btn .icon { font-size: 1.1em; }
 
        /* ── Main area ── */
        .main { display: flex; flex-direction: column; gap: 0; }
 
        .tab-panel { display: none; flex-direction: column; gap: 0; }
        .tab-panel.active { display: flex; }
 
        /* ── Forms ── */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group.full { grid-column: 1 / -1; }
 
        label {
            font-size: .85em;
            font-weight: 600;
            color: #555;
            text-transform: uppercase;
            letter-spacing: .4px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea {
            padding: 11px 14px;
            border: 2px solid #e0e0e0;
            border-radius: 9px;
            font-size: .95em;
            font-family: inherit;
            transition: border-color .2s, box-shadow .2s;
            background: #fafafa;
        }
        input:focus, textarea:focus {
            outline: none;
            border-color: #295be2;
            box-shadow: 0 0 0 3px rgba(41,91,226,.1);
            background: #fff;
        }
        textarea { resize: vertical; min-height: 90px; }
 
        /* Avatar upload */
        .avatar-upload-area {
            border: 2px dashed #c5d0f0;
            border-radius: 10px;
            padding: 18px;
            text-align: center;
            cursor: pointer;
            transition: border-color .2s, background .2s;
        }
        .avatar-upload-area:hover { border-color: #295be2; background: #f5f8ff; }
        .avatar-upload-area input[type="file"] { display: none; }
        .avatar-upload-area .upload-hint {
            font-size: .85em; color: #888; margin-top: 6px;
        }
        #avatarPreview {
            width: 70px; height: 70px;
            border-radius: 50%;
            object-fit: cover;
            display: none;
            margin: 10px auto 0;
            border: 3px solid #295be2;
        }
 
        /* Buttons */
        .btn {
            padding: 11px 22px;
            border: none;
            border-radius: 9px;
            font-size: .95em;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s;
        }
        .btn-primary {
            background: linear-gradient(135deg, #295be2, #0579ec);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(41,91,226,.35);
        }
        .btn-danger {
            background: linear-gradient(135deg, #e53935, #c62828);
            color: white;
        }
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(229,57,53,.35);
        }
        .btn-outline {
            background: transparent;
            border: 2px solid #295be2;
            color: #295be2;
        }
        .btn-outline:hover { background: #e8edff; }
 
        .form-actions {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
        }
 
        /* Alert messages */
        .alert {
            padding: 12px 16px;
            border-radius: 9px;
            font-size: .9em;
            font-weight: 500;
            margin-bottom: 18px;
        }
        .alert-success { background: #e8f5e9; color: #2e7d32; border-left: 4px solid #43a047; }
        .alert-error   { background: #fce4ec; color: #c62828; border-left: 4px solid #e53935; }
 
        /* Delete panel */
        .danger-zone {
            border: 2px solid #ffcdd2;
            border-radius: 14px;
            padding: 24px;
            background: #fff8f8;
        }
        .danger-zone h3 { color: #c62828; margin-bottom: 10px; font-size: 1em; }
        .danger-zone p  { color: #666; font-size: .9em; margin-bottom: 18px; line-height: 1.5; }
 
        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 999;
            justify-content: center;
            align-items: center;
        }
        .modal-overlay.open { display: flex; }
        .modal {
            background: white;
            border-radius: 16px;
            padding: 32px;
            max-width: 420px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0,0,0,.25);
        }
        .modal h3 { font-size: 1.2em; color: #c62828; margin-bottom: 12px; }
        .modal p  { color: #555; font-size: .9em; margin-bottom: 20px; line-height: 1.5; }
        .modal .modal-actions { display: flex; gap: 10px; justify-content: flex-end; }
 
        /* Responsive */
        @media (max-width: 700px) {
            .page { grid-template-columns: 1fr; }
            .form-grid { grid-template-columns: 1fr; }
        }
 
        /* Password strength indicator */
        .strength-bar {
            height: 5px;
            border-radius: 3px;
            margin-top: 6px;
            transition: all .3s;
            background: #e0e0e0;
        }
        .strength-bar.weak   { background: linear-gradient(90deg, #e53935 33%, #e0e0e0 33%); }
        .strength-bar.medium { background: linear-gradient(90deg, #fb8c00 66%, #e0e0e0 66%); }
        .strength-bar.strong { background: #43a047; }
        .strength-label { font-size: .78em; color: #888; margin-top: 3px; }
 
        /* Read-only info row */
        .info-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-row:last-child { border-bottom: none; }
        .info-row .info-key  { width: 130px; font-weight: 600; font-size: .85em; color: #888; text-transform: uppercase; letter-spacing: .4px; flex-shrink: 0; }
        .info-row .info-val  { color: #333; font-size: .95em; }
        .info-row .info-val.empty { color: #bbb; font-style: italic; }
    </style>
</head>
<body>
 
<!-- ── Header ──────────────────────────────────────────────────────────────── -->
<header>
    <div class="header-content">
        <span class="header-logo">🎮 GameHub</span>
        <nav class="header-nav">
            <a href="shop.php">🛒 Shop</a>
            <a href="profile.php" class="active">👤 Profile</a>
            <a href="../CONTROLLER/UserController.php?action=logout">🚪 Logout</a>
        </nav>
    </div>
</header>
 
<!-- ── Page layout ─────────────────────────────────────────────────────────── -->
<div class="page">
 
    <!-- ── Sidebar ── -->
    <aside class="sidebar">
        <!-- Avatar & name -->
        <div class="card">
            <div class="avatar-wrap">
                <?php if (!empty($profile['avatar'])): ?>
                    <img src="<?= htmlspecialchars($profile['avatar']) ?>" class="avatar-img" alt="Avatar">
                <?php else: ?>
                    <div class="avatar-placeholder">
                        <?= strtoupper(substr($profile['username'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div class="sidebar-username"><?= htmlspecialchars($profile['username']) ?></div>
                <?php if (!empty($profile['full_name'])): ?>
                    <div class="sidebar-since"><?= htmlspecialchars($profile['full_name']) ?></div>
                <?php endif; ?>
                <div class="sidebar-since">Member since <?= !empty($profile['created_at'])
                    ? date('M Y', strtotime($profile['created_at']))
                    : 'N/A' ?></div>
            </div>
        </div>
 
        <!-- Navigation tabs -->
        <div class="card">
            <div class="tab-list">
                <button class="tab-btn active" data-tab="overview">
                    <span class="icon">👁️</span> Overview
                </button>
                <button class="tab-btn" data-tab="edit">
                    <span class="icon">✏️</span> Edit Profile
                </button>
                <button class="tab-btn" data-tab="password">
                    <span class="icon">🔒</span> Change Password
                </button>
                <button class="tab-btn" data-tab="danger">
                    <span class="icon">🗑️</span> Delete Account
                </button>
            </div>
        </div>
    </aside>
 
    <!-- ── Main content ── -->
    <main class="main">
 
        <!-- ══ OVERVIEW ════════════════════════════════════════════════════════ -->
        <div class="tab-panel active" id="tab-overview">
            <div class="card">
                <h2>👁️ Profile Overview</h2>
 
                <div class="info-row">
                    <span class="info-key">Username</span>
                    <span class="info-val"><?= htmlspecialchars($profile['username']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-key">Full Name</span>
                    <span class="info-val <?= empty($profile['full_name']) ? 'empty' : '' ?>">
                        <?= !empty($profile['full_name']) ? htmlspecialchars($profile['full_name']) : 'Not set' ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-key">Email</span>
                    <span class="info-val <?= empty($profile['email']) ? 'empty' : '' ?>">
                        <?= !empty($profile['email']) ? htmlspecialchars($profile['email']) : 'Not set' ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-key">Bio</span>
                    <span class="info-val <?= empty($profile['bio']) ? 'empty' : '' ?>">
                        <?= !empty($profile['bio']) ? nl2br(htmlspecialchars($profile['bio'])) : 'No bio yet' ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-key">Member Since</span>
                    <span class="info-val">
                        <?= !empty($profile['created_at'])
                            ? date('F j, Y', strtotime($profile['created_at']))
                            : 'N/A' ?>
                    </span>
                </div>
            </div>
        </div>
 
        <!-- ══ EDIT PROFILE ════════════════════════════════════════════════════ -->
        <div class="tab-panel" id="tab-edit">
            <div class="card">
                <h2>✏️ Edit Profile</h2>
 
                <?php if ($updateMsg): ?>
                    <div class="alert alert-<?= $updateMsg['type'] ?>"><?= $updateMsg['text'] ?></div>
                <?php endif; ?>
 
                <form action="../CONTROLLER/UserController.php" method="POST" enctype="multipart/form-data">
 
                    <div class="form-grid">
                        <!-- Username (read-only) -->
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" value="<?= htmlspecialchars($profile['username']) ?>" disabled>
                        </div>
 
                        <!-- Full name -->
                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" id="full_name" name="full_name"
                                   placeholder="Your full name"
                                   value="<?= htmlspecialchars($profile['full_name'] ?? '') ?>">
                        </div>
 
                        <!-- Email -->
                        <div class="form-group full">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email"
                                   placeholder="your@email.com"
                                   value="<?= htmlspecialchars($profile['email'] ?? '') ?>">
                        </div>
 
                        <!-- Bio -->
                        <div class="form-group full">
                            <label for="bio">Bio</label>
                            <textarea id="bio" name="bio" placeholder="Tell us a bit about yourself..."><?= htmlspecialchars($profile['bio'] ?? '') ?></textarea>
                        </div>
 
                        <!-- Avatar upload -->
                        <div class="form-group full">
                            <label>Profile Picture</label>
                            <div class="avatar-upload-area" onclick="document.getElementById('avatarInput').click()">
                                <div style="font-size:2em">📷</div>
                                <div>Click to upload a new picture</div>
                                <div class="upload-hint">JPG, PNG, WEBP · max 2 MB</div>
                                <img id="avatarPreview" src="" alt="Preview">
                                <input type="file" id="avatarInput" name="avatar" accept="image/*">
                            </div>
                        </div>
                    </div>
 
                    <div class="form-actions">
                        <button type="submit" name="updateProfile" class="btn btn-primary">
                            💾 Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
 
        <!-- ══ CHANGE PASSWORD ═════════════════════════════════════════════════ -->
        <div class="tab-panel" id="tab-password">
            <div class="card">
                <h2>🔒 Change Password</h2>
 
                <?php if ($pwdMsg): ?>
                    <div class="alert alert-<?= $pwdMsg['type'] ?>"><?= $pwdMsg['text'] ?></div>
                <?php endif; ?>
 
                <form action="../CONTROLLER/UserController.php" method="POST">
 
                    <div class="form-grid">
                        <div class="form-group full">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password"
                                   placeholder="Enter your current password" required>
                        </div>
 
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password"
                                   placeholder="At least 6 characters"
                                   oninput="checkStrength(this.value)" required>
                            <div class="strength-bar" id="strengthBar"></div>
                            <div class="strength-label" id="strengthLabel"></div>
                        </div>
 
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password"
                                   placeholder="Repeat new password" required>
                        </div>
                    </div>
 
                    <div class="form-actions">
                        <button type="submit" name="changePassword" class="btn btn-primary">
                            🔑 Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
 
        <!-- ══ DELETE ACCOUNT ══════════════════════════════════════════════════ -->
        <div class="tab-panel" id="tab-danger">
            <div class="card">
                <h2>🗑️ Delete Account</h2>
 
                <?php if ($deleteMsg): ?>
                    <div class="alert alert-<?= $deleteMsg['type'] ?>"><?= $deleteMsg['text'] ?></div>
                <?php endif; ?>
 
                <div class="danger-zone">
                    <h3>⚠️ Danger Zone</h3>
                    <p>
                        Deleting your account is <strong>permanent and irreversible</strong>.
                        All your data, purchase history, and preferences will be lost.<br><br>
                        To confirm, please enter your password below.
                    </p>
 
                    <button class="btn btn-danger" onclick="document.getElementById('deleteModal').classList.add('open')">
                        🗑️ Delete My Account
                    </button>
                </div>
            </div>
        </div>
 
    </main>
</div>
 
<!-- ── Delete confirmation modal ─────────────────────────────────────────── -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal">
        <h3>⚠️ Confirm Account Deletion</h3>
        <p>
            This action <strong>cannot be undone</strong>. Your account
            <strong><?= htmlspecialchars($profile['username']) ?></strong> and all
            associated data will be permanently deleted.
        </p>
 
        <form action="../CONTROLLER/UserController.php" method="POST">
            <div class="form-group" style="margin-bottom:16px">
                <label for="delete_password">Enter your password to confirm</label>
                <input type="password" id="delete_password" name="delete_password"
                       placeholder="Your password" required>
            </div>
 
            <div class="modal-actions">
                <button type="button" class="btn btn-outline"
                        onclick="document.getElementById('deleteModal').classList.remove('open')">
                    Cancel
                </button>
                <button type="submit" name="deleteAccount" class="btn btn-danger">
                    Yes, delete my account
                </button>
            </div>
        </form>
    </div>
</div>
 
<script>
// ── Tab switching ────────────────────────────────────────────────────────────
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const target = btn.dataset.tab;
 
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
 
        btn.classList.add('active');
        document.getElementById('tab-' + target).classList.add('active');
    });
});
 
// Auto-open tab based on URL params
(function() {
    const p = new URLSearchParams(location.search);
    if (p.has('updated'))   openTab('edit');
    if (p.has('pwd') || p.has('pwd_error')) openTab('password');
    if (p.has('del_error')) openTab('danger');
})();
 
function openTab(name) {
    const btn = document.querySelector('[data-tab="' + name + '"]');
    if (btn) btn.click();
}
 
// ── Avatar preview ───────────────────────────────────────────────────────────
document.getElementById('avatarInput').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('avatarPreview');
        preview.src = e.target.result;
        preview.style.display = 'block';
    };
    reader.readAsDataURL(file);
});
 
// ── Password strength ────────────────────────────────────────────────────────
function checkStrength(val) {
    const bar   = document.getElementById('strengthBar');
    const label = document.getElementById('strengthLabel');
    if (!val) { bar.className = 'strength-bar'; label.textContent = ''; return; }
 
    let score = 0;
    if (val.length >= 6)  score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val) && /[0-9]/.test(val)) score++;
    if (/[^a-zA-Z0-9]/.test(val)) score++;
 
    if (score <= 1) { bar.className = 'strength-bar weak';   label.textContent = 'Weak'; }
    else if (score <= 2) { bar.className = 'strength-bar medium'; label.textContent = 'Medium'; }
    else { bar.className = 'strength-bar strong'; label.textContent = 'Strong'; }
}
 
// Close modal on overlay click
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('open');
});
</script>
</body>
</html>
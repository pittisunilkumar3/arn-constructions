<?php
/**
 * ANR Constructions - Web Installer
 * Easy cPanel installation wizard - AMT Style
 * 
 * Usage: Upload all files to cPanel, create MySQL database, then visit:
 * http://yourdomain.com/install/
 * 
 * Files are stored in public/uploads/ (like AMT's uploads/)
 * No symlink needed! Works on any shared hosting.
 */

session_start();

// Check if already installed
if (file_exists(__DIR__ . '/../storage/installed')) {
    die('<!DOCTYPE html><html><head><title>Already Installed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{display:flex;align-items:center;justify-content:center;min-height:100vh;background:#f0f2f5;}</style>
    </head><body>
    <div class="text-center p-5 bg-white rounded shadow">
        <h2 class="text-success">✅ Already Installed!</h2>
        <p class="mt-3">ANR Constructions website is already installed.</p>
        <a href="../" class="btn btn-primary mt-3">Go to Website</a>
        <a href="../admin/login" class="btn btn-outline-primary mt-3">Admin Panel</a>
        <hr>
        <p class="text-muted small mt-3">To reinstall, delete <code>storage/installed</code> file and refresh this page.</p>
    </div>
    </body></html>');
}

$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($step) {
        case 2:
            $host = $_POST['db_host'] ?? 'localhost';
            $port = $_POST['db_port'] ?? '3306';
            $name = $_POST['db_name'] ?? '';
            $user = $_POST['db_user'] ?? '';
            $pass = $_POST['db_pass'] ?? '';

            try {
                $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
                $pdo = new PDO($dsn, $user, $pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $pdo->exec("USE `{$name}`");
                
                $_SESSION['db_config'] = [
                    'host' => $host,
                    'port' => $port,
                    'name' => $name,
                    'user' => $user,
                    'pass' => $pass,
                ];
                
                $step = 3;
            } catch (PDOException $e) {
                $error = 'Database connection failed: ' . $e->getMessage();
                $step = 2;
            }
            break;

        case 3:
            if (!isset($_SESSION['db_config'])) {
                $step = 2;
                break;
            }
            
            $db = $_SESSION['db_config'];
            try {
                $dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['name']};charset=utf8mb4";
                $pdo = new PDO($dsn, $db['user'], $db['pass']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $sqlFile = __DIR__ . '/anr_database.sql';
                if (file_exists($sqlFile)) {
                    $sql = file_get_contents($sqlFile);
                    $pdo->exec($sql);
                    $success = 'Database tables created and sample data imported successfully!';
                } else {
                    $error = 'SQL file not found: ' . $sqlFile;
                }
                
                $step = 4;
            } catch (PDOException $e) {
                $error = 'SQL import failed: ' . $e->getMessage();
                $step = 3;
            }
            break;

        case 4:
            $siteName = $_POST['site_name'] ?? 'ARN Constructions';
            $siteUrl = $_POST['site_url'] ?? '';
            $adminName = $_POST['admin_name'] ?? 'Super Admin';
            $adminEmail = $_POST['admin_email'] ?? 'admin@anrconstructions.com';
            $adminPass = $_POST['admin_pass'] ?? 'admin@123';
            $adminPassConfirm = $_POST['admin_pass_confirm'] ?? '';

            if ($adminPass !== $adminPassConfirm) {
                $error = 'Admin passwords do not match!';
                $step = 4;
                break;
            }

            if (strlen($adminPass) < 6) {
                $error = 'Admin password must be at least 6 characters!';
                $step = 4;
                break;
            }

            $_SESSION['site_config'] = [
                'site_name' => $siteName,
                'site_url' => rtrim($siteUrl, '/'),
                'admin_name' => $adminName,
                'admin_email' => $adminEmail,
                'admin_pass' => $adminPass,
            ];

            $step = 5;
            break;

        case 5:
            if (!isset($_SESSION['db_config']) || !isset($_SESSION['site_config'])) {
                $step = 1;
                break;
            }

            $db = $_SESSION['db_config'];
            $site = $_SESSION['site_config'];

            try {
                // 1. Generate .env file
                $appKey = 'base64:' . base64_encode(random_bytes(32));
                $envContent = generateEnvFile($db, $site, $appKey);
                file_put_contents(__DIR__ . '/../.env', $envContent);

                // 2. Update admin user in database
                $dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['name']};charset=utf8mb4";
                $pdo = new PDO($dsn, $db['user'], $db['pass']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $hashedPassword = password_hash($site['admin_pass'], PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = 1");
                $stmt->execute([$site['admin_name'], $site['admin_email'], $hashedPassword]);

                $stmt2 = $pdo->prepare("UPDATE site_settings SET value = ? WHERE `key` = 'site_name'");
                $stmt2->execute([$site['site_name']]);

                // 3. Create storage/framework directories (like AMT's system folder structure)
                $dirs = [
                    __DIR__ . '/../storage/framework/cache',
                    __DIR__ . '/../storage/framework/cache/data',
                    __DIR__ . '/../storage/framework/sessions',
                    __DIR__ . '/../storage/framework/views',
                    __DIR__ . '/../storage/logs',
                    __DIR__ . '/../bootstrap/cache',
                ];
                foreach ($dirs as $dir) {
                    if (!is_dir($dir)) {
                        mkdir($dir, 0755, true);
                    }
                }

                // 4. Create upload directories at ROOT level (like AMT's uploads/)
                $uploadDirs = [
                    '/../uploads',
                    '/../uploads/projects',
                    '/../uploads/projects/gallery',
                    '/../uploads/gallery',
                    '/../uploads/sliders',
                    '/../uploads/testimonials',
                    '/../uploads/amenities',
                    '/../uploads/floor-plans',
                    '/../uploads/brochures',
                    '/../uploads/settings',
                ];
                
                $indexHtml = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                
                foreach ($uploadDirs as $dir) {
                    $fullPath = __DIR__ . '/..' . $dir;
                    if (!is_dir($fullPath)) {
                        mkdir($fullPath, 0755, true);
                    }
                    // Add index.html protection (exactly like AMT)
                    $indexPath = $fullPath . '/index.html';
                    if (!file_exists($indexPath)) {
                        file_put_contents($indexPath, $indexHtml);
                    }
                }

                // 5. Create .htaccess for uploads (block PHP execution)
                $uploadHtaccess = '# Block PHP execution in uploads directory for security
<FilesMatch "\.(php|phtml|php3|php4|php5|php7|php8|pht|phar)$">
    <IfModule mod_authz_core.c>
        Require all denied
    </IfModule>
    <IfModule !mod_authz_core.c>
        Order deny,allow
        Deny from all
    </IfModule>
</FilesMatch>
Options -Indexes';
                file_put_contents(__DIR__ . '/../uploads/.htaccess', $uploadHtaccess);

                // 6. Create installed lock file
                file_put_contents(__DIR__ . '/../storage/installed', date('Y-m-d H:i:s'));

                $step = 6;
                unset($_SESSION['db_config'], $_SESSION['site_config']);

            } catch (Exception $e) {
                $error = 'Installation failed: ' . $e->getMessage();
                $step = 5;
            }
            break;
    }
}

function generateEnvFile($db, $site, $appKey) {
    $url = $site['site_url'] ?: 'http://localhost';
    return <<<ENV
APP_NAME="{$site['site_name']}"
APP_ENV=production
APP_KEY={$appKey}
APP_DEBUG=false
APP_URL={$url}

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST={$db['host']}
DB_PORT={$db['port']}
DB_DATABASE={$db['name']}
DB_USERNAME={$db['user']}
DB_PASSWORD={$db['pass']}

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="\${APP_NAME}"

VITE_APP_NAME="\${APP_NAME}"
ENV;
}

// Auto-detect site URL
$detectedUrl = '';
if (isset($_SERVER['HTTP_HOST'])) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $detectedUrl = $protocol . '://' . $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['SCRIPT_NAME']);
    if ($path !== '/' && $path !== '\\') {
        $detectedUrl .= $path;
    }
    // Remove /install from detected URL
    $detectedUrl = preg_replace('#/install$#', '', $detectedUrl);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ANR Constructions - Installer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .installer-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 750px;
            width: 100%;
            overflow: hidden;
        }
        .installer-header {
            background: linear-gradient(135deg, #b8860b, #d4a843);
            color: #fff;
            padding: 30px 40px;
            text-align: center;
        }
        .installer-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            margin: 0;
        }
        .installer-header p {
            margin: 8px 0 0;
            opacity: 0.9;
            font-size: 0.9rem;
        }
        .installer-body {
            padding: 35px 40px;
        }
        .step-indicator {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 30px;
        }
        .step-dot {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            background: #e9ecef;
            color: #6c757d;
            transition: all 0.3s;
        }
        .step-dot.active {
            background: #b8860b;
            color: #fff;
            box-shadow: 0 4px 12px rgba(184,134,11,0.4);
        }
        .step-dot.completed {
            background: #28a745;
            color: #fff;
        }
        .step-connector {
            width: 30px;
            height: 2px;
            background: #dee2e6;
            align-self: center;
        }
        .step-connector.completed {
            background: #28a745;
        }
        h2.step-title {
            font-family: 'Playfair Display', serif;
            color: #1a1a2e;
            margin-bottom: 5px;
            font-size: 1.4rem;
        }
        .step-subtitle {
            color: #6c757d;
            margin-bottom: 25px;
            font-size: 0.9rem;
        }
        .form-label {
            font-weight: 500;
            color: #333;
            font-size: 0.9rem;
        }
        .form-control:focus {
            border-color: #b8860b;
            box-shadow: 0 0 0 0.2rem rgba(184,134,11,0.15);
        }
        .btn-primary {
            background: linear-gradient(135deg, #b8860b, #d4a843);
            border: none;
            padding: 10px 30px;
            font-weight: 500;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #8B6914, #b8860b);
        }
        .alert { font-size: 0.9rem; }
        .req-item {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        .req-pass { background: #d4edda; color: #155724; }
        .req-fail { background: #f8d7da; color: #721c24; }
        .feature-list { list-style: none; padding: 0; }
        .feature-list li { padding: 6px 0; color: #555; font-size: 0.9rem; }
        .feature-list li i { color: #28a745; margin-right: 8px; }
        .success-icon { font-size: 4rem; color: #28a745; }
        .code-block { background: #f8f9fa; padding: 8px 12px; border-radius: 6px; font-family: monospace; font-size: 0.85rem; }
    </style>
</head>
<body>

<div class="installer-card">
    <div class="installer-header">
        <h1><i class="fas fa-building"></i> ANR Constructions</h1>
        <p>Website Installation Wizard (cPanel Ready)</p>
    </div>

    <div class="installer-body">
        <div class="step-indicator">
            <?php for ($i = 1; $i <= 6; $i++): ?>
                <?php if ($i > 1): ?>
                    <div class="step-connector <?php echo $step > $i ? 'completed' : ''; ?>"></div>
                <?php endif; ?>
                <div class="step-dot <?php echo $step === $i ? 'active' : ($step > $i ? 'completed' : ''); ?>">
                    <?php echo $step > $i ? '✓' : $i; ?>
                </div>
            <?php endfor; ?>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <!-- STEP 1: Welcome -->
        <?php if ($step === 1): ?>
            <h2 class="step-title">Welcome to the Installer</h2>
            <p class="step-subtitle">This wizard will set up your website in minutes — no SSH or terminal needed!</p>

            <div class="mb-4">
                <h6 class="mb-3"><i class="fas fa-clipboard-check text-primary"></i> Before you begin, make sure you have:</h6>
                <ul class="feature-list">
                    <li><i class="fas fa-check-circle"></i> A MySQL database created in cPanel</li>
                    <li><i class="fas fa-check-circle"></i> Database username and password</li>
                    <li><i class="fas fa-check-circle"></i> PHP 8.1+ enabled</li>
                    <li><i class="fas fa-check-circle"></i> All files uploaded to your hosting</li>
                </ul>
            </div>

            <div class="mb-4 p-3 bg-light rounded">
                <h6 class="mb-2"><i class="fas fa-server text-warning"></i> Server Requirements Check:</h6>
                <?php
                $checks = [
                    ['PHP >= 8.1', version_compare(PHP_VERSION, '8.1.0', '>=')],
                    ['PDO MySQL Extension', extension_loaded('pdo_mysql')],
                    ['MySQLi Extension', extension_loaded('mysqli')],
                    ['OpenSSL Extension', extension_loaded('openssl')],
                    ['MBString Extension', extension_loaded('mbstring')],
                    ['Tokenizer Extension', extension_loaded('tokenizer')],
                    ['JSON Extension', extension_loaded('json')],
                    ['cURL Extension', extension_loaded('curl')],
                    ['Fileinfo Extension', extension_loaded('fileinfo')],
                    ['uploads/ writable', is_writable(__DIR__ . '/../public/uploads') || (is_dir(__DIR__ . '/../public') && is_writable(__DIR__ . '/../public'))],
                    ['storage/ writable', is_writable(__DIR__ . '/../storage')],
                    ['bootstrap/cache/ writable', is_writable(__DIR__ . '/../bootstrap/cache')],
                ];
                foreach ($checks as $check):
                    $pass = $check[1];
                    $class = $pass ? 'req-pass' : 'req-fail';
                    $icon = $pass ? 'fa-check-circle' : 'fa-times-circle';
                ?>
                    <div class="req-item <?php echo $class; ?>">
                        <i class="fas <?php echo $icon; ?> me-2"></i> <?php echo $check[0]; ?>
                    </div>
                <?php endforeach; ?>
                </div>

            <div class="text-end">
                <a href="?step=2" class="btn btn-primary">
                    Start Installation <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>

        <!-- STEP 2: Database -->
        <?php elseif ($step === 2): ?>
            <h2 class="step-title">Database Configuration</h2>
            <p class="step-subtitle">Enter your MySQL database credentials from cPanel → MySQL Databases.</p>

            <form method="POST" action="?step=2">
                <?php $db = $_SESSION['db_config'] ?? []; ?>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Database Host</label>
                        <input type="text" name="db_host" class="form-control" value="<?php echo htmlspecialchars($db['host'] ?? 'localhost'); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Database Port</label>
                        <input type="text" name="db_port" class="form-control" value="<?php echo htmlspecialchars($db['port'] ?? '3306'); ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Database Name</label>
                    <input type="text" name="db_name" class="form-control" value="<?php echo htmlspecialchars($db['name'] ?? ''); ?>" placeholder="e.g., youruser_anr_website" required>
                    <div class="form-text">Usually format: <span class="code-block">cpaneluser_databasename</span></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Database Username</label>
                    <input type="text" name="db_user" class="form-control" value="<?php echo htmlspecialchars($db['user'] ?? ''); ?>" placeholder="e.g., youruser_dbuser" required>
                    <div class="form-text">Usually format: <span class="code-block">cpaneluser_dbuser</span></div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Database Password</label>
                    <input type="password" name="db_pass" class="form-control" value="<?php echo htmlspecialchars($db['pass'] ?? ''); ?>">
                </div>
                <div class="d-flex justify-content-between">
                    <a href="?step=1" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
                    <button type="submit" class="btn btn-primary">Test Connection & Continue <i class="fas fa-arrow-right ms-2"></i></button>
                </div>
            </form>

        <!-- STEP 3: Import DB -->
        <?php elseif ($step === 3): ?>
            <h2 class="step-title">Import Database</h2>
            <p class="step-subtitle">Connection successful! Now let's create the tables and import sample data.</p>

            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <strong>Database connected!</strong><br>
                <small>Host: <?php echo htmlspecialchars($_SESSION['db_config']['host']); ?> | DB: <?php echo htmlspecialchars($_SESSION['db_config']['name']); ?></small>
            </div>

            <div class="mb-3 p-3 bg-light rounded">
                <h6 class="mb-2">This will create:</h6>
                <ul class="feature-list">
                    <li><i class="fas fa-check-circle"></i> All database tables (users, projects, enquiries, etc.)</li>
                    <li><i class="fas fa-check-circle"></i> Default site settings</li>
                    <li><i class="fas fa-check-circle"></i> Sample projects, testimonials, amenities</li>
                    <li><i class="fas fa-check-circle"></i> Upload directories with security protection</li>
                    <li><i class="fas fa-check-circle"></i> Default admin user</li>
                </ul>
            </div>

            <form method="POST" action="?step=3">
                <div class="d-flex justify-content-between">
                    <a href="?step=2" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-database me-2"></i> Import Database</button>
                </div>
            </form>

        <!-- STEP 4: Admin Config -->
        <?php elseif ($step === 4): ?>
            <h2 class="step-title">Site & Admin Configuration</h2>
            <p class="step-subtitle">Set your website URL and admin login credentials.</p>

            <?php $site = $_SESSION['site_config'] ?? []; ?>

            <form method="POST" action="?step=4">
                <h6 class="mb-3 text-primary"><i class="fas fa-globe"></i> Website Settings</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Site Name</label>
                        <input type="text" name="site_name" class="form-control" value="<?php echo htmlspecialchars($site['site_name'] ?? 'ARN Constructions'); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Site URL</label>
                        <input type="url" name="site_url" class="form-control" value="<?php echo htmlspecialchars($site['site_url'] ?? $detectedUrl); ?>" placeholder="https://yourdomain.com" required>
                        <div class="form-text">Auto-detected from your browser.</div>
                    </div>
                </div>

                <hr class="my-4">

                <h6 class="mb-3 text-primary"><i class="fas fa-user-shield"></i> Admin Account</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Admin Name</label>
                        <input type="text" name="admin_name" class="form-control" value="<?php echo htmlspecialchars($site['admin_name'] ?? 'Super Admin'); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Admin Email</label>
                        <input type="email" name="admin_email" class="form-control" value="<?php echo htmlspecialchars($site['admin_email'] ?? 'admin@anrconstructions.com'); ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Admin Password</label>
                        <input type="password" name="admin_pass" class="form-control" placeholder="Min 6 characters" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="admin_pass_confirm" class="form-control" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="?step=3" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
                    <button type="submit" class="btn btn-primary">Save & Continue <i class="fas fa-arrow-right ms-2"></i></button>
                </div>
            </form>

        <!-- STEP 5: Confirm -->
        <?php elseif ($step === 5): ?>
            <h2 class="step-title">Confirm Installation</h2>
            <p class="step-subtitle">Review your settings and click Install.</p>

            <?php $site = $_SESSION['site_config'] ?? []; $db = $_SESSION['db_config'] ?? []; ?>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr><th width="40%" class="bg-light">Site Name</th><td><?php echo htmlspecialchars($site['site_name']); ?></td></tr>
                    <tr><th class="bg-light">Site URL</th><td><?php echo htmlspecialchars($site['site_url']); ?></td></tr>
                    <tr><th class="bg-light">Database</th><td><?php echo htmlspecialchars($db['name']); ?> @ <?php echo htmlspecialchars($db['host']); ?></td></tr>
                    <tr><th class="bg-light">Admin Email</th><td><?php echo htmlspecialchars($site['admin_email']); ?></td></tr>
                    <tr><th class="bg-light">Admin Password</th><td>••••••••</td></tr>
                    <tr><th class="bg-light">Upload Storage</th><td><span class="badge bg-success">uploads/ (No symlink needed)</span></td></tr>
                </table>
            </div>

            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> <strong>After install:</strong> Delete the <code>install/</code> folder for security.
            </div>

            <form method="POST" action="?step=5">
                <div class="d-flex justify-content-between">
                    <a href="?step=4" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
                    <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-rocket me-2"></i> Install Now</button>
                </div>
            </form>

        <!-- STEP 6: Complete -->
        <?php elseif ($step === 6): ?>
            <div class="text-center">
                <div class="mb-4"><i class="fas fa-check-circle success-icon"></i></div>
                <h2 class="step-title">Installation Complete! 🎉</h2>
                <p class="step-subtitle">Your ANR Constructions website is ready.</p>

                <div class="alert alert-success text-start">
                    <h6 class="mb-2"><i class="fas fa-shield-alt"></i> Your Admin Credentials:</h6>
                    <p class="mb-1"><strong>Admin Panel:</strong> <code><?php echo htmlspecialchars($detectedUrl); ?>/admin/login</code></p>
                    <p class="mb-1"><strong>Email:</strong> <code>(as you set in Step 4)</code></p>
                    <p class="mb-0"><strong>Password:</strong> <code>(as you set in Step 4)</code></p>
                </div>

                <div class="mt-4">
                    <a href="../" class="btn btn-primary btn-lg me-2"><i class="fas fa-home me-2"></i> View Website</a>
                    <a href="../admin/login" class="btn btn-outline-primary btn-lg"><i class="fas fa-lock me-2"></i> Admin Panel</a>
                </div>

                <div class="mt-4 p-3 bg-light rounded text-start">
                    <h6 class="mb-2"><i class="fas fa-tasks text-success"></i> Post-Installation Checklist:</h6>
                    <ul class="feature-list">
                        <li><i class="fas fa-circle text-danger" style="font-size:8px"></i> <strong class="text-danger">DELETE the <code>install/</code> folder now!</strong></li>
                        <li><i class="fas fa-circle text-muted" style="font-size:8px"></i> Log into admin panel and update site settings</li>
                        <li><i class="fas fa-circle text-muted" style="font-size:8px"></i> Upload your logo, slider images</li>
                        <li><i class="fas fa-circle text-muted" style="font-size:8px"></i> Replace sample projects with real data</li>
                        <li><i class="fas fa-circle text-muted" style="font-size:8px"></i> Configure SMTP email in Admin → SMTP Settings</li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

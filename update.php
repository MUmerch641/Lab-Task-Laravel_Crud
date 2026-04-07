<?php
include "db.php";

// Get user ID from URL parameter
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$id) {
    header("Location: index.php");
    exit;
}

// Initialize user data
$user = null;
$error_message = '';
$success_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validation
    if (empty($name) || empty($email) || empty($password)) {
        $error_message = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Invalid email format.';
    } else {
        // Update user
        $update_query = "UPDATE users SET name=?, email=?, password=? WHERE id=?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssi", $name, $email, $password, $id);
        
        if ($stmt->execute()) {
            $success_message = 'User updated successfully!';
            // Redirect after 1 second
            header("refresh:1; url=index.php");
        } else {
            $error_message = 'Error updating user: ' . $conn->error;
        }
        $stmt->close();
    }
}

// Fetch user data
$fetch_query = "SELECT id, name, email, password FROM users WHERE id=?";
$stmt = $conn->prepare($fetch_query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            background: white;
            width: 90%;
            max-width: 500px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            animation: slideIn 0.4s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            color: #667eea;
            margin-bottom: 30px;
            text-align: center;
            font-size: 28px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #2d3748;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            background-color: #f7fafc;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .button-group {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }

        button, .btn-cancel {
            flex: 1;
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-update {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-cancel {
            background-color: #cbd5e0;
            color: #2d3748;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-cancel:hover {
            background-color: #a0aec0;
            transform: translateY(-2px);
        }

        .alert {
            padding: 15px 16px;
            margin-bottom: 20px;
            border-radius: 8px;
            border-left: 4px solid;
            font-weight: 500;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .alert-error {
            background-color: #fff5f5;
            color: #742a2a;
            border-color: #fc8181;
        }

        .alert-success {
            background-color: #f0fff4;
            color: #22543d;
            border-color: #9ae6b4;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Update User Information</h2>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($user['password']); ?>" required>
            </div>

            <div class="button-group">
                <button type="submit" name="update" class="btn-update">Update User</button>
                <a href="index.php" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
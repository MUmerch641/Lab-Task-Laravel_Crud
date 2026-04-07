<?php
include "db.php";

$error_message = '';
$success_message = '';

// Fetch all users
$query = "SELECT id, name, email FROM users ORDER BY id DESC";
$result = mysqli_query($conn, $query);
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 30px 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        h2 {
            color: white;
            margin-bottom: 25px;
            text-align: center;
            font-size: 32px;
            font-weight: 600;
        }

        .form-card {
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            margin-bottom: 40px;
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

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .form-group {
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

        .btn-save {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            align-self: flex-start;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .table-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background: #f7fafc;
        }

        table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #2d3748;
            border-bottom: 2px solid #e2e8f0;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        table td {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
        }

        table tbody tr:hover {
            background-color: #f7fafc;
            transition: all 0.3s ease;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-edit, .btn-delete {
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background-color: #4299e1;
            color: white;
        }

        .btn-edit:hover {
            background-color: #3182ce;
            transform: translateY(-2px);
        }

        .btn-delete {
            background-color: #f56565;
            color: white;
        }

        .btn-delete:hover {
            background-color: #e53e3e;
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 50px;
            color: #718096;
        }

        .empty-state p {
            font-size: 16px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>👥 User Management System</h2>

        <div class="form-card">
            <h3 style="color: #2d3748; margin-bottom: 20px;">Add New User</h3>
            <form action="insert.php" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                </div>
                <button type="submit" class="btn-save">➕ Save User</button>
            </form>
        </div>

        <div class="table-card">
            <h3 style="color: #2d3748; margin-bottom: 20px;">User List</h3>
            <?php if (count($users) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="update.php?id=<?php echo $user['id']; ?>" class="btn-edit">✏️ Edit</a>
                                        <a href="delete.php?id=<?php echo $user['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')">🗑️ Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <p>📭 No users found</p>
                    <p>Add a new user using the form above</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

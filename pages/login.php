<?php
require_once '../config/config.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';

// Jeśli użytkownik jest już zalogowany, przekieruj na stronę główną
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $errors['login'] = 'Wprowadź email i hasło';
    } else {
        if ($user->login($email, $password)) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_role'] = $user->role;
            $_SESSION['user_name'] = $user->first_name;
            
            // Przekierowanie do odpowiedniej strony w zależności od roli
            if ($user->role == 'admin') {
                header("Location: memberships/types.php");
            } elseif ($user->role == 'trainer') {
                header("Location: dashboard/trainer.php");
            } else {
                header("Location: account.php");
            }
            exit();
        } else {
            $errors['login'] = 'Nieprawidłowy email lub hasło';
        }
    }
}

require_once '../templates/header.php';
?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Logowanie</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors['login'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $errors['login']; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Hasło</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Zaloguj się</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">Nie masz konta? <a href="register.php">Zarejestruj się</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../templates/footer.php'; ?>
<?php
require_once '../config/config.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';

// Tablica na błędy
$errors = [];
$success = false;

// Jeśli formularz został wysłany
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Utworzenie połączenia z bazą
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);

    // Pobieranie i czyszczenie danych z formularza
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
    $last_name = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);

    // Walidacja
    if (empty($email)) {
        $errors['email'] = 'Email jest wymagany';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Nieprawidłowy format email';
    } elseif ($user->emailExists($email)) {
        $errors['email'] = 'Ten email jest już zajęty';
    }

    if (empty($password)) {
        $errors['password'] = 'Hasło jest wymagane';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Hasło musi mieć minimum 6 znaków';
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Hasła nie są identyczne';
    }

    if (empty($first_name)) {
        $errors['first_name'] = 'Imię jest wymagane';
    }

    if (empty($last_name)) {
        $errors['last_name'] = 'Nazwisko jest wymagane';
    }

    // Jeśli nie ma błędów, próbujemy zarejestrować użytkownika
    if (empty($errors)) {
        $user->email = $email;
        $user->password = $password;
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->phone = $phone;
        $user->role = 'client';

        if ($user->register()) {
            // Automatyczne logowanie po rejestracji
            if ($user->login($email, $password)) {
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_role'] = $user->role;
                $_SESSION['user_name'] = $user->first_name;
                
                header("Location: ../index.php");
                exit();
            }
        } else {
            $errors['register'] = 'Wystąpił błąd podczas rejestracji';
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
                    <h3 class="text-center">Rejestracja</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors['register'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $errors['register']; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                   id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errors['email']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">Imię</label>
                                <input type="text" class="form-control <?php echo isset($errors['first_name']) ? 'is-invalid' : ''; ?>" 
                                       id="first_name" name="first_name" value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>">
                                <?php if (isset($errors['first_name'])): ?>
                                    <div class="invalid-feedback">
                                        <?php echo $errors['first_name']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Nazwisko</label>
                                <input type="text" class="form-control <?php echo isset($errors['last_name']) ? 'is-invalid' : ''; ?>" 
                                       id="last_name" name="last_name" value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>">
                                <?php if (isset($errors['last_name'])): ?>
                                    <div class="invalid-feedback">
                                        <?php echo $errors['last_name']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Telefon</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Hasło</label>
                            <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                                   id="password" name="password">
                            <?php if (isset($errors['password'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errors['password']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Potwierdź hasło</label>
                            <input type="password" class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>" 
                                   id="confirm_password" name="confirm_password">
                            <?php if (isset($errors['confirm_password'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errors['confirm_password']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Zarejestruj się</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">Masz już konto? <a href="login.php">Zaloguj się</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../templates/footer.php'; ?>
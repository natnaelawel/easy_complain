<?php
session_start();
require_once("./libs/utils.php");
require_once("./config/db.php");

$errors = []
?>



<?php

// When form submitted, check and create user session.
if (isset($_POST['login'])) {

    $email = stripslashes($_POST['email']);    // removes backslashes
    $email = htmlspecialchars($email);    // removes backslashes
    $password = stripslashes($_POST['password']);
    $password = htmlspecialchars($password);
    $recaptcha = $_POST['g-recaptcha-response'];

    $secret_key = "";


    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is invalid");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret='
        . $secret_key . '&response=' . $recaptcha;


    try {
        // Making request to verify captcha
        $response = file_get_contents($url);

        $response = json_decode($response);
    } catch (Exception $e) {
        die($e);
    }

    if (!$response->success) {
        array_push($errors, "Invalid Request");
    }

    if (count($errors) == 0) {
        try {
            $user_check_query = "SELECT * FROM users WHERE email=:email AND is_active = 1 LIMIT 1";
            $stmt = $conn->prepare($user_check_query);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->rowCount();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($count < 1) {
                array_push($errors, "Invalid credentials");
            } else {
                $isMatched =  password_verify($password,  $row['password']);
                if ($isMatched) {
                    $_SESSION['id']  =  $row['id'];
                    $_SESSION['email'] =  $row['email'];
                    $_SESSION['password'] =  $row['password'];
                    $_SESSION['is_admin'] =  $row['is_admin'];
                    $_SESSION['is_active'] =  $row['is_active'];
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(35));

                    header('Location:dashboard.php');
                } else {
                    array_push($errors, "Invalid credentials");
                }
            }
        } catch (Exception $th) {
            write_to_console($th);
            echo "Failed to register user first" . $th->getMessage();
        }
    }
}
?>

<?php if ($_SESSION['id']) : ?>
    <?php header("Location:dashboard.php") ?>
<?php else : ?>
    <?php
    include("./includes/header.php");
    ?>
    <!-- Google reCAPTCHA CDN -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <main class="min-h-[calc(100vh-100px)] flex flex-col justify-center">
        <div class="w-full bg-gray-50 flex flex-col sm:justify-center items-center sm:pt-0">
            <div class="w-full sm:max-w-md p-5 mx-auto">
                <h2 class="mb-12 text-center text-5xl font-extrabold">Login.</h2>
                <div class="form-group error-display">
                    <?php
                    if (isset($_POST["login"])) :
                    ?>
                        <?php
                        if (count($errors) > 0) {
                            echo '<p class="text-red-600 font-bold">Error with authenticatiion:</p> <br/>';
                            foreach ($errors as $error) {
                                echo '<p class="text-red-600">' . $error . "</p>" . '<br/>';
                            }
                        }
                        ?>
                    <?php endif; ?>
                </div>
                <form action="login.php" method="POST">
                    <div class="mb-4">
                        <label class="block mb-1" for="email">Email-Address</label>
                        <input id="email" placeholder="Please enter your email-address" type="text" name="email" class="py-2 px-3 border border-gray-300 focus:border-red-300 focus:outline-none focus:ring focus:ring-red-200 focus:ring-opacity-50 rounded-md shadow-sm disabled:bg-gray-100 mt-1 block w-full" />
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1" for="password">Password</label>
                        <input id="password" type="password" placeholder="Please enter your password" name="password" class="py-2 px-3 border border-gray-300 focus:border-red-300 focus:outline-none focus:ring focus:ring-red-200 focus:ring-opacity-50 rounded-md shadow-sm disabled:bg-gray-100 mt-1 block w-full" />
                    </div>

                    <!-- div to show reCAPTCHA -->
                    <div class="my-5 g-recaptcha" data-sitekey="6LeQ1IsgAAAAAJetG39_U_8322-A7Hzdyx7HSeux">
                    </div>
                    <br>

                    <div class="mt-6">
                        <input type="submit" name="login" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold capitalize text-white hover:bg-blue-700 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition" />
                    </div>
                    <div class="mt-6 text-center">
                        <a href="/www/itsec-project/register.php" class="underline">Sign up for an account</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <?php
    include("./includes/footer.php");
    ?>

<?php endif ?>
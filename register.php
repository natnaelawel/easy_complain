<?php
session_start();
require_once("./libs/utils.php");
require_once("./config/db.php");

$errors = []
?>

<?php
if (isset($_POST['register'])) {

    $fullname = stripslashes($_POST['fullname']);
    $fullname = htmlspecialchars($fullname);
    $email    = stripslashes($_POST['email']);
    $email    = htmlspecialchars($email);
    $password = stripslashes($_POST['password']);
    $password = htmlspecialchars($password);
    $confirm = stripslashes($_POST['confirm_password']);

    if (empty($fullname)) {
        array_push($errors, "Full Name is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is invalid");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    } elseif (strlen($password) < 8) {
        array_push($errors, "Password too short");
    } elseif (!preg_match("#[0-9]+#", $password)) {
        array_push($errors, "Password must include at least one number");
    } elseif (!preg_match("#[a-zA-Z]+#", $password)) {
        array_push($errors, "Password must include at least one letter");
    }

    if ($password != $confirm) {
        array_push($errors, "The two passwords do not match");
    }

    if (count($errors) == 0) {
        try {
            $user_check_query = "SELECT * FROM users WHERE email=:email LIMIT 1";
            $stmt = $conn->prepare($user_check_query);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            $count = $stmt->rowCount();
            write_to_console($count);
            if ($count > 0) {
                array_push($errors, "email already exists");
            }
        } catch (Exception $th) {
            write_to_console($th);
            echo "Failed to register user first" . $th->getMessage();
        }
    }


    // Finally, register user if there are no errors in the form
    if (count($errors) == 0) {
        try {

            $hash_default_salt = password_hash($password, PASSWORD_DEFAULT);
            // prepare and bind
            $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (:fullname, :email, :password)");
            $hash_default_salt ==
                $stmt->bindParam(":fullname", $fullname);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $hash_default_salt);
            $stmt->execute();

            $count = $stmt->rowCount();
            $row = $stmt->fetch();
            if ($count > 0) {
                header('Location:login.php');
            }
        } catch (Exception $th) {
            write_to_console($th);
            echo "Failed to register user" . $th->getMessage();
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
    <main class=" flex flex-col">
        <div class="w-full bg-gray-50 flex flex-col items-center pt-6 sm:pt-0 ">
            <div class="w-full sm:max-w-md p-5 mx-auto my-10">
                <h2 class="mb-12 text-center text-5xl font-extrabold">Welcome.</h2>
                <div class="form-group error-display">
                    <?php
                    if (isset($_POST["register"])) :
                    ?>
                        <?php
                        if (count($errors) > 0) {
                            echo '<p class="text-red-600 font-bold">Error with registration:</p> <br/>';
                            foreach ($errors as $error) {
                                echo '<p class="text-red-600">' . $error . "</p>" . '<br/>';
                            }
                        }
                        ?>
                    <?php endif; ?>
                </div>
                <form class="form" action="register.php" method="post">
                    <div class="mb-4">
                        <label class="block mb-1" for="fullname">Full Name</label>
                        <input id="fullname" autofocus="true" type="text" class="block border border-grey-light w-full p-3 rounded mb-4" name="fullname" placeholder="Full Name" />
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1" for="email">Email-Address</label>
                        <input id="email" placeholder="Please enter your email-address" type="text" name="email" class="py-2 px-3 border border-gray-300 focus:border-red-300 focus:outline-none focus:ring focus:ring-red-200 focus:ring-opacity-50 rounded-md shadow-sm disabled:bg-gray-100 mt-1 block w-full" />
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1" for="password">Password</label>
                        <input id="password" type="password" placeholder="Please enter your password" name="password" class="py-2 px-3 border border-gray-300 focus:border-red-300 focus:outline-none focus:ring focus:ring-red-200 focus:ring-opacity-50 rounded-md shadow-sm disabled:bg-gray-100 mt-1 block w-full" />
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1" for="password">Confirm</label>
                        <input id="password" name="confirm_password" placeholder="Confirm Password" type="password" class="py-2 px-3 border border-gray-300 focus:border-red-300 focus:outline-none focus:ring focus:ring-red-200 focus:ring-opacity-50 rounded-md shadow-sm disabled:bg-gray-100 mt-1 block w-full" />
                    </div>

                    <div class="mt-6">
                        <input type="submit" name="register" class="cursor-pointer w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold capitalize text-white hover:bg-blue-700 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition" />
                    </div>
                    <div class="text-grey-dark mt-6 text-center">
                        Already have an account?
                        <a class="no-underline border-b text-blue-500 border-blue text-lg" href="/www/itsec-project/login.php">
                            Log in
                        </a>.
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php
    include("./includes/footer.php");
    ?>

<?php endif ?>
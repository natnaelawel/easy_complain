<?php
include("./includes/auth_session.php");
require_once("./libs/utils.php");
require_once("./config/db.php");


if (!$isAdmin) {
    $_SESSION['msg'] = "You must be an admin";
    header("Location: dashboard.php");
    exit();
}

$errors = [];
?>


<?php
include("./includes/header.php");
?>


<?php
if (isset($_POST['moderator'])) {

    $id = stripslashes($_POST['user_id']);    // removes backslashes
    $id = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');

    try {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->rowCount();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($count < 1) {
            array_push($errors, "There is no user with a given id");
        } else {
            $status = $row["is_active"];

            $sql = "UPDATE users SET users.is_active = (users.is_active ^ 1) WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
        }
    } catch (Exception $th) {
        write_to_console($th);
        echo "Failed to update user" . $th->getMessage();
    }
}
?>

<?php
$sql = "SELECT * FROM users WHERE users.is_admin = 0";
$stmt = $conn->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>





<main class="min-h-[calc(100vh-100px)] flex flex-col items-center border">
    <div id="add_feedback_form" class="w-2/3 flex flex-col my-10">
        <?php
        if (isset($_POST["login"])) :
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
        <div class="text-gray-900 ">
            <div class="p-4 flex">
                <h1 class="text-3xl">
                    Users
                </h1>
            </div>
            <div class="px-3 py-4 flex justify-center">
                <table class="w-full text-md mb-4">
                    <tbody>
                        <tr class="border-b">
                            <th class="text-left p-3 px-5">Name</th>
                            <th class="text-left p-3 px-5">Email</th>
                            <th class="text-left p-3 px-5">Comment</th>
                            <th class="text-left p-3 px-5">Status</th>
                            <th></th>
                        </tr>
                        <?php

                        foreach ($users as $user) {
                            $id  =  $user["id"];
                            $fullname =  $user["fullname"];
                            $email =  $user["email"];
                            $status =  $user["is_active"];
                            $admin = $user["is_admin"];
                        ?>

                            <tr>
                                <td class="p-3 px-5">
                                    <p class="bg-transparent">
                                        <?php echo $fullname; ?>
                                    </p>
                                </td>
                                <td class="p-3 px-5">
                                    <p class="bg-transparent">
                                        <?php echo $email; ?>
                                    </p>
                                </td>
                                <td class="p-3 px-5">
                                    <p value="user.comment" class="bg-transparent border2" rows="5">
                                        <?php echo $email; ?>
                                    </p>
                                </td>

                                <td class="p-3 px-5">
                                    <form action="moderator.php" method="post">
                                        <input type="hidden" name="user_id" value=<?php echo $id ?> />
                                        <button type="submit" name="moderator" class='text-sm  text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline  <?php echo (($status == 1) ?  " bg-blue-500 hover:bg-blue-700 " : " bg-red-500 hover:bg-red-700 ") ?>'>
                                            <?php
                                            if ($status == 1) :
                                            ?>
                                                active
                                            <?php else : ?>
                                                inactive
                                            <?php endif; ?>
                                        </button>
                                    </form>

                                    <!-- <button type="button" class="text-sm bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">Delete</button> -->
                                </td>

                                <td> </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>



<?php
include("../includes/footer.php");
?>
<?php

include("./includes/auth_session.php");
require_once("./libs/utils.php");
require_once("./config/db.php");
require_once "./libs/csrf_token.php";

?>

<?php require_once('./includes/header.php') ?>



<main class="w-full flex justify-center py-10">
    <?php if ($isAdmin) : ?>
        <?php
        $id = $_SESSION['id'];
        $sql = "SELECT * FROM feedbacks ORDER BY created_at DESC;";
        $stmt = $conn->query($sql);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <table class="w-2/3 text-md mb-4">
            <tbody>
                <tr class="border-b">
                    <th class="text-left p-3 px-5">Fullname</th>
                    <th class="text-left p-3 px-5">Email</th>
                    <th class="text-left p-3 px-5">Comment</th>
                    <th class="text-left p-3 px-5">File</th>
                    <th class="text-left p-3 px-5">Action</th>
                    <th></th>
                </tr>
                <?php
                foreach ($row as $feedback) {
                    $id  =  $feedback["id"];
                    $comment =  $feedback["comment"];
                    $file_path =  $feedback["file_path"];
                    $user_id =  $feedback["user_id"];
                    $created_at = $feedback["created_at"];

                    $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(":id", $user_id);
                    $stmt->execute();
                    $user_row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $email =  $user_row["email"];
                    $fullname =  $user_row["fullname"];

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
                            <p class="bg-transparent border2">
                                <?php echo $comment; ?>
                            </p>
                        </td>

                        <td class="p-3 px-5 ">
                            <a href='files.php?filename=<?php echo $file_path ?>' target="_blank" rel="noopener noreferrer">
                                pdf link
                            </a>
                        </td>

                        <td class="p-3 px-5 ">
                            <a class="text-white px-5 py-2 rounded-lg bg-blue-600" href='update.php?id=<?php echo $id ?>' rel="noopener noreferrer">
                                edit
                            </a>
                        </td>

                        <td> </td>
                    </tr>
                <?php
                }
                ?>

            </tbody>
        </table>
    <?php else : ?>
        <div class="w-1/2 my-10">
            <div class="flex justify-end mb-5">
                <button data-test-id="add_review_button" class="focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-700 transition duration-150 ease-in-out hover:bg-indigo-600 bg-indigo-700 disabled:bg-indigo-400  disabled:cursor-not-allowed rounded text-white px-10 py-3 text-sm flex">
                    <a href="feedback.php">
                        Add Feedback
                    </a>
                </button>
            </div>
            <?php
            $id = $_SESSION['id'];
            $sql = "SELECT * FROM feedbacks WHERE user_id = :id ORDER BY created_at DESC;";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <table class="w-full text-md">
                <tbody>
                    <tr class="border-b">
                        <th class="text-left p-3 px-5">Comment</th>
                        <th class="text-left p-3 px-5">Status</th>
                        <th></th>
                    </tr>
                    <?php
                    foreach ($row as $feedback) {
                        $id  =  $feedback["id"];
                        $comment =  $feedback["comment"];
                        $file_path =  $feedback["file_path"];
                        $user_id =  $feedback["user_id"];
                        $created_at = $feedback["created_at"];
                    ?>

                        <tr>
                            <td class="p-3 px-5">
                                <p class="bg-transparent border2">
                                    <?php echo $comment; ?>
                                </p>
                            </td>

                            <td class="p-3 px-5 ">
                                <a class="text-blue-500" href='files.php?filename=<?php echo $file_path ?>' target="_blank" rel="noopener noreferrer">
                                    pdf link
                                </a>
                            </td>

                            <td class="p-3 px-5 ">
                                <a class="text-white px-5 py-2 rounded-lg bg-blue-600" href='update_feedback.php?id=<?php echo $id ?>' rel="noopener noreferrer">
                                    edit
                                </a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>

                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>



<?php
include("./includes/footer.php");
?>
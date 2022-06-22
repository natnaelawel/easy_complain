<?php
include("./includes/auth_session.php");
require_once("./libs/utils.php");
require_once("./config/db.php");
require_once "./libs/csrf_token.php";
error_reporting(E_ALL);
ini_set("display_errors", 1);

?>

<?php
include("./includes/header.php");

$errors = []
?>
<?php


if (isset($_FILES['formFile'])) {
    $comment = $_POST['comment'];
    $comment = stripslashes($comment);    // removes backslashes
    $comment = htmlspecialchars($comment);

    if (empty($comment)) {
        array_push($errors, "Comment is required");
    }

    if (csrf_token_is_valid()) {
        write_to_console("heelloow");
        if (count($errors) == 0) {
            $uploadok = 1;
            $file_name = $_FILES['formFile']['name'];
            $file_size = $_FILES['formFile']['size'];
            $file_tmp = $_FILES['formFile']['tmp_name'];
            $file_type = $_FILES['formFile']['type'];
            $target_dir = "assets/uploads";
            $target_file = $target_dir . basename($_FILES['formFile']['name']);

            $file_ext = strtolower(end(explode(
                '.',
                $_FILES['formFile']['name']
            )));

            $extensions = array("pdf");

            if (in_array($file_ext, $extensions) == false) {
                array_push($errors, "Please upload only a pdf file");
            } else if (file_exists($target_file)) {
                array_push($errors, "Sorry file already exists");
            }

            if (count($errors) == 0) {
                try {
                    $store_path =  "assets/uploads/" . $file_name;

                    move_uploaded_file($file_tmp, $store_path);
                    $url = $_SERVER['HTTP_REFERER'];
                    $seg = explode('/', $url);
                    $path = $seg[0] . '/' . $seg[1] . '/' . $seg[2] . '/' . $seg[3];
                    $file_path =  $file_name;
                    $user_id = $_SESSION['id'];
                    $sql = "INSERT INTO feedbacks (comment, file_path, user_id) VALUES (:comment, :file_path, :user_id)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(":comment", $comment, PDO::PARAM_STR);
                    $stmt->bindParam(":file_path", $file_path, PDO::PARAM_STR);
                    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
                    $stmt->execute();

                    header("Location:dashboard.php");
                } catch (Exception $e) {
                    echo $e;
                    array_push($errors, "Failed to upload comment");
                }
            }
        }
    } else {
        array_push($errors, "CSRF token missing");
    }
}
?>

<main class="min-h-[calc(100vh-100px)] flex flex-col justify-center items-center border">
    <div class="flex flex-col w-1/2">
        <div class="flex">
            <h2 class="text-4xl font-bold">Add Feedback</h2>
        </div>
        <div id="add_feedback_form" class="flex flex-col">
            <form action="feedback.php" method="post" enctype="multipart/form-data">
                <div class="form-group error-display">
                    <?php
                    if (isset($_POST["feedback"])) :
                    ?>
                        <?php
                        if (count($errors) > 0) {
                            echo '<p class="text-red-600 font-bold">Error with adding feedback:</p> <br/>';
                            foreach ($errors as $error) {
                                echo '<p class="text-red-600">' . $error . "</p>" . '<br/>';
                            }
                        }
                        ?>
                    <?php endif; ?>
                </div>
                <input type="hidden" name="csrf_token" value=<?php echo $_SESSION['csrf_token']; ?> />
                <input type="hidden" name="session_id" value=<?php echo $_SESSION['id'] ?> />
                <div class="my-4">
                    <div class="my-5">
                        <label class="block mb-1" for="comment">Comment</label>
                        <textarea placeholder="Please feedback comment" id="comment" name="comment" rows="5" class="px-5 py-3 border border-gray-300 focus:border-red-300 focus:outline-none focus:ring focus:ring-red-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 w-full"></textarea>
                    </div>
                    <div class="my-5">
                        <div class="overflow-hidden relative mt-4 mb-4">
                            <label class="block">
                                <span class="sr-only">Choose file</span>
                                <input type="file" name="formFile" accept="application/pdf" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 hover:file:text-violet-800" />
                            </label>
                        </div>
                    </div>
                </div>
                <div class="my-5">
                    <div class="flex items-center justify-start w-full">
                        <button type="submit" name="feedback" data-test-id="add_event_button" class="focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-700 transition duration-150 ease-in-out hover:bg-indigo-600 bg-indigo-700 disabled:bg-indigo-400  disabled:cursor-not-allowed rounded text-white px-10 py-3 text-sm flex">
                            Submit
                        </button>

                    </div>
            </form>
        </div>
    </div>
</main>


<?php
include("./includes/footer.php");
?>
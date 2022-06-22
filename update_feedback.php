<?php
include("./includes/auth_session.php");
require_once("./libs/utils.php");
require_once("./config/db.php");
require_once "./libs/csrf_token.php"
?>

<?php
include("./includes/header.php");

$errors = []
?>
<?php

if (isset($_FILES['formFile'])) {
    $feedback_id = $_POST['id'];
    $session_id = $_POST['session_id'];
    $user_id = $_SESSION["id"];

    if ($session_id != $user_id) {
        array_push($errors, "You have tried to hack us!");
    }

    $comment = $_POST['comment'];
    $file = $_POST['formFile'];
    $token = $_POST['csrf_token'];

    $feedback_id = htmlspecialchars($feedback_id, ENT_QUOTES, 'UTF-8');
    $comment = htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');
    $file = htmlspecialchars($file, ENT_QUOTES, 'UTF-8');
    $token = htmlspecialchars($token, ENT_QUOTES, 'UTF-8');

    if (!$token || $token !== $_SESSION['csrf_token']) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        exit;
    }
    if (empty($comment)) {
        array_push($errors, "Comment is required");
    }
    if (count($errors) == 0) {
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
        }
        if (file_exists($target_file)) {
            array_push($errors, "Sorry file already exists");
        }
        if (count($errors) == 0) {
            try {
                move_uploaded_file($file_tmp, "assets/uploads/" . $file_name);
                $url = $_SERVER['HTTP_REFERER'];
                $seg = explode('/', $url);
                $path = $seg[0] . '/' . $seg[1] . '/' . $seg[2] . '/' . $seg[3];
                $image_path = explode('/', $file);
                $image = $image_path[6];
                $full_url =  $path . '/' . 'assets/uploads/' . $file_name;

                $id = $_SESSION['id'];
                $sql = "UPDATE feedbacks SET comment = :comment, file_path = :full_url WHERE id = :feedback_id";
                if ($file_name != $image) {
                    unlink("assets/uploads/" . $image);
                }
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(":comment", $comment);
                $stmt->bindParam(":full_url", $full_url);
                $stmt->bindParam(":feedback_id", $feedback_id);
                $stmt->execute();

                header("Location:dashboard.php");
            } catch (Exception $e) {
                array_push($errors, "Failed to upload comment");
            }
        }
    }
}
?>


<?php
if (isset($_GET['id'])) {

    $id = $_GET['id'];
    $id = stripslashes($id);
    $id = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');
    $sql = "SELECT * FROM feedbacks WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    $stmt->execute();
    $count = $stmt->rowCount();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($count > 0) {
        $comment =  $row["comment"];
        $file_path =  $row["file_path"];
        $user_id =  $row["user_id"];
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>


<main class="min-h-[calc(100vh-100px)] flex flex-col justify-center items-center border">
    <div class="flex flex-col w-1/2">
        <div class="flex">
            <h2 class="text-4xl font-bold">Update Feedback</h2>
        </div>
        <div id="update_feedback_form" class="flex flex-col">
            <form action="update_feedback.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value=<?php echo $id; ?> />
                <input type="hidden" name="file" value=<?php echo $file_path; ?> />
                <input type="hidden" name="session_id" value=<?php echo $_SESSION["id"]; ?> />
                <input type="hidden" name="csrf_token" value=<?php echo $_SESSION['csrf_token']; ?> />


                <div class="my-4">
                    <div class="my-5">
                        <label class="block mb-1" for="comment">Comment</label>
                        <textarea placeholder="Please add comment" id="comment" name="comment" rows="5" class="px-5 py-3 border border-gray-300 focus:border-red-300 focus:outline-none focus:ring focus:ring-red-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 w-full"><?php echo $comment; ?></textarea>
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
                        <button type="submit" name="feedback" data-test-id="edit_button" class="focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-700 transition duration-150 ease-in-out hover:bg-indigo-600 bg-indigo-700 disabled:bg-indigo-400  disabled:cursor-not-allowed rounded text-white px-10 py-3 text-sm flex">
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
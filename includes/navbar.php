<?php
$stored_token = $_SESSION['csrf_token'];

?>

<div class="dark:bg-gray-900">
    <div>
        <div class="relative">
            <div class="dark:bg-gray-900 bg-gray-50 px-6 py-9">
                <div class="container mx-auto flex items-center justify-between">
                    <div class="md:w-4/12 cursor-pointer text-gray-800 dark:text-white" aria-label="the Crib.">
                        <h1 class="text-xl font-bold">
                            <a href="dashboard.php">
                                Easy Complaint System
                            </a>
                        </h1>
                    </div>
                    <ul class="hidden w-8/12 md:flex items-center justify-center space-x-8">
                        <li>
                            <a href="/www/itsec-project/dashboard.php" class="dark:text-white text-base text-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800 hover:underline">Dashboard</a>
                        </li>

                        <?php
                        if ($isLoggedIn && !$isAdmin) :
                        ?>
                            <li>
                                <a href="/www/itsec-project/feedback.php" class="dark:text-white text-base text-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800 hover:underline">Feedback</a>
                            </li>
                        <?php endif; ?>
                        <?php
                        if ($isAdmin) :
                        ?>
                            <li>
                                <a href="/www/itsec-project/moderator.php" class="dark:text-white text-base text-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800 hover:underline">Moderator</a>
                            </li>
                        <?php endif; ?>
                        <?php
                        if ($isLoggedIn) :
                        ?>
                            <li>
                                <a href="/www/itsec-project/logout.php" class="dark:text-white text-base text-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800 hover:underline">Logout</a>
                            </li>
                        <?php else : ?>
                            <li>
                                <a href="/www/itsec-project/login.php" class="dark:text-white text-base text-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800 hover:underline">Sign in</a>
                            </li>
                        <?php endif; ?>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
function register_user(string $email, string $fullname, string $password, bool $is_admin = false): bool
{
$sql = 'INSERT INTO users(fullname, email, password, is_admin)
VALUES(:fullname, :email, :password, :is_admin)';

$statement = db()->prepare($sql);

$statement->bindValue(':fullname', $fullname, PDO::PARAM_STR);
$statement->bindValue(':email', $email, PDO::PARAM_STR);
$statement->bindValue(':password', password_hash($password, PASSWORD_BCRYPT), PDO::PARAM_STR);
$statement->bindValue(':is_admin', (int)$is_admin, PDO::PARAM_INT);


return $statement->execute();
}
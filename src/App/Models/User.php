<?php

declare(strict_types=1);

namespace App\Models;

use Framework\Model;
use PDO;


class User extends Model
{

    protected $table = "user";



    public function findUserByEmail($email)
    {
        $pdo = $this->database->getConnection();

        $sql = "select * from {$this->getTable()} where email = :email";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":email", $email, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch();
    }

    public function findUserByActivationHash($token_hash)
    {
        $pdo = $this->database->getConnection();

        $sql = "select * from {$this->getTable()} where activation_hash = :activation_hash";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":activation_hash", $token_hash, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch();
    }

    public function activateAccount($user)
    {

        $pdo = $this->database->getConnection();

        $sql = "update user set activation_hash = null, is_active = 1 where id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":id", $user['id'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function startPasswordReset($user)
    {
        $pdo = $this->database->getConnection();

        $sql = "update user set password_reset_hash = :reset_hash, password_reset_expiry = :reset_expiry where id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":reset_hash", $user['reset_hash'], PDO::PARAM_STR);
        $stmt->bindValue(":reset_expiry", date('Y-m-d H:i:s', $user['reset_expiry']), PDO::PARAM_STR);
        $stmt->bindValue(":id", $user['id'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function findUserByResetHash($reset_hash)
    {
        $pdo = $this->database->getConnection();

        $sql = "select * from {$this->getTable()} where password_reset_hash = :reset_hash";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":reset_hash", $reset_hash, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch();
    }

    public function updatePassword($user)
    {
        $pdo = $this->database->getConnection();

        $sql = "update {$this->getTable()} set password_hash = :password_hash, 
        password_reset_hash = null,
        password_reset_expiry = null
        where id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":password_hash", $user['password_hash'], PDO::PARAM_STR);
        $stmt->bindValue(":id", $user['id'], PDO::PARAM_INT);

        return $stmt->execute();
    }
}

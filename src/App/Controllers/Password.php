<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use App\Token;
use App\Email;
use App\Flash;
use Framework\Controller;
use Framework\Validate;


class Password extends Controller
{

    public function __construct(private User $model) {}

    public function forgot()
    {
        return $this->view("Password/forgot.php");
    }

    public function start()
    {
        $email = $this->request->post['email'];

        $user = $this->model->findUserByEmail($email);

        if (!$user) {
            return $this->redirect("/password/check-email");
        }

        $token = new Token();

        $reset_hash = $token->getHash();

        $reset_token = $token->getValue();

        $expiry_timestamp = time() + 60 * 60 * 2;

        $user['reset_hash'] = $reset_hash;

        $user['reset_expiry'] = $expiry_timestamp;

        $this->model->startPasswordReset($user);

        $this->sendPasswordResetEmail($user['email'], $reset_token);

        return $this->redirect("/password/check-email");
    }

    private function sendPasswordResetEmail(string $email, string $token)
    {

        $url = $_ENV['APP_URL'] . "/password/get-new-password/?token=" . $token;

        $subject = "Reset Password";

        $html = $this->emailView("Password/reset-email.html", ["url" => $url]);

        $text = $this->emailView("Password/reset-email.txt", ["url" => $url]);

        return Email::send($subject, $email, $html, $text);
    }

    public function getNewPassword()
    {
        $token_value = $this->request->get['token'] ?? null;

        if ($token_value === null) {

            Flash::addMessage("Token not found. Please try again");
            return $this->redirect("/password/forgot");
        }

        return $this->view("Password/new-password.php", ["token" => $token_value]);
    }

    public function complete()
    {
        $data = $this->request->post;

        if (!Validate::string($data['password'], 6, 256)) {
            $this->addError("password", "Password must be 6 characters minimum");
        }

        if (!Validate::password_confirmation($data['password'], $data['confirm_password'])) {
            $this->addError("password", "Passwords do not match");
        }

        if (!empty($this->errors)) {

            $_SESSION['password_reset_token'] = $data['token'];
            $_SESSION['password_reset_errors'] = $this->errors;

            return $this->redirect("/password/show-with-errors");
        }

        $token = new Token($data['token']);

        $reset_hash = $token->getHash();

        $user = $this->model->findUserByResetHash($reset_hash);

        if (!$user) {

            Flash::addMessage("Token not found. Please try again");
            return $this->redirect("/password/forgot");
        }

        if (strtotime($user['password_reset_expiry']) < time()) {

            Flash::addMessage("Token has expired. Please try again");
            return $this->redirect("/password/forgot");
        }

        $user['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $this->model->updatePassword($user);

        return $this->redirect("/login");
    }

    public function checkEmail()
    {
        return $this->view("Password/check-email.php");
    }

    public function showWithErrors()
    {

        $token = $_SESSION['password_reset_token'] ?? '';
        $errors = $_SESSION['password_reset_errors'] ?? [];

        unset($_SESSION['password_reset_token']);
        unset($_SESSION['password_reset_errors']);

        return $this->view("Password/new-password.php", ["token" => $token, "errors" => $errors]);
    }
}

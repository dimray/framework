<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Email;
use Framework\Controller;
use Framework\Validate;
use App\Models\User;
use App\Token;
use Exception;

class Register extends Controller
{
    public function __construct(private User $model) {}

    public function new()
    {
        return $this->view("Register/new.php");
    }

    public function create()
    {

        $data = $this->request->post;

        if (time() - $data['start_time'] < 5) {
            return $this->redirect("/register/spam");
        }

        if (!empty($data['phone'])) {
            return $this->redirect("/register/spam");
        }

        unset($data['start_time']);
        unset($data['phone']);

        if (!Validate::string($data['name'], 1, 70)) {
            $this->addError("name", "Name is required");
        }

        if (!Validate::email($data['email'])) {
            $this->addError("email", "Email is not in the correct format");
        }

        if (!Validate::string($data['password'], 6, 256)) {
            $this->addError("password", "Password must be 6 characters minimum");
        }

        if (!Validate::password_confirmation($data['password'], $data['confirm_password'])) {
            $this->addError("password", "Passwords do not match");
        }

        $user = $this->model->findUserByEmail($data['email']);

        if ($user) {

            if (!$user['is_active']) {

                $this->model->delete($user['id']);
            } else {

                $this->addError("duplicate", "Email address is already registered");
            }
        }

        if (!empty($this->errors)) {

            $_SESSION['registration_data'] = $data;
            $_SESSION['registration_errors'] = $this->errors;

            return $this->redirect("/register/show-with-errors");
        }

        $token = new Token();

        $activation_hash = $token->getHash();

        $token_value = $token->getValue();

        $data['activation_hash'] = $activation_hash;

        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);

        unset($data['password']);

        unset($data['confirm_password']);

        $this->model->insert($data);

        $this->sendActivationEmail($data['email'], $token_value);

        return $this->redirect("/register/check-email");
    }

    public function showWithErrors()
    {
        $data =  $_SESSION['registration_data'] ?? [];
        $errors = $_SESSION['registration_errors'] ?? [];

        unset($_SESSION['registration_data']);
        unset($_SESSION['registration_errors']);

        return $this->view("Register/new.php", ["data" => $data, "errors" => $errors]);
    }

    public function checkEmail()
    {
        return $this->view("Register/check-email.php");
    }

    private function sendActivationEmail(string $email, string $token)
    {

        $url = $_ENV['APP_URL'] . "/register/activate-account/?token=" . $token;

        $subject = "Activate Account";

        $html = $this->emailView("Register/activation-email.html", ["url" => $url]);

        $text = $this->emailView("Register/activation-email.txt", ["url" => $url]);

        return Email::send($subject, $email, $html, $text);
    }

    public function activateAccount()
    {
        $token_value = $this->request->get['token'] ?? null;

        if ($token_value === null) {

            return $this->view("Register/failed.php");
        }

        $token = new Token($token_value);

        $hash_value = $token->gethash();

        $user = $this->model->findUserByActivationHash($hash_value);

        if ($user === false) {

            return $this->view("Register/failed.php");
        }

        if ($this->model->activateAccount($user)) {

            return $this->view("Register/success.php");
        } else {

            return $this->view("Register/failed.php");
        }
    }

    public function spam()
    {
        return $this->view("Register/spam.php");
    }
}

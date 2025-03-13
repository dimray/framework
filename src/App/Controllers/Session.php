<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use Framework\Controller;
use Framework\Validate;

class Session extends Controller
{
    public function __construct(private User $model) {}

    public function new()
    {
        return $this->view("Session/new.php");
    }

    public function create()
    {
        $data = $this->request->post;

        if (!Validate::email($data['email'])) {
            $this->addError("email", "Email is not in the correct format");
        }

        if (!Validate::string($data['email'])) {
            $this->addError("email", "Email is required");
        }

        if (!Validate::string($data['password'])) {
            $this->addError("password", "Password is required");
        }

        if (!empty($this->errors)) {

            return $this->showErrors($data);
        }

        $user = $this->model->findUserByEmail($data['email']);

        if ($user === false) {

            $this->addError("email", "The combination of email and password is not recognised");

            return $this->showErrors($data);
        }

        if (!$user['is_active']) {
            $this->addError("email", "Account has not been activated. Check your email for your activation code, or register again.");

            return $this->showErrors($data);
        }

        if (password_verify($data['password'], $user['password_hash'])) {

            session_regenerate_id(true);

            $redirect = $_SESSION['redirect'] ?? "/";

            $_SESSION['user_id'] = $user['id'];

            $_SESSION['user_name'] = $user['name'];

            return $this->redirect($redirect);
        } else {

            $this->addError("email", "The combination of email and password is not recognised");

            return $this->showErrors($data);
        }
    }

    private function showErrors($data)
    {

        $_SESSION['login_data'] = $data;
        $_SESSION['login_errors'] = $this->errors;

        return $this->redirect("/session/show-with-errors");
    }

    public function showWithErrors()
    {

        $data = $_SESSION['login_data'] ?? [];
        $errors = $_SESSION['login_errors'] ?? [];

        unset($_SESSION['login_data']);
        unset($_SESSION['login_errors']);

        return $this->view("Session/new.php", ["data" => $data, "errors" => $errors]);
    }

    public function destroy()
    {
        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();

        return $this->redirect("/");
    }
}

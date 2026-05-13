<?php
require_once __DIR__ . '/../models/User.php';

class UserController
{
    public static function register(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';

        if ($name === '' || $email === '' || $password === '') {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Заполните все поля.']);
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Некорректный email.']);
            return;
        }
        if (strlen($password) < 6) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Пароль должен быть не менее 6 символов.']);
            return;
        }
        if (User::findByEmail($email)) {
            http_response_code(409);
            echo json_encode(['status' => 'error', 'message' => 'Пользователь с таким email уже зарегистрирован.']);
            return;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $userId = User::create($name, $email, $hash);
        http_response_code(201);
        echo json_encode([
            'status' => 'success',
            'message' => 'Пользователь зарегистрирован.',
            'user_id' => $userId
        ]);
    }

    public static function login(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';

        if ($email === '' || $password === '') {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Заполните все поля.']);
            return;
        }

        $user = User::findByEmail($email);
        if (!$user) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Неверный email или пароль.']);
            return;
        }
        if (!password_verify($password, $user['password_hash'])) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Неверный email или пароль.']);
            return;
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Авторизация успешна.',
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ]
        ]);
    }

    public static function getUsers(): void
    {
        $users = User::getAll();
        echo json_encode(['status' => 'success', 'data' => $users]);
    }

    public static function getUser(int $id): void
    {
        $user = User::findById($id);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден.']);
            return;
        }
        echo json_encode(['status' => 'success', 'data' => $user]);
    }

    public static function updatePassword(int $id): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $newPassword = $input['password'] ?? '';
        if (strlen($newPassword) < 6) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Пароль должен быть не менее 6 символов.']);
            return;
        }

        $user = User::findById($id);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден.']);
            return;
        }

        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        User::updatePassword($id, $hash);
        echo json_encode(['status' => 'success', 'message' => 'Пароль обновлён.']);
    }

    public static function deleteUser(int $id): void
    {
        if (!User::findById($id)) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден.']);
            return;
        }
        User::delete($id);
        echo json_encode(['status' => 'success', 'message' => 'Пользователь удалён.']);
    }
}
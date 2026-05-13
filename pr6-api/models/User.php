<?php
require_once __DIR__ . '/../config/database.php';

class User
{
    public static function create(string $name, string $email, string $passwordHash): int
    {
        $db = getDB();
        $stmt = $db->prepare('INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :pass)');
        $stmt->execute(['name' => $name, 'email' => $email, 'pass' => $passwordHash]);
        return (int) $db->lastInsertId('users_id_seq');
    }

    public static function findByEmail(string $email): ?array
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function findById(int $id): ?array
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT id, name, email FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function getAll(): array
    {
        $db = getDB();
        $stmt = $db->query('SELECT id, name, email FROM users ORDER BY id');
        return $stmt->fetchAll();
    }

    public static function updatePassword(int $id, string $passwordHash): bool
    {
        $db = getDB();
        $stmt = $db->prepare('UPDATE users SET password_hash = :pass WHERE id = :id');
        $stmt->execute(['pass' => $passwordHash, 'id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public static function delete(int $id): bool
    {
        $db = getDB();
        $stmt = $db->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }
}
<?php

namespace App\models;

use App\core\Model;
use PDO;

class Screen extends Model
{
    public function getScreensByUser(int $userId)
    {
        $sql = "SELECT s.*
                FROM users u
                JOIN user_screens us USING (user_id)
                JOIN screens s USING (screen_id)
                WHERE u.user_id = ?
                ORDER BY s.screen_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

<?php

namespace App\models;

use App\core\Model;
use PDO;

class Tela extends Model
{
    public function getTelasPorUsuario(int $usuarioId)
    {
        $sql = "SELECT t.*
                FROM usuario u
                JOIN usuario_tela ut USING (usuario_id)
                JOIN tela t USING (tela_id)
                WHERE u.usuario_id = ?
                ORDER BY t.tela_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$usuarioId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

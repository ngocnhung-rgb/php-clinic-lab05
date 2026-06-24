<?php
namespace App\Repositories;

use PDO;
use PDOException;
use App\Core\DuplicateRecordException;

class LeadRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function countAll(string $keyword = ''): int
    {
        $sql = "SELECT COUNT(*) FROM leads";
        $params = [];

        if ($keyword !== '') {
            $sql .= " WHERE name LIKE :keyword1 OR email LIKE :keyword2 OR phone LIKE :keyword3";
            $params = [
                'keyword1' => "%{$keyword}%",
                'keyword2' => "%{$keyword}%",
                'keyword3' => "%{$keyword}%"
            ];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getPaginated(string $keyword, int $limit, int $offset, string $sort, string $direction): array
    {
        $allowedSorts = ['id', 'name', 'email', 'phone', 'status', 'created_at'];
        $allowedDirections = ['asc', 'desc'];

        $sort = in_array($sort, $allowedSorts, true) ? $sort : 'created_at';
        $direction = in_array(strtolower($direction), $allowedDirections, true) ? $direction : 'desc';

        $params = [];
        $whereSql = "";

        if ($keyword !== '') {
            $whereSql = " WHERE name LIKE :keyword1 OR email LIKE :keyword2 OR phone LIKE :keyword3 ";
            $params = [
                'keyword1' => "%{$keyword}%",
                'keyword2' => "%{$keyword}%",
                'keyword3' => "%{$keyword}%"
            ];
        }

        $sql = "SELECT id, name, email, phone, status, created_at 
                FROM leads {$whereSql} 
                ORDER BY {$sort} {$direction} 
                LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM leads WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO leads (name, email, phone, status, note)
                VALUES (:name, :email, :phone, :status, :note)";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'name'   => $data['name'],
                'email'  => $data['email'],
                'phone'  => $data['phone'] ?? null,
                'status' => $data['status'] ?? 'new',
                'note'   => $data['note'] ?? null,
            ]);
        } catch (PDOException $e) {
            // Kiểm tra lỗi 1062 (Duplicate entry)
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new DuplicateRecordException('Email hoặc Số điện thoại đã tồn tại trong hệ thống.');
            }
            throw $e;
        }
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE leads SET name = :name, email = :email, phone = :phone,
                status = :status, note = :note, updated_at = NOW() WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id'     => $id,
                'name'   => $data['name'],
                'email'  => $data['email'],
                'phone'  => $data['phone'] ?? null,
                'status' => $data['status'],
                'note'   => $data['note'] ?? null,
            ]);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new DuplicateRecordException('Email hoặc Số điện thoại đã được sử dụng bởi hồ sơ khác.');
            }
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM leads WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
<?php
namespace App\Repositories;

use PDO;
use PDOException;
use App\Core\DuplicateRecordException;

class OrderRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function countAll(string $keyword = ''): int
    {
        $sql = "SELECT COUNT(*) FROM orders";
        $params = [];

        if ($keyword !== '') {
            $sql .= " WHERE order_code LIKE :keyword1 OR customer_name LIKE :keyword2";
            $params = [
                'keyword1' => "%{$keyword}%",
                'keyword2' => "%{$keyword}%"
            ];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO orders (order_code, customer_name, customer_email, total_amount, status, appointment_date)
                VALUES (:order_code, :customer_name, :customer_email, :total_amount, :status, :appointment_date)";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'order_code'       => $data['order_code'],
                'customer_name'    => $data['customer_name'],
                'customer_email'   => $data['customer_email'] ?? null,
                'total_amount'     => $data['total_amount'],
                'status'           => $data['status'],
                'appointment_date' => $data['appointment_date'],
            ]);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new DuplicateRecordException('Mã lịch hẹn này đã tồn tại.');
            }
            throw $e;
        }
    }

    public function getPaginated(string $keyword, int $limit, int $offset, string $sort, string $direction): array
    {
        $allowedSorts = ['id', 'order_code', 'customer_name', 'status', 'created_at', 'appointment_date'];
        $allowedDirections = ['asc', 'desc'];

        $sort = in_array($sort, $allowedSorts, true) ? $sort : 'created_at';
        $direction = in_array(strtolower($direction), $allowedDirections, true) ? $direction : 'desc';

        $params = [];
        $whereSql = "";
        
        if ($keyword !== '') {
            $whereSql = " WHERE order_code LIKE :keyword1 OR customer_name LIKE :keyword2 ";
            $params = ['keyword1' => "%{$keyword}%", 'keyword2' => "%{$keyword}%"];
        }

        $sql = "SELECT id, order_code, customer_name, customer_email, total_amount, status, appointment_date, created_at 
                FROM orders {$whereSql} 
                ORDER BY {$sort} {$direction} 
                LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE orders 
                SET order_code = :order_code, 
                    customer_name = :customer_name, 
                    customer_email = :customer_email, 
                    total_amount = :total_amount, 
                    status = :status,
                    appointment_date = :appointment_date,
                    updated_at = NOW()
                WHERE id = :id";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id'               => $id,
                'order_code'       => $data['order_code'],
                'customer_name'    => $data['customer_name'],
                'customer_email'   => $data['customer_email'] ?: null,
                'total_amount'     => (float)($data['total_amount'] ?? 0),
                'status'           => $data['status'],
                'appointment_date' => $data['appointment_date'],
            ]);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new DuplicateRecordException('Mã lịch hẹn này đã tồn tại.');
            }
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM orders WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
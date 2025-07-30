<?php
namespace App\Models;

use CodeIgniter\Model;

class MultiDBModel extends Model
{
    protected $db;

    public function __construct(array $options = [])
    {
        parent::__construct();

        // Pastikan 'dbGroup' dikirim dari controller dan digunakan di sini
        $dbGroup = $options['dbGroup'] ?? config('Database')->defaultGroup;

        // Set koneksi database sesuai group
        $this->db = \Config\Database::connect($dbGroup);
    }

    public function transactionsp(string $procedure, array $params = [])
    {
        $placeholders = implode(', ', array_fill(0, count($params), '?'));
        $sql = "EXEC $procedure $placeholders";

        $query = $this->db->query($sql, $params);

        return $query->getResultArray();
    }
}



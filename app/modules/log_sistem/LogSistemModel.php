<?php

class LogSistemModel {
    private $db;

    public function __construct() {
        if (!class_exists('Database')) {
            require_once dirname(__DIR__, 2) . '/config/Database.php';
        }
        $this->db = new Database();
    }

    /**
     * Ambil data log dengan filter & pagination
     */
    public function getLogs($filters = [], $limit = 50, $offset = 0) {
        $where = ["1=1"];

        if (!empty($filters['level'])) {
            $level = addslashes($filters['level']);
            $where[] = "level = '{$level}'";
        }

        if (!empty($filters['category'])) {
            $category = addslashes($filters['category']);
            $where[] = "category = '{$category}'";
        }

        if (!empty($filters['username'])) {
            $username = addslashes($filters['username']);
            $where[] = "username = '{$username}'";
        }

        if (!empty($filters['search'])) {
            $search = addslashes($filters['search']);
            $where[] = "(action LIKE '%{$search}%' OR description LIKE '%{$search}%' OR context LIKE '%{$search}%' OR url LIKE '%{$search}%')";
        }

        if (!empty($filters['date_from'])) {
            $from = addslashes($filters['date_from']) . " 00:00:00";
            $where[] = "created_at >= '{$from}'";
        }

        if (!empty($filters['date_to'])) {
            $to = addslashes($filters['date_to']) . " 23:59:59";
            $where[] = "created_at <= '{$to}'";
        }

        $whereClause = implode(" AND ", $where);
        $limit = (int)$limit;
        $offset = (int)$offset;

        $sql = "SELECT * FROM tbl_logs WHERE {$whereClause} ORDER BY id DESC LIMIT {$offset}, {$limit}";
        $res = $this->db->query($sql);
        $data = [];
        if ($res) {
            while ($row = $res->FetchRow()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    /**
     * Hitung total log yang cocok dengan filter
     */
    public function countLogs($filters = []) {
        $where = ["1=1"];

        if (!empty($filters['level'])) {
            $level = addslashes($filters['level']);
            $where[] = "level = '{$level}'";
        }

        if (!empty($filters['category'])) {
            $category = addslashes($filters['category']);
            $where[] = "category = '{$category}'";
        }

        if (!empty($filters['username'])) {
            $username = addslashes($filters['username']);
            $where[] = "username = '{$username}'";
        }

        if (!empty($filters['search'])) {
            $search = addslashes($filters['search']);
            $where[] = "(action LIKE '%{$search}%' OR description LIKE '%{$search}%' OR context LIKE '%{$search}%' OR url LIKE '%{$search}%')";
        }

        if (!empty($filters['date_from'])) {
            $from = addslashes($filters['date_from']) . " 00:00:00";
            $where[] = "created_at >= '{$from}'";
        }

        if (!empty($filters['date_to'])) {
            $to = addslashes($filters['date_to']) . " 23:59:59";
            $where[] = "created_at <= '{$to}'";
        }

        $whereClause = implode(" AND ", $where);
        $sql = "SELECT COUNT(*) as total FROM tbl_logs WHERE {$whereClause}";
        $res = $this->db->query($sql);
        if ($res && $row = $res->FetchRow()) {
            return (int)$row['total'];
        }
        return 0;
    }

    /**
     * Ambil statistik ringkasan log
     */
    public function getSummary() {
        $today = date("Y-m-d");

        // Total hari ini
        $resToday = $this->db->query("SELECT COUNT(*) as cnt FROM tbl_logs WHERE created_at >= '{$today} 00:00:00'");
        $todayCount = ($resToday && $row = $resToday->FetchRow()) ? (int)$row['cnt'] : 0;

        // Total Error hari ini
        $resErr = $this->db->query("SELECT COUNT(*) as cnt FROM tbl_logs WHERE level = 'ERROR' AND created_at >= '{$today} 00:00:00'");
        $todayErrors = ($resErr && $row = $resErr->FetchRow()) ? (int)$row['cnt'] : 0;

        // Total Action hari ini
        $resAct = $this->db->query("SELECT COUNT(*) as cnt FROM tbl_logs WHERE level = 'ACTION' AND created_at >= '{$today} 00:00:00'");
        $todayActions = ($resAct && $row = $resAct->FetchRow()) ? (int)$row['cnt'] : 0;

        // Total Seluruh Log
        $resAll = $this->db->query("SELECT COUNT(*) as cnt FROM tbl_logs");
        $allCount = ($resAll && $row = $resAll->FetchRow()) ? (int)$row['cnt'] : 0;

        // Daftar User Unik untuk dropdown filter
        $resUsers = $this->db->query("SELECT DISTINCT username FROM tbl_logs WHERE username IS NOT NULL AND username != '' ORDER BY username ASC");
        $users = [];
        if ($resUsers) {
            while ($row = $resUsers->FetchRow()) {
                $users[] = $row['username'];
            }
        }

        // Daftar Kategori Unik
        $resCats = $this->db->query("SELECT DISTINCT category FROM tbl_logs WHERE category IS NOT NULL AND category != '' ORDER BY category ASC");
        $categories = [];
        if ($resCats) {
            while ($row = $resCats->FetchRow()) {
                $categories[] = $row['category'];
            }
        }

        return [
            'today_total'   => $todayCount,
            'today_errors'  => $todayErrors,
            'today_actions' => $todayActions,
            'all_total'     => $allCount,
            'users'         => $users,
            'categories'    => $categories
        ];
    }

    /**
     * Hapus 1 rekaman log berdasarkan ID
     */
    public function deleteLog($id) {
        $id = (int)$id;
        $sql = "DELETE FROM tbl_logs WHERE id = {$id}";
        return $this->db->query($sql);
    }

    /**
     * Hapus masal log berdasarkan array ID
     */
    public function deleteMultipleLogs($ids) {
        if (empty($ids) || !is_array($ids)) {
            return false;
        }
        $cleanIds = array_map('intval', $ids);
        $cleanIds = array_filter($cleanIds, function($id) { return $id > 0; });
        if (empty($cleanIds)) return false;
        $idList = implode(',', $cleanIds);
        $sql = "DELETE FROM tbl_logs WHERE id IN ({$idList})";
        return $this->db->query($sql);
    }

    /**
     * Kosongkan seluruh log
     */
    public function clearAllLogs() {
        $sql = "DELETE FROM tbl_logs";
        return $this->db->query($sql);
    }

    /**
     * Bersihkan log lama di atas X hari (atau kosongkan semua jika days = 0)
     */
    public function purgeOldLogs($days = 30) {
        $days = (int)$days;
        if ($days <= 0) {
            return $this->clearAllLogs();
        }
        $sql = "DELETE FROM tbl_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL {$days} DAY)";
        return $this->db->query($sql);
    }
}

<?php
namespace Admin;

use PDO;

class SubmissionManager
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function getAllSubmissions(int $limit = 50, int $offset = 0, string $search = ''): array
    {
        $whereClause = '';
        $params = [];
        
        if (!empty($search)) {
            $whereClause = 'WHERE s.id = :search_id OR c.contact_name LIKE :search_name OR c.email LIKE :search_email OR cp.company_name LIKE :search_company';
            $params = [
                ':search_id' => (int)$search,
                ':search_name' => "%{$search}%",
                ':search_email' => "%{$search}%",
                ':search_company' => "%{$search}%"
            ];
        }
        
        $sql = "
            SELECT 
                s.id,
                s.created_at,
                c.contact_name,
                c.email,
                c.phone,
                cp.company_name,
                cp.business_type
            FROM submissions s
            LEFT JOIN contacts c ON s.id = c.submission_id
            LEFT JOIN company_profiles cp ON s.id = cp.submission_id
            {$whereClause}
            ORDER BY s.created_at DESC
            LIMIT :limit OFFSET :offset
        ";
        
        $stmt = $this->pdo->prepare($sql);
        
        // Bind search parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        // Bind pagination parameters
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getSubmissionCount(string $search = ''): int
    {
        $whereClause = '';
        $params = [];
        
        if (!empty($search)) {
            $whereClause = 'WHERE s.id = :search_id OR c.contact_name LIKE :search_name OR c.email LIKE :search_email OR cp.company_name LIKE :search_company';
            $params = [
                ':search_id' => (int)$search,
                ':search_name' => "%{$search}%",
                ':search_email' => "%{$search}%",
                ':search_company' => "%{$search}%"
            ];
        }
        
        $sql = "
            SELECT COUNT(*) as total 
            FROM submissions s
            LEFT JOIN contacts c ON s.id = c.submission_id
            LEFT JOIN company_profiles cp ON s.id = cp.submission_id
            {$whereClause}
        ";
        
        $stmt = $this->pdo->prepare($sql);
        
        // Bind search parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        $result = $stmt->fetch();
        return (int)$result['total'];
    }

    public function getSubmissionById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                s.id,
                s.created_at,
                s.updated_at,
                c.*,
                cp.*,
                o.*,
                a.*,
                ca.*,
                mb.*,
                btf.*,
                f.*
            FROM submissions s
            LEFT JOIN contacts c ON s.id = c.submission_id
            LEFT JOIN company_profiles cp ON s.id = cp.submission_id
            LEFT JOIN objectives o ON s.id = o.submission_id
            LEFT JOIN audiences a ON s.id = a.submission_id
            LEFT JOIN competition_analysis ca ON s.id = ca.submission_id
            LEFT JOIN marketing_branding mb ON s.id = mb.submission_id
            LEFT JOIN budget_tech_future btf ON s.id = btf.submission_id
            LEFT JOIN finalization f ON s.id = f.submission_id
            WHERE s.id = ?
        ");
        $stmt->execute([$id]);
        $submission = $stmt->fetch();
        
        if (!$submission) {
            return null;
        }

        // Get related data
        $submission['goals'] = $this->getGoals($id);
        $submission['competitors'] = $this->getCompetitors($id);
        $submission['audience_devices'] = $this->getAudienceDevices($id);
        $submission['audience_content_formats'] = $this->getAudienceContentFormats($id);

        return $submission;
    }

    public function getGoals(int $submissionId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT goal_text FROM goals WHERE submission_id = ? ORDER BY id
        ");
        $stmt->execute([$submissionId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getCompetitors(int $submissionId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT name, website FROM competitors WHERE submission_id = ? ORDER BY id
        ");
        $stmt->execute([$submissionId]);
        return $stmt->fetchAll();
    }

    public function getAudienceDevices(int $submissionId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT device FROM audience_devices WHERE submission_id = ? ORDER BY id
        ");
        $stmt->execute([$submissionId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getAudienceContentFormats(int $submissionId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT content_format FROM audience_content_formats WHERE submission_id = ? ORDER BY id
        ");
        $stmt->execute([$submissionId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function deleteSubmission(int $id): bool
    {
        try {
            $this->pdo->beginTransaction();
            
            // Delete in reverse order of dependencies
            $tables = [
                'goals', 'competitors', 'audience_devices', 'audience_content_formats',
                'finalization', 'budget_tech_future', 'marketing_branding', 
                'competition_analysis', 'audiences', 'objectives', 
                'company_profiles', 'contacts', 'submissions'
            ];
            
            foreach ($tables as $table) {
                $stmt = $this->pdo->prepare("DELETE FROM {$table} WHERE submission_id = ?");
                $stmt->execute([$id]);
            }
            
            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function getStats(): array
    {
        $stats = [];
        
        // Total submissions
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM submissions");
        $stmt->execute();
        $stats['total_submissions'] = (int)$stmt->fetch()['total'];
        
        // Submissions this month
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total FROM submissions 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
        ");
        $stmt->execute();
        $stats['this_month'] = (int)$stmt->fetch()['total'];
        
        // Submissions this week
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total FROM submissions 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 WEEK)
        ");
        $stmt->execute();
        $stats['this_week'] = (int)$stmt->fetch()['total'];
        
        // Business types
        $stmt = $this->pdo->prepare("
            SELECT business_type, COUNT(*) as count 
            FROM company_profiles 
            WHERE business_type != '' 
            GROUP BY business_type 
            ORDER BY count DESC
        ");
        $stmt->execute();
        $stats['business_types'] = $stmt->fetchAll();
        
        return $stats;
    }
}

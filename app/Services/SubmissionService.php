<?php
namespace App\Services;

use App\Repositories;
use PDO;
use Throwable;

class SubmissionService
{
    public function __construct(private PDO $pdo, private Repositories $repo) {}

    public function save(array $data): int
    {
        $this->pdo->beginTransaction();
        try {
            $submissionId = $this->repo->insertSubmission();

            // Step 1 & 2
            $this->repo->upsertContact($submissionId, $data);
            $this->repo->upsertCompanyProfile($submissionId, $data);

            // Step 3
            $this->repo->upsertObjectives($submissionId, $data);
            $this->repo->insertGoals($submissionId, $data['লক্ষ্য'] ?? []);

            // Step 4
            $this->repo->upsertAudience($submissionId, $data);
            $this->repo->insertAudienceDevices($submissionId, $data['টার্গেট_অডিয়েন্স_ডিভাইস'] ?? []);
            $this->repo->insertAudienceContentFormats($submissionId, $data['টার্গেট_অডিয়েন্স_কনটেন্ট_ফরম্যাট'] ?? []);
            $this->repo->insertCompetitors(
                $submissionId,
                $data['প্রতিযোগী_নাম'] ?? [],
                $data['প্রতিযোগী_ওয়েবসাইট'] ?? []
            );
            $this->repo->upsertCompetitionAnalysis($submissionId, $data);

            // Step 5
            $this->repo->upsertMarketingBranding($submissionId, $data);

            // Step 6
            $this->repo->upsertBudgetTechFuture($submissionId, $data);

            // Step 7
            $this->repo->upsertFinalization($submissionId, $data);

            $this->pdo->commit();
            return $submissionId;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}



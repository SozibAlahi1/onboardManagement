<?php
// Simple autoloader for admin classes
spl_autoload_register(function ($class) {
    if (strpos($class, 'Admin\\') === 0) {
        $file = __DIR__ . '/classes/' . str_replace('Admin\\', '', $class) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

use Admin\Auth;
use Admin\SubmissionManager;

$auth = new Auth();
$auth->requireLogin();

$submissionManager = new SubmissionManager();

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: dashboard.php');
    exit;
}

$submission = $submissionManager->getSubmissionById($id);
if (!$submission) {
    header('Location: dashboard.php');
    exit;
}

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if ($submissionManager->deleteSubmission($id)) {
        header('Location: dashboard.php?deleted=1');
        exit;
    } else {
        $error = 'Failed to delete submission.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission #<?= $id ?> - Admin Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            color: #2d3748;
            line-height: 1.6;
        }
        
        .header {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header h1 {
            color: #2d3748;
            font-size: 24px;
            font-weight: 700;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
        }
        
        .btn-secondary {
            background: #718096;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #4a5568;
        }
        
        .btn-danger {
            background: #e53e3e;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c53030;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .submission-header {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .submission-title {
            color: #2d3748;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .submission-meta {
            color: #718096;
            font-size: 14px;
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }
        
        .section {
            background: white;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .section-header {
            background: #f7fafc;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .section-title {
            color: #2d3748;
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .section-content {
            padding: 1.5rem;
        }
        
        .field-group {
            margin-bottom: 1.5rem;
        }
        
        .field-group:last-child {
            margin-bottom: 0;
        }
        
        .field-label {
            color: #4a5568;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .field-value {
            color: #2d3748;
            font-size: 15px;
            line-height: 1.5;
        }
        
        .field-value.empty {
            color: #cbd5e0;
            font-style: italic;
        }
        
        .list-item {
            background: #f7fafc;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            border-left: 3px solid #667eea;
        }
        
        .list-item:last-child {
            margin-bottom: 0;
        }
        
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        
        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-primary {
            background: #e6fffa;
            color: #234e52;
        }
        
        .badge-success {
            background: #f0fff4;
            color: #22543d;
        }
        
        .error-message {
            background: #fed7d7;
            color: #c53030;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid #e53e3e;
        }
        
        @media (max-width: 768px) {
            .header {
                padding: 1rem;
            }
            
            .container {
                padding: 1rem;
            }
            
            .submission-header {
                padding: 1.5rem;
            }
            
            .submission-title {
                font-size: 24px;
            }
            
            .submission-meta {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .grid-2,
            .grid-3 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-file-alt"></i> Submission #<?= $id ?></h1>
        <div class="header-actions">
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this submission? This action cannot be undone.');">
                <input type="hidden" name="action" value="delete">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>
    
    <div class="container">
        <?php if (isset($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <div class="submission-header">
            <h1 class="submission-title">
                <?= htmlspecialchars($submission['company_name'] ?: 'Unnamed Company') ?>
            </h1>
            <div class="submission-meta">
                <span><i class="fas fa-calendar"></i> Submitted: <?= date('M j, Y g:i A', strtotime($submission['created_at'])) ?></span>
                <span><i class="fas fa-user"></i> Contact: <?= htmlspecialchars($submission['contact_name'] ?: 'N/A') ?></span>
                <span><i class="fas fa-envelope"></i> Email: <?= htmlspecialchars($submission['email'] ?: 'N/A') ?></span>
                <?php if ($submission['business_type']): ?>
                    <span><i class="fas fa-building"></i> Type: <span class="badge badge-primary"><?= htmlspecialchars($submission['business_type']) ?></span></span>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Company Information -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-building"></i> Company Information
                </h2>
            </div>
            <div class="section-content">
                <div class="grid-2">
                    <div class="field-group">
                        <label class="field-label">Company Name</label>
                        <div class="field-value <?= empty($submission['company_name']) ? 'empty' : '' ?>">
                            <?= htmlspecialchars($submission['company_name'] ?: 'Not provided') ?>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Business Type</label>
                        <div class="field-value <?= empty($submission['business_type']) ? 'empty' : '' ?>">
                            <?= htmlspecialchars($submission['business_type'] ?: 'Not provided') ?>
                        </div>
                    </div>
                </div>
                
                <div class="grid-2">
                    <div class="field-group">
                        <label class="field-label">Website</label>
                        <div class="field-value <?= empty($submission['website']) ? 'empty' : '' ?>">
                            <?php if ($submission['website']): ?>
                                <a href="<?= htmlspecialchars($submission['website']) ?>" target="_blank">
                                    <?= htmlspecialchars($submission['website']) ?>
                                </a>
                            <?php else: ?>
                                Not provided
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Facebook Page</label>
                        <div class="field-value <?= empty($submission['facebook_page']) ? 'empty' : '' ?>">
                            <?php if ($submission['facebook_page']): ?>
                                <a href="<?= htmlspecialchars($submission['facebook_page']) ?>" target="_blank">
                                    <?= htmlspecialchars($submission['facebook_page']) ?>
                                </a>
                            <?php else: ?>
                                Not provided
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">WhatsApp Number</label>
                    <div class="field-value <?= empty($submission['whatsapp_number']) ? 'empty' : '' ?>">
                        <?php if ($submission['whatsapp_number']): ?>
                            <a href="https://wa.me/<?= htmlspecialchars($submission['whatsapp_number']) ?>" target="_blank">
                                <?= htmlspecialchars($submission['whatsapp_number']) ?>
                            </a>
                        <?php else: ?>
                            Not provided
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Address</label>
                    <div class="field-value <?= empty($submission['address']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['address'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Business Start Date</label>
                    <div class="field-value <?= empty($submission['business_start_date']) ? 'empty' : '' ?>">
                        <?= htmlspecialchars($submission['business_start_date'] ?: 'Not provided') ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Business Details</label>
                    <div class="field-value <?= empty($submission['business_details']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['business_details'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Main Products/Services</label>
                    <div class="field-value <?= empty($submission['main_products_services']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['main_products_services'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Business USP</label>
                    <div class="field-value <?= empty($submission['usp']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['usp'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Team Structure</label>
                    <div class="field-value <?= empty($submission['team_structure']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['team_structure'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Financial Status</label>
                    <div class="field-value <?= empty($submission['financial_status']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['financial_status'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Revenue Sources</label>
                    <div class="field-value <?= empty($submission['revenue_sources']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['revenue_sources'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Seasonal Impact</label>
                    <div class="field-value <?= empty($submission['seasonal_impact']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['seasonal_impact'] ?: 'Not provided')) ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Information -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-user"></i> Contact Information
                </h2>
            </div>
            <div class="section-content">
                <div class="grid-2">
                    <div class="field-group">
                        <label class="field-label">Contact Name</label>
                        <div class="field-value <?= empty($submission['contact_name']) ? 'empty' : '' ?>">
                            <?= htmlspecialchars($submission['contact_name'] ?: 'Not provided') ?>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Title/Position</label>
                        <div class="field-value <?= empty($submission['contact_title']) ? 'empty' : '' ?>">
                            <?= htmlspecialchars($submission['contact_title'] ?: 'Not provided') ?>
                        </div>
                    </div>
                </div>
                
                <div class="grid-2">
                    <div class="field-group">
                        <label class="field-label">Email</label>
                        <div class="field-value <?= empty($submission['email']) ? 'empty' : '' ?>">
                            <?php if ($submission['email']): ?>
                                <a href="mailto:<?= htmlspecialchars($submission['email']) ?>">
                                    <?= htmlspecialchars($submission['email']) ?>
                                </a>
                            <?php else: ?>
                                Not provided
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Phone</label>
                        <div class="field-value <?= empty($submission['phone']) ? 'empty' : '' ?>">
                            <?php if ($submission['phone']): ?>
                                <a href="tel:<?= htmlspecialchars($submission['phone']) ?>">
                                    <?= htmlspecialchars($submission['phone']) ?>
                                </a>
                            <?php else: ?>
                                Not provided
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Objectives & Goals -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-target"></i> Objectives & Goals
                </h2>
            </div>
            <div class="section-content">
                <div class="field-group">
                    <label class="field-label">Business Vision</label>
                    <div class="field-value <?= empty($submission['business_vision']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['business_vision'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Business Mission</label>
                    <div class="field-value <?= empty($submission['business_mission']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['business_mission'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Core Values</label>
                    <div class="field-value <?= empty($submission['core_values']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['core_values'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Main Challenges</label>
                    <div class="field-value <?= empty($submission['main_challenges']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['main_challenges'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Agency Expectations</label>
                    <div class="field-value <?= empty($submission['agency_expectations']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['agency_expectations'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Marketing KPIs</label>
                    <div class="field-value <?= empty($submission['marketing_kpis']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['marketing_kpis'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Past Failures</label>
                    <div class="field-value <?= empty($submission['past_failures']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['past_failures'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <?php if (!empty($submission['goals'])): ?>
                <div class="field-group">
                    <label class="field-label">Business Goals</label>
                    <div class="field-value">
                        <?php foreach ($submission['goals'] as $goal): ?>
                            <div class="list-item">
                                <?= htmlspecialchars($goal) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Target Audience -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-users"></i> Target Audience
                </h2>
            </div>
            <div class="section-content">
                <div class="grid-2">
                    <div class="field-group">
                        <label class="field-label">Age Range</label>
                        <div class="field-value <?= empty($submission['age_range']) ? 'empty' : '' ?>">
                            <?= htmlspecialchars($submission['age_range'] ?: 'Not provided') ?>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Gender</label>
                        <div class="field-value <?= empty($submission['gender']) ? 'empty' : '' ?>">
                            <?= htmlspecialchars($submission['gender'] ?: 'Not provided') ?>
                        </div>
                    </div>
                </div>
                
                <div class="grid-2">
                    <div class="field-group">
                        <label class="field-label">Location</label>
                        <div class="field-value <?= empty($submission['location']) ? 'empty' : '' ?>">
                            <?= htmlspecialchars($submission['location'] ?: 'Not provided') ?>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Profession/Income</label>
                        <div class="field-value <?= empty($submission['profession_income']) ? 'empty' : '' ?>">
                            <?= htmlspecialchars($submission['profession_income'] ?: 'Not provided') ?>
                        </div>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Education</label>
                    <div class="field-value <?= empty($submission['education']) ? 'empty' : '' ?>">
                        <?= htmlspecialchars($submission['education'] ?: 'Not provided') ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Interests & Hobbies</label>
                    <div class="field-value <?= empty($submission['interests']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['interests'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Problems They Face</label>
                    <div class="field-value <?= empty($submission['problems']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['problems'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Social Media Usage</label>
                    <div class="field-value <?= empty($submission['social_media']) ? 'empty' : '' ?>">
                        <?= htmlspecialchars($submission['social_media'] ?: 'Not provided') ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Online Behavior</label>
                    <div class="field-value <?= empty($submission['online_behavior']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['online_behavior'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Purchase Influencers</label>
                    <div class="field-value <?= empty($submission['purchase_influencers']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['purchase_influencers'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <?php if (!empty($submission['audience_devices'])): ?>
                <div class="field-group">
                    <label class="field-label">Preferred Devices</label>
                    <div class="field-value">
                        <?php foreach ($submission['audience_devices'] as $device): ?>
                            <span class="badge badge-primary"><?= htmlspecialchars($device) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($submission['audience_content_formats'])): ?>
                <div class="field-group">
                    <label class="field-label">Preferred Content Formats</label>
                    <div class="field-value">
                        <?php foreach ($submission['audience_content_formats'] as $format): ?>
                            <span class="badge badge-success"><?= htmlspecialchars($format) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Competitors -->
        <?php if (!empty($submission['competitors'])): ?>
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-search-dollar"></i> Competitors
                </h2>
            </div>
            <div class="section-content">
                <?php foreach ($submission['competitors'] as $competitor): ?>
                    <div class="list-item">
                        <strong><?= htmlspecialchars($competitor['name']) ?></strong>
                        <?php if ($competitor['website']): ?>
                            <br><a href="<?= htmlspecialchars($competitor['website']) ?>" target="_blank">
                                <?= htmlspecialchars($competitor['website']) ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                
                <div class="field-group">
                    <label class="field-label">Competitor Strengths & Weaknesses</label>
                    <div class="field-value <?= empty($submission['strengths_weaknesses']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['strengths_weaknesses'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Marketing Strategies They Like</label>
                    <div class="field-value <?= empty($submission['marketing_likes']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['marketing_likes'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Product Differentiation</label>
                    <div class="field-value <?= empty($submission['product_differentiation']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['product_differentiation'] ?: 'Not provided')) ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Marketing & Branding -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-bullhorn"></i> Marketing & Branding
                </h2>
            </div>
            <div class="section-content">
                <div class="field-group">
                    <label class="field-label">Current Marketing Activities</label>
                    <div class="field-value <?= empty($submission['current_activities']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['current_activities'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Successful Activities</label>
                    <div class="field-value <?= empty($submission['successful_activities']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['successful_activities'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Less Successful Activities</label>
                    <div class="field-value <?= empty($submission['less_successful_activities']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['less_successful_activities'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="grid-2">
                    <div class="field-group">
                        <label class="field-label">Monthly Marketing Budget</label>
                        <div class="field-value <?= empty($submission['monthly_budget']) ? 'empty' : '' ?>">
                            <?= htmlspecialchars($submission['monthly_budget'] ?: 'Not provided') ?>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Ad Budget Allocation</label>
                        <div class="field-value <?= empty($submission['ad_budget_allocation']) ? 'empty' : '' ?>">
                            <?= nl2br(htmlspecialchars($submission['ad_budget_allocation'] ?: 'Not provided')) ?>
                        </div>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">In-House Marketing Team</label>
                    <div class="field-value <?= empty($submission['in_house_team']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['in_house_team'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Previous Agency Experience</label>
                    <div class="field-value <?= empty($submission['previous_agency_experience']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['previous_agency_experience'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="grid-2">
                    <div class="field-group">
                        <label class="field-label">Analytics Tools Installed</label>
                        <div class="field-value">
                            <?php if ($submission['analytics_tool_installed'] === 1): ?>
                                <span class="badge badge-success">Yes</span>
                                <?php if ($submission['analytics_tool_name']): ?>
                                    <br><?= htmlspecialchars($submission['analytics_tool_name']) ?>
                                <?php endif; ?>
                            <?php elseif ($submission['analytics_tool_installed'] === 0): ?>
                                <span class="badge badge-primary">No</span>
                            <?php else: ?>
                                Not specified
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label">CRM System Used</label>
                        <div class="field-value">
                            <?php if ($submission['crm_used'] === 1): ?>
                                <span class="badge badge-success">Yes</span>
                                <?php if ($submission['crm_name']): ?>
                                    <br><?= htmlspecialchars($submission['crm_name']) ?>
                                <?php endif; ?>
                            <?php elseif ($submission['crm_used'] === 0): ?>
                                <span class="badge badge-primary">No</span>
                            <?php else: ?>
                                Not specified
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Website Traffic Sources</label>
                    <div class="field-value <?= empty($submission['website_traffic_sources']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['website_traffic_sources'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Email Marketing List</label>
                    <div class="field-value <?= empty($submission['email_marketing_list']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['email_marketing_list'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Social Media Engagement</label>
                    <div class="field-value <?= empty($submission['social_media_engagement']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['social_media_engagement'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Customer Acquisition Cost Understanding</label>
                    <div class="field-value <?= empty($submission['cac_understanding']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['cac_understanding'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Marketing Automation Tools</label>
                    <div class="field-value <?= empty($submission['marketing_automation_tools']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['marketing_automation_tools'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="grid-2">
                    <div class="field-group">
                        <label class="field-label">Brand Guidelines</label>
                        <div class="field-value">
                            <?php if ($submission['brand_guideline'] === 'হ্যাঁ'): ?>
                                <span class="badge badge-success">Yes</span>
                            <?php elseif ($submission['brand_guideline'] === 'না'): ?>
                                <span class="badge badge-primary">No</span>
                            <?php else: ?>
                                Not specified
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Blog Status</label>
                        <div class="field-value">
                            <?php if ($submission['blog_status'] === 'হ্যাঁ'): ?>
                                <span class="badge badge-success">Yes</span>
                                <?php if ($submission['blog_frequency']): ?>
                                    <br><?= htmlspecialchars($submission['blog_frequency']) ?>
                                <?php endif; ?>
                            <?php elseif ($submission['blog_status'] === 'না'): ?>
                                <span class="badge badge-primary">No</span>
                            <?php else: ?>
                                Not specified
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Brand Personality</label>
                    <div class="field-value <?= empty($submission['brand_personality']) ? 'empty' : '' ?>">
                        <?= htmlspecialchars($submission['brand_personality'] ?: 'Not provided') ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Brand Tone of Voice</label>
                    <div class="field-value <?= empty($submission['brand_tone_of_voice']) ? 'empty' : '' ?>">
                        <?= htmlspecialchars($submission['brand_tone_of_voice'] ?: 'Not provided') ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Content Themes & Pillars</label>
                    <div class="field-value <?= empty($submission['content_themes_pillars']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['content_themes_pillars'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Preferred Visuals</label>
                    <div class="field-value <?= empty($submission['preferred_visuals']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['preferred_visuals'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Existing Marketing Materials</label>
                    <div class="field-value <?= empty($submission['existing_marketing_materials']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['existing_marketing_materials'] ?: 'Not provided')) ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Budget & Technology -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-cogs"></i> Budget & Technology
                </h2>
            </div>
            <div class="section-content">
                <div class="field-group">
                    <label class="field-label">Project Budget</label>
                    <div class="field-value <?= empty($submission['project_budget']) ? 'empty' : '' ?>">
                        <?= htmlspecialchars($submission['project_budget'] ?: 'Not provided') ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Budget Priorities</label>
                    <div class="field-value <?= empty($submission['budget_priorities']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['budget_priorities'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Results Timeline</label>
                    <div class="field-value <?= empty($submission['results_timeline']) ? 'empty' : '' ?>">
                        <?= htmlspecialchars($submission['results_timeline'] ?: 'Not provided') ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Reporting Preference</label>
                    <div class="field-value">
                        <?php if ($submission['reporting_preference']): ?>
                            <span class="badge badge-primary"><?= htmlspecialchars($submission['reporting_preference']) ?></span>
                        <?php else: ?>
                            Not specified
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="grid-2">
                    <div class="field-group">
                        <label class="field-label">Website Admin Access</label>
                        <div class="field-value">
                            <?php if ($submission['need_website_admin_access']): ?>
                                <span class="badge badge-primary"><?= htmlspecialchars($submission['need_website_admin_access']) ?></span>
                            <?php else: ?>
                                Not specified
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Social Media Access</label>
                        <div class="field-value">
                            <?php if ($submission['need_social_media_access']): ?>
                                <span class="badge badge-primary"><?= htmlspecialchars($submission['need_social_media_access']) ?></span>
                            <?php else: ?>
                                Not specified
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Tracking Code Setup</label>
                    <div class="field-value">
                        <?php if ($submission['tracking_code_setup']): ?>
                            <span class="badge badge-primary"><?= htmlspecialchars($submission['tracking_code_setup']) ?></span>
                            <?php if ($submission['tracking_code_access_needed']): ?>
                                <br><?= htmlspecialchars($submission['tracking_code_access_needed']) ?>
                            <?php endif; ?>
                        <?php else: ?>
                            Not specified
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Future Products/Services</label>
                    <div class="field-value <?= empty($submission['future_products_services']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['future_products_services'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Technology Usage Plans</label>
                    <div class="field-value <?= empty($submission['tech_usage_plans']) ? 'empty' : '' ?>">
                        <?= nl2br(htmlspecialchars($submission['tech_usage_plans'] ?: 'Not provided')) ?>
                    </div>
                </div>
                
                <div class="field-group">
                    <label class="field-label">E-commerce/Payment Experience</label>
                    <div class="field-value">
                        <?php if ($submission['ecommerce_payment_experience']): ?>
                            <span class="badge badge-primary"><?= htmlspecialchars($submission['ecommerce_payment_experience']) ?></span>
                        <?php else: ?>
                            Not specified
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Additional Information -->
        <?php if ($submission['additional_info'] || $submission['questions_for_agency']): ?>
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-comment-dots"></i> Additional Information
                </h2>
            </div>
            <div class="section-content">
                <?php if ($submission['additional_info']): ?>
                <div class="field-group">
                    <label class="field-label">Additional Information</label>
                    <div class="field-value">
                        <?= nl2br(htmlspecialchars($submission['additional_info'])) ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($submission['questions_for_agency']): ?>
                <div class="field-group">
                    <label class="field-label">Questions for Agency</label>
                    <div class="field-value">
                        <?= nl2br(htmlspecialchars($submission['questions_for_agency'])) ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>

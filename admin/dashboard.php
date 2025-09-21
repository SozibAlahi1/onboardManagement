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

// Get search and pagination parameters
$search = $_GET['search'] ?? '';
$page = (int)($_GET['page'] ?? 1);
$limit = 20;
$offset = ($page - 1) * $limit;

// Get submissions and stats
$submissions = $submissionManager->getAllSubmissions($limit, $offset, $search);
$totalSubmissions = $submissionManager->getSubmissionCount($search);
$stats = $submissionManager->getStats();

$totalPages = ceil($totalSubmissions / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Panel</title>
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
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #718096;
            font-size: 14px;
        }
        
        .logout-btn {
            background: #e53e3e;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s ease;
        }
        
        .logout-btn:hover {
            background: #c53030;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-left: 4px solid #667eea;
        }
        
        .stat-card h3 {
            color: #4a5568;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-card .value {
            color: #2d3748;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stat-card .change {
            color: #38a169;
            font-size: 14px;
            font-weight: 500;
        }
        
        .submissions-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .section-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .section-header h2 {
            color: #2d3748;
            font-size: 20px;
            font-weight: 600;
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        .submissions-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .submissions-table th {
            background: #f7fafc;
            color: #4a5568;
            font-weight: 600;
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }
        
        .submissions-table td {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }
        
        .submissions-table tr:hover {
            background: #f7fafc;
        }
        
        .submission-id {
            font-weight: 700;
            color: #667eea;
            font-size: 14px;
        }
        
        .company-name {
            font-weight: 600;
            color: #2d3748;
        }
        
        .contact-info {
            color: #718096;
            font-size: 13px;
        }
        
        .business-type {
            background: #e6fffa;
            color: #234e52;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .date {
            color: #718096;
            font-size: 13px;
        }
        
        .actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a67d8;
        }
        
        .btn-danger {
            background: #e53e3e;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c53030;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            padding: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }
        
        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            color: #4a5568;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .pagination a:hover {
            background: #e2e8f0;
            color: #2d3748;
        }
        
        .pagination .current {
            background: #667eea;
            color: white;
        }
        
        .search-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .search-form {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .search-input {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .search-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .search-btn:hover {
            background: #5a67d8;
        }
        
        .clear-btn {
            background: #718096;
            color: white;
            border: none;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .clear-btn:hover {
            background: #4a5568;
        }
        
        .search-info {
            margin-top: 1rem;
            color: #718096;
            font-size: 14px;
        }
        
         .search-info strong {
             color: #2d3748;
         }
         
         .empty-state {
             text-align: center;
             padding: 4rem 2rem;
             color: #718096;
         }
         
         .empty-state i {
             font-size: 64px;
             margin-bottom: 1.5rem;
             color: #cbd5e0;
             opacity: 0.8;
         }
         
         .empty-state h3 {
             color: #4a5568;
             font-size: 24px;
             font-weight: 600;
             margin-bottom: 1rem;
         }
         
         .empty-state p {
             font-size: 16px;
             margin-bottom: 2rem;
             max-width: 400px;
             margin-left: auto;
             margin-right: auto;
             line-height: 1.6;
         }
         
         .empty-state .action-buttons {
             display: flex;
             gap: 1rem;
             justify-content: center;
             flex-wrap: wrap;
         }
         
         .empty-state .btn-large {
             padding: 12px 24px;
             font-size: 16px;
             font-weight: 600;
             border-radius: 8px;
             text-decoration: none;
             display: inline-flex;
             align-items: center;
             gap: 8px;
             transition: all 0.3s ease;
         }
         
         .empty-state .btn-primary-large {
             background: #667eea;
             color: white;
         }
         
         .empty-state .btn-primary-large:hover {
             background: #5a67d8;
             transform: translateY(-2px);
         }
         
         .empty-state .btn-secondary-large {
             background: #e2e8f0;
             color: #4a5568;
         }
         
         .empty-state .btn-secondary-large:hover {
             background: #cbd5e0;
             transform: translateY(-2px);
         }
         
         .empty-state .stats-mini {
             margin-top: 2rem;
             padding-top: 2rem;
             border-top: 1px solid #e2e8f0;
         }
         
         .empty-state .stats-mini h4 {
             color: #4a5568;
             font-size: 16px;
             font-weight: 600;
             margin-bottom: 1rem;
         }
         
         .empty-state .stats-mini .mini-stats {
             display: flex;
             justify-content: center;
             gap: 2rem;
             flex-wrap: wrap;
         }
         
         .empty-state .stats-mini .mini-stat {
             text-align: center;
         }
         
         .empty-state .stats-mini .mini-stat .number {
             font-size: 20px;
             font-weight: 700;
             color: #667eea;
             display: block;
         }
         
         .empty-state .stats-mini .mini-stat .label {
             font-size: 12px;
             color: #718096;
             text-transform: uppercase;
             letter-spacing: 0.5px;
         }
         
         @media (max-width: 768px) {
            .header {
                padding: 1rem;
            }
            
            .container {
                padding: 1rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .section-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            .submissions-table {
                font-size: 12px;
            }
            
            .submissions-table th,
            .submissions-table td {
                padding: 0.75rem 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
        <div class="header-actions">
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                Welcome, <?= htmlspecialchars($_SESSION['admin_username']) ?>
            </div>
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
    
    <div class="container">
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Submissions</h3>
                <div class="value"><?= number_format($stats['total_submissions']) ?></div>
                <div class="change">All time</div>
            </div>
            
            <div class="stat-card">
                <h3>This Month</h3>
                <div class="value"><?= number_format($stats['this_month']) ?></div>
                <div class="change">Last 30 days</div>
            </div>
            
            <div class="stat-card">
                <h3>This Week</h3>
                <div class="value"><?= number_format($stats['this_week']) ?></div>
                <div class="change">Last 7 days</div>
            </div>
            
            <div class="stat-card">
                <h3>Most Common Business Type</h3>
                <div class="value">
                    <?= !empty($stats['business_types']) ? htmlspecialchars($stats['business_types'][0]['business_type']) : 'N/A' ?>
                </div>
                <div class="change">
                    <?= !empty($stats['business_types']) ? $stats['business_types'][0]['count'] . ' submissions' : '' ?>
                </div>
            </div>
        </div>
        
        <!-- Search Section -->
        <div class="search-container">
            <form method="GET" class="search-form">
                <input type="text" 
                       name="search" 
                       class="search-input" 
                       placeholder="Search by ID, name, email, or company name..."
                       value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i> Search
                </button>
                <?php if (!empty($search)): ?>
                    <a href="dashboard.php" class="clear-btn">
                        <i class="fas fa-times"></i> Clear
                    </a>
                <?php endif; ?>
            </form>
            
            <?php if (!empty($search)): ?>
                <div class="search-info">
                    <i class="fas fa-info-circle"></i> 
                    Showing <strong><?= number_format($totalSubmissions) ?></strong> result(s) for "<strong><?= htmlspecialchars($search) ?></strong>"
                </div>
            <?php endif; ?>
        </div>
        <div class="submissions-section">
            <div class="section-header">
                <h2><i class="fas fa-list"></i> Recent Submissions</h2>
                <div>
                    <a href="index.php" class="btn btn-primary" target="_blank">
                        <i class="fas fa-external-link-alt"></i> View Form
                    </a>
                </div>
            </div>
            
             <?php if (empty($submissions)): ?>
                 <div class="empty-state">
                     <i class="fas fa-clipboard-list"></i>
                     <h3><?= !empty($search) ? 'No results found' : 'No submissions yet' ?></h3>
                     <p>
                         <?php if (!empty($search)): ?>
                             No submissions match your search criteria. Try adjusting your search terms or clear the search to see all submissions.
                         <?php else: ?>
                             Your client onboarding form is ready! Once clients start filling out the form, their submissions will appear here for you to review and manage.
                         <?php endif; ?>
                     </p>
                     
                     
                     <?php if (empty($search)): ?>
                         <div class="stats-mini">
                             <h4>Quick Stats</h4>
                             <div class="mini-stats">
                                 <div class="mini-stat">
                                     <span class="number"><?= number_format($stats['total_submissions']) ?></span>
                                     <span class="label">Total Submissions</span>
                                 </div>
                                 <div class="mini-stat">
                                     <span class="number"><?= number_format($stats['this_month']) ?></span>
                                     <span class="label">This Month</span>
                                 </div>
                                 <div class="mini-stat">
                                     <span class="number"><?= number_format($stats['this_week']) ?></span>
                                     <span class="label">This Week</span>
                                 </div>
                             </div>
                         </div>
                     <?php endif; ?>
                 </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="submissions-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Company</th>
                                <th>Contact</th>
                                <th>Business Type</th>
                                <th>Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($submissions as $submission): ?>
                                <tr>
                                    <td>
                                        <div class="submission-id">
                                            <strong>#<?= $submission['id'] ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="company-name">
                                            <?= htmlspecialchars($submission['company_name'] ?: 'N/A') ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="contact-info">
                                            <div><?= htmlspecialchars($submission['contact_name'] ?: 'N/A') ?></div>
                                            <div><?= htmlspecialchars($submission['email'] ?: 'N/A') ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($submission['business_type']): ?>
                                            <span class="business-type">
                                                <?= htmlspecialchars($submission['business_type']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span style="color: #cbd5e0;">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="date">
                                            <?= date('M j, Y g:i A', strtotime($submission['created_at'])) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="submission.php?id=<?= $submission['id'] ?>" class="btn btn-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="delete.php?id=<?= $submission['id'] ?>" 
                                               class="btn btn-danger"
                                               onclick="return confirm('Are you sure you want to delete this submission?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                            <?php if ($i == $page): ?>
                                <span class="current"><?= $i ?></span>
                            <?php else: ?>
                                <a href="?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">
                                Next <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

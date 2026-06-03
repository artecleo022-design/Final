<?php
require_once 'admin_header.php';

// Get distinct academic years
$stmtYears = $pdo->query("SELECT DISTINCT academic_year FROM applications WHERE academic_year IS NOT NULL ORDER BY academic_year DESC");
$years = $stmtYears->fetchAll(PDO::FETCH_COLUMN);
$selectedYear = $_GET['academic_year'] ?? '';
?>

<style>
.export-wrapper {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px 0;
}
.header-section {
    text-align: center;
    margin-bottom: 40px;
}
.header-icon {
    width: 70px; height: 70px; 
    background: linear-gradient(135deg, rgba(15,81,50,0.1), rgba(15,81,50,0.05)); 
    border-radius: 50%; 
    display: flex; align-items: center; justify-content: center; 
    margin: 0 auto 20px auto; 
    color: var(--primary); 
    font-size: 28px;
    box-shadow: inset 0 0 0 1px rgba(15,81,50,0.1);
}
.header-title {
    margin: 0 0 10px 0; color: #1a202c; font-size: 32px; font-weight: 800; letter-spacing: -0.5px;
}
.header-desc {
    color: #718096; line-height: 1.6; max-width: 600px; margin: 0 auto; font-size: 15px;
}

.export-grid {
    display: flex;
    gap: 25px;
    justify-content: center;
    flex-wrap: wrap;
}
.export-card {
    background: #ffffff;
    border: 1px solid #edf2f7;
    border-radius: 20px;
    padding: 35px 25px;
    text-align: center;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    text-decoration: none;
    color: #2d3748;
    width: calc(33.333% - 17px);
    min-width: 240px;
    box-sizing: border-box;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0,0,0,0.02);
}
.export-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: linear-gradient(180deg, rgba(255,255,255,0) 0%, rgba(0,0,0,0.02) 100%);
    opacity: 0;
    transition: opacity 0.4s;
    pointer-events: none;
}
.export-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.08);
}
.export-card:hover::before {
    opacity: 1;
}

/* Specific colors */
.export-excel { border-top: 4px solid #107c41; }
.export-excel:hover { border-color: #107c41; box-shadow: 0 20px 40px rgba(16, 124, 65, 0.15); }
.export-excel .icon { color: #107c41; background: rgba(16, 124, 65, 0.1); }

.export-csv { border-top: 4px solid #0d6efd; }
.export-csv:hover { border-color: #0d6efd; box-shadow: 0 20px 40px rgba(13, 110, 253, 0.15); }
.export-csv .icon { color: #0d6efd; background: rgba(13, 110, 253, 0.1); }

.export-pdf { border-top: 4px solid #dc3545; }
.export-pdf:hover { border-color: #dc3545; box-shadow: 0 20px 40px rgba(220, 53, 69, 0.15); }
.export-pdf .icon { color: #dc3545; background: rgba(220, 53, 69, 0.1); }

.icon {
    width: 65px; height: 65px;
    margin: 0 auto 20px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 28px;
    transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.export-card:hover .icon {
    transform: scale(1.15) rotate(5deg);
}
.export-title {
    font-size: 19px; font-weight: 800; margin-bottom: 12px; color: #1a202c;
}
.export-desc {
    font-size: 13.5px; color: #718096; line-height: 1.5;
}

.print-section {
    margin-top: 50px;
    padding: 35px 40px;
    background: #ffffff;
    border-radius: 20px;
    border: 1px dashed #cbd5e0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    transition: all 0.3s;
}
.print-section:hover {
    border-color: var(--primary);
    background: #fcfdfd;
}
.print-section-info {
    display: flex; align-items: center; gap: 20px;
}
.print-icon-wrap {
    width: 50px; height: 50px;
    background: #edf2f7; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: #4a5568; font-size: 20px;
}
.print-section-info h3 { margin: 0 0 5px 0; font-size: 18px; color: #2d3748; font-weight: 800; }
.print-section-info p { margin: 0; font-size: 14px; color: #718096; }

.btn-print {
    background: var(--primary); color: white;
    padding: 14px 30px; border-radius: 12px;
    font-weight: 700; text-decoration: none; font-size: 15px;
    box-shadow: 0 4px 10px rgba(15, 81, 50, 0.2);
    transition: all 0.3s;
    display: inline-flex; align-items: center; gap: 10px;
}
.btn-print:hover {
    background: #0a3a23; transform: translateY(-3px); box-shadow: 0 8px 20px rgba(15, 81, 50, 0.3);
}

@media (max-width: 768px) {
    .export-card { width: 100%; }
    .print-section { flex-direction: column; text-align: center; justify-content: center; }
    .print-section-info { flex-direction: column; text-align: center; }
}
</style>

<div class="export-wrapper">
    <div class="header-section">
        <div class="header-icon">
            <i class="fa-solid fa-cloud-arrow-down"></i>
        </div>
        <h2 class="header-title">Export Data Reports</h2>
        <p class="header-desc">Generate standardized files of your applicant and organization records for offline administrative review, accounting, or archival purposes.</p>
    </div>
    
    <!-- Year Filter -->
    <form method="GET" style="text-align: center; margin-bottom: 35px;">
        <label style="font-weight: 700; font-size: 15px; color: #2d3748; margin-right: 10px;">Filter by Academic Year:</label>
        <select name="academic_year" onchange="this.form.submit()" style="width: auto; display: inline-block; padding: 10px 25px; border-radius: 10px; border: 2px solid #e2e8f0; font-weight: 600; font-family: inherit; cursor: pointer; background: white;">
            <option value="">-- All Years --</option>
            <?php foreach ($years as $year): ?>
                <option value="<?= htmlspecialchars($year) ?>" <?= $selectedYear === $year ? 'selected' : '' ?>><?= htmlspecialchars($year) ?></option>
            <?php endforeach; ?>
        </select>
        <?php if ($selectedYear): ?>
            <a href="reports.php" style="margin-left: 10px; color: #718096; font-size: 13px; text-decoration: none;">Clear filter</a>
        <?php endif; ?>
    </form>

    <div class="export-grid">
        <a href="export_excel.php<?= $selectedYear ? '?academic_year=' . urlencode($selectedYear) : '' ?>" class="export-card export-excel">
            <div class="icon"><i class="fa-solid fa-file-excel"></i></div>
            <div class="export-title">Excel Spreadsheet</div>
            <div class="export-desc">Download a beautifully formatted .xls file ready for data manipulation and graphing.</div>
        </a>
        
        <a href="export_csv.php<?= $selectedYear ? '?academic_year=' . urlencode($selectedYear) : '' ?>" class="export-card export-csv">
            <div class="icon"><i class="fa-solid fa-file-csv"></i></div>
            <div class="export-title">Raw CSV Data</div>
            <div class="export-desc">Get a lightweight, plain-text .csv file. Best for importing into other database systems.</div>
        </a>
        
        <a href="export_pdf.php<?= $selectedYear ? '?academic_year=' . urlencode($selectedYear) : '' ?>" class="export-card export-pdf">
            <div class="icon"><i class="fa-solid fa-file-pdf"></i></div>
            <div class="export-title">PDF Document</div>
            <div class="export-desc">Generate a secure, print-ready document perfect for sharing and formal presentations.</div>
        </a>
    </div>
    
    <div class="print-section">
        <div class="print-section-info">
            <div class="print-icon-wrap"><i class="fa-solid fa-print"></i></div>
            <div>
                <h3>Physical Copy & Print Layout</h3>
                <p>Open the live system data directly in a browser print-optimized layout.</p>
            </div>
        </div>
        <a href="print_report.php<?= $selectedYear ? '?academic_year=' . urlencode($selectedYear) : '' ?>" target="_blank" class="btn-print">
            <i class="fa-solid fa-print"></i> Open Print View
        </a>
    </div>
</div>

<?php require_once 'admin_footer.php'; ?>

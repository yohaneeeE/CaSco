<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Reports & Analytics - Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f9f9f9;
            color: #333;
            line-height: 1.6;
        }
        header {
            background: linear-gradient(135deg, #004080, #0066cc);
            color: white;
            padding: 25px 0;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        nav {
            background-color: #003060;
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 8px 15px;
            border-radius: 5px;
        }
        nav ul li a:hover, nav ul li a.active {
            color: #ffcc00;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        h2 {
            color: #004080;
            margin-bottom: 25px;
            text-align: center;
            font-size: 2rem;
            position: relative;
            padding-bottom: 15px;
        }
        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, #004080, #ffcc00);
            border-radius: 3px;
        }
        .intro-text {
            font-size: 1.1em;
            margin-bottom: 40px;
            text-align: center;
            color: #555;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        .report-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
        }
        .date-filter {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }
        .date-filter input, .date-filter select {
            padding: 10px 15px;
            border: 2px solid #e0e9ff;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: border-color 0.3s ease;
        }
        .date-filter input:focus, .date-filter select:focus {
            outline: none;
            border-color: #004080;
        }
        .btn {
            background: linear-gradient(135deg, #004080, #0066cc);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background: linear-gradient(135deg, #003060, #004080);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 64, 128, 0.3);
        }
        .btn-secondary {
            background: linear-gradient(135deg, #28a745, #20c997);
        }
        .btn-secondary:hover {
            background: linear-gradient(135deg, #218838, #1ea085);
        }
        .btn-warning {
            background: linear-gradient(135deg, #ffc107, #ffb300);
            color: #333;
        }
        .btn-warning:hover {
            background: linear-gradient(135deg, #e0a800, #ff8f00);
        }
        .btn-small {
            padding: 8px 16px;
            font-size: 0.9rem;
        }
        .stats-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: linear-gradient(135deg, #f2f7ff, #e6f0ff);
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            border: 1px solid #e0e9ff;
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            background: linear-gradient(135deg, #004080, #0066cc);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #004080;
            margin-bottom: 8px;
        }
        .stat-label {
            color: #666;
            font-size: 1rem;
            margin-bottom: 5px;
        }
        .stat-change {
            font-size: 0.9rem;
            font-weight: 600;
        }
        .stat-increase {
            color: #28a745;
        }
        .stat-decrease {
            color: #dc3545;
        }
        .reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        .report-card {
            background: white;
            border: 1px solid #e0e9ff;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-color: #004080;
        }
        .report-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            gap: 15px;
        }
        .report-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: linear-gradient(135deg, #004080, #0066cc);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .report-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #003060;
            margin-bottom: 5px;
        }
        .report-subtitle {
            color: #666;
            font-size: 0.9rem;
        }
        .report-content {
            margin-bottom: 20px;
        }
        .report-metric {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .metric-label {
            color: #666;
        }
        .metric-value {
            font-weight: 600;
            color: #004080;
        }
        .report-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .chart-container {
            background: white;
            border: 1px solid #e0e9ff;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .chart-title {
            font-size: 1.4rem;
            font-weight: bold;
            color: #003060;
        }
        .chart-placeholder {
            height: 300px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 1.1rem;
            border: 2px dashed #dee2e6;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        .data-table th {
            background: linear-gradient(135deg, #004080, #0066cc);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
        }
        .data-table tr:hover {
            background-color: #f8f9fa;
        }
        .export-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        .export-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #003060;
            margin-bottom: 15px;
        }
        .export-options {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        footer {
            text-align: center;
            padding: 30px 0;
            background: linear-gradient(135deg, #003060, #004080);
            color: white;
            font-size: 0.95em;
            margin-top: 60px;
        }
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 15px;
        }
        .footer-links a {
            color: #ffcc00;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .footer-links a:hover {
            color: white;
        }
        @media (max-width: 768px) {
            .report-controls {
                flex-direction: column;
                align-items: stretch;
            }
            .date-filter {
                justify-content: center;
            }
            .reports-grid {
                grid-template-columns: 1fr;
            }
            .export-options {
                justify-content: center;
            }
            nav ul {
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Reports & Analytics</h1>
        <p>Comprehensive insights and data analysis dashboard</p>
    </header>
    
    <nav>
        <ul>
            <li><a href="dashboard.html">Dashboard</a></li>
            <li><a href="admin-users.html">User Management</a></li>
            <li><a href="admin-content.html" class="active">Content Management</a></li>
            <li><a href="admin-reports.html">Reports</a></li>
            <li><a href="../index.html">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>Reports & Analytics Dashboard</h2>
        <p class="intro-text">Generate comprehensive reports and analyze system performance, user engagement, and content metrics.</p>
        
        <!-- Report Controls -->
        <div class="report-controls">
            <div class="date-filter">
                <label>Date Range:</label>
                <input type="date" id="startDate" value="2024-01-01">
                <span>to</span>
                <input type="date" id="endDate" value="2024-12-31">
                <select id="reportPeriod">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly" selected>Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
                <button class="btn btn-small" onclick="updateReports()">Update</button>
            </div>
            <div>
                <button class="btn btn-secondary" onclick="generateReport()">Generate Report</button>
                <button class="btn btn-warning" onclick="scheduleReport()">Schedule Report</button>
            </div>
        </div>

        <!-- Statistics Overview -->
        <div class="stats-overview">
            <div class="stat-card">
                <div class="stat-icon">
                    <img src="https://img.icons8.com/color/40/000000/user.png" alt="Users">
                </div>
                <div class="stat-number                <div class="stat-number">2,847</div>
                <div class="stat-label">Total Users</div>
                <div class="stat-change stat-increase">+12.5% from last month</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <img src="https://img.icons8.com/color/40/000000/content.png" alt="Content">
                </div>
                <div class="stat-number">156</div>
                <div class="stat-label">Content Items</div>
                <div class="stat-change stat-increase">+8.3% from last month</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <img src="https://img.icons8.com/color/40/000000/view.png" alt="Views">
                </div>
                <div class="stat-number">45,892</div>
                <div class="stat-label">Page Views</div>
                <div class="stat-change stat-increase">+23.7% from last month</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <img src="https://img.icons8.com/color/40/000000/time.png" alt="Time">
                </div>
                <div class="stat-number">4.2m</div>
                <div class="stat-label">Session Duration</div>
                <div class="stat-change stat-decrease">-2.1% from last month</div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="chart-container">
            <div class="chart-header">
                <div class="chart-title">User Activity Trends</div>
                <select id="chartType">
                    <option value="line">Line Chart</option>
                    <option value="bar">Bar Chart</option>
                    <option value="area">Area Chart</option>
                </select>
            </div>
            <div class="chart-placeholder">
                📊 Interactive Chart Will Be Displayed Here
                <br><small>Chart.js or similar library integration required</small>
            </div>
        </div>

        <!-- Reports Grid -->
        <div class="reports-grid">
            <div class="report-card">
                <div class="report-header">
                    <div class="report-icon">
                        <img src="https://img.icons8.com/color/30/000000/user.png" alt="Users">
                    </div>
                    <div>
                        <div class="report-title">User Analytics</div>
                        <div class="report-subtitle">Registration, activity, and engagement metrics</div>
                    </div>
                </div>
                <div class="report-content">
                    <div class="report-metric">
                        <span class="metric-label">New Registrations</span>
                        <span class="metric-value">156</span>
                    </div>
                    <div class="report-metric">
                        <span class="metric-label">Active Users (30d)</span>
                        <span class="metric-value">1,234</span>
                    </div>
                    <div class="report-metric">
                        <span class="metric-label">User Retention Rate</span>
                        <span class="metric-value">68.5%</span>
                    </div>
                    <div class="report-metric">
                        <span class="metric-label">Average Session Time</span>
                        <span class="metric-value">8m 32s</span>
                    </div>
                </div>
                <div class="report-actions">
                    <button class="btn btn-small" onclick="viewReport('user-analytics')">View Details</button>
                    <button class="btn btn-small btn-secondary" onclick="exportReport('user-analytics')">Export</button>
                </div>
            </div>

            <div class="report-card">
                <div class="report-header">
                    <div class="report-icon">
                        <img src="https://img.icons8.com/color/30/000000/content.png" alt="Content">
                    </div>
                    <div>
                        <div class="report-title">Content Performance</div>
                        <div class="report-subtitle">Views, engagement, and popularity metrics</div>
                    </div>
                </div>
                <div class="report-content">
                    <div class="report-metric">
                        <span class="metric-label">Total Views</span>
                        <span class="metric-value">45,892</span>
                    </div>
                    <div class="report-metric">
                        <span class="metric-label">Most Popular Content</span>
                        <span class="metric-value">AI Engineer</span>
                    </div>
                    <div class="report-metric">
                        <span class="metric-label">Engagement Rate</span>
                        <span class="metric-value">72.3%</span>
                    </div>
                    <div class="report-metric">
                        <span class="metric-label">Bounce Rate</span>
                        <span class="metric-value">24.7%</span>
                    </div>
                </div>
                <div class="report-actions">
                    <button class="btn btn-small" onclick="viewReport('content-performance')">View Details</button>
                    <button class="btn btn-small btn-secondary" onclick="exportReport('content-performance')">Export</button>
                </div>
            </div>

            <div class="report-card">
                <div class="report-header">
                    <div class="report-icon">
                        <img src="https://img.icons8.com/color/30/000000/search.png" alt="Search">
                    </div>
                    <div>
                        <div class="report-title">Search Analytics</div>
                        <div class="report-subtitle">Popular searches and trends</div>
                    </div>
                </div>
                <div class="report-content">
                    <div class="report-metric">
                        <span class="metric-label">Total Searches</span>
                        <span class="metric-value">12,456</span>
                    </div>
                    <div class="report-metric">
                        <span class="metric-label">Top Search Term</span>
                        <span class="metric-value">"AI Engineer"</span>
                    </div>
                    <div class="report-metric">
                        <span class="metric-label">Search Success Rate</span>
                        <span class="metric-value">89.2%</span>
                    </div>
                    <div class="report-metric">
                        <span class="metric-label">Zero Results Rate</span>
                        <span class="metric-value">3.8%</span>
                    </div>
                </div>
                <div class="report-actions">
                    <button class="btn btn-small" onclick="viewReport('search-analytics')">View Details</button>
                    <button class="btn btn-small btn-secondary" onclick="exportReport('search-analytics')">Export</button>
                </div>
            </div>

            <div class="report-card">
                <div class="report-header">
                    <div class="report-icon">
                        <img src="https://img.icons8.com/color/30/000000/system-report.png" alt="System">
                    </div>
                    <div>
                        <div class="report-title">System Performance</div>
                        <div class="report-subtitle">Server metrics and performance data</div>
                    </div>
                </div>
                <div class="report-content">
                    <div class="report-metric">
                        <span class="metric-label">Uptime</span>
                        <span class="metric-value">99.8%</span>
                    </div>
                    <div class="report-metric">
                        <span class="metric-label">Average Response Time</span>
                        <span class="metric-value">245ms</span>
                    </div>
                    <div class="report-metric">
                        <span class="metric-label">Error Rate</span>
                        <span class="metric-value">0.2%</span>
                    </div>
                    <div class="report-metric">
                        <span class="metric-label">Peak Concurrent Users</span>
                        <span class="metric-value">892</span>
                    </div>
                </div>
                <div class="report-actions">
                    <button class="btn btn-small" onclick="viewReport('system-performance')">View Details</button>
                    <button class="btn btn-small btn-secondary" onclick="exportReport('system-performance')">Export</button>
                </div>
            </div>

            <div class="report-card">
                <div class="report-header">
                    <div class="report-icon">
                        <img src="https://img.icons8.com/color/30/000000/geography.png" alt="Geography">
                    </div>
                    <div>
                        <div class="report-title">Geographic Analytics</div>
                        <div class="report-subtitle">User location and regional data</div>
                    </div>
                </div>
                <div class="report-content">
                    <div class="report-metric">
                        <span class="metric-label">Top Country</span>
                        <span class="metric-value">Philippines</span>
                    </div>
                    <div class="report-metric">
                        <span class="metric-label">Top Region</span>
                        <span class="metric-value">Metro Manila</span>
                    </div>
                    <div class="report-metric">
                        <span class="metric-label">International Users</span>
                        <span class="metric-value">15.3%</span>
                    </div>
                    <div class="report-metric">
                        <span class="metric-label">Mobile vs Desktop</span>
                        <span class="metric-value">65% / 35%</span>
                    </div>
                </div>
                <div class="report-actions">
                    <button class="btn btn-small" onclick="viewReport('geographic-analytics')">View Details</button>
                    <button class="btn btn-small btn-secondary" onclick="exportReport('geographic-analytics')">Export</button>
                </div>
            </div>

            <div class="report-card">
                <div class="report-header">
                    <div class="report-icon">
                        <img src="https://img.icons8.com/color/30/000000/security-checked.png" alt="Security">
                    </div>
                    <div>
                        <div class="report-title">Security Report</div>
                        <div class="report-subtitle">Security events and threat analysis</div>
                    </div>
                </div>
                <div class="report-content">
                    <div class="report-metric">
                        <span class="metric-label">Security Events</span>
                        <span class="metric-value">23</span>
                    </div>
                    <div class="report-metric">
                        <span class="metric-label">Blocked Attempts</span>
                        <span class="metric-value">156</span>
                    </div>
                    <div class="report-metric">
                        <span class="metric-label">Failed Logins</span>
                        <span class="metric-value">89</span>
                    </div>
                    <div class="report-metric">
                        <span class="metric-label">Security Score</span>
                        <span class="metric-value">95/100</span>
                    </div>
                </div>
                <div class="report-actions">
                    <button class="btn btn-small" onclick="viewReport('security-report')">View Details</button>
                    <button class="btn btn-small btn-secondary" onclick="exportReport('security-report')">Export</button>
                </div>
            </div>
        </div>

        <!-- Top Content Table -->
        <div class="chart-container">
            <div class="chart-header">
                <div class="chart-title">Top Performing Content</div>
                <button class="btn btn-small btn-secondary" onclick="exportTable('top-content')">Export Table</button>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Content Title</th>
                        <th>Type</th>
                        <th>Views</th>
                        <th>Engagement</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>AI Engineer Career Path</td>
                        <td>Career Trend</td>
                        <td>3,421</td>
                        <td>78.5%</td>
                        <td>2 days ago</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Data Scientist Roadmap</td>
                        <td>Career Path</td>
                        <td>2,847</td>
                        <td>72.3%</td>
                        <td>1 week ago</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Cybersecurity Analyst Guide</td>
                        <td>Career Trend</td>
                        <td>2,156</td>
                        <td>69.8%</td>
                        <td>3 days ago</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Full Stack Development Path</td>
                        <td>Career Path</td>
                        <td>1,923</td>
                        <td>65.4%</td>
                        <td>5 days ago</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Cloud Engineer Trends</td>
                        <td>Career Trend</td>
                        <td>1,678</td>
                        <td>61.2%</td>
                        <td>1 week ago</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Export Section -->
        <div class="export-section">
            <div class="export-title">Export Reports</div>
            <p style="margin-bottom: 20px; color: #666;">Generate and download comprehensive reports in various formats.</p>
            <div class="export-options">
                <button class="btn" onclick="exportReport('pdf')">
                    <img src="https://img.icons8.com/color/20/000000/pdf.png" alt="PDF" style="margin-right: 8px;">
                    Export as PDF
                </button>
                <button class="btn btn-secondary" onclick="exportReport('excel')">
                    <img src="https://img.icons8.com/color/20/000000/microsoft-excel-2019.png" alt="Excel" style="margin-right: 8px;">
                    Export as Excel
                </button>
                <button class="btn btn-warning" onclick="exportReport('csv')">
                    <img src="https://img.icons8.com/color/20/000000/csv.png" alt="CSV" style="margin-right: 8px;">
                    Export as CSV
                </button>
                                <button class="btn" onclick="exportReport('json')" style="background: linear-gradient(135deg, #6f42c1, #8a63d2);">
                    <img src="https://img.icons8.com/color/20/000000/json.png" alt="JSON" style="margin-right: 8px;">
                    Export as JSON
                </button>
            </div>
        </div>

        <!-- Scheduled Reports Section -->
        <div class="chart-container">
            <div class="chart-header">
                <div class="chart-title">Scheduled Reports</div>
                <button class="btn btn-secondary btn-small" onclick="addScheduledReport()">Add Schedule</button>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Report Name</th>
                        <th>Type</th>
                        <th>Frequency</th>
                        <th>Recipients</th>
                        <th>Next Run</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Weekly User Analytics</td>
                        <td>User Analytics</td>
                        <td>Weekly</td>
                        <td>admin@company.com</td>
                        <td>Dec 30, 2024 09:00</td>
                        <td><span class="status-badge status-published">Active</span></td>
                        <td>
                            <button class="btn btn-small" onclick="editSchedule(1)">Edit</button>
                            <button class="btn btn-small btn-danger" onclick="deleteSchedule(1)">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Monthly Performance Report</td>
                        <td>System Performance</td>
                        <td>Monthly</td>
                        <td>team@company.com</td>
                        <td>Jan 1, 2025 08:00</td>
                        <td><span class="status-badge status-published">Active</span></td>
                        <td>
                            <button class="btn btn-small" onclick="editSchedule(2)">Edit</button>
                            <button class="btn btn-small btn-danger" onclick="deleteSchedule(2)">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Daily Security Summary</td>
                        <td>Security Report</td>
                        <td>Daily</td>
                        <td>security@company.com</td>
                        <td>Dec 28, 2024 06:00</td>
                        <td><span class="status-badge status-draft">Paused</span></td>
                        <td>
                            <button class="btn btn-small btn-secondary" onclick="resumeSchedule(3)">Resume</button>
                            <button class="btn btn-small btn-danger" onclick="deleteSchedule(3)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Recent Activity Log -->
        <div class="chart-container">
            <div class="chart-header">
                <div class="chart-title">Recent Report Activity</div>
                <button class="btn btn-small btn-warning" onclick="clearActivityLog()">Clear Log</button>
            </div>
            <div style="max-height: 300px; overflow-y: auto;">
                <div style="padding: 15px; border-bottom: 1px solid #e9ecef; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong>User Analytics Report</strong> generated successfully
                        <br><small style="color: #666;">Generated by Admin User</small>
                    </div>
                    <div style="text-align: right; color: #666; font-size: 0.9rem;">
                        Dec 27, 2024<br>14:30
                    </div>
                </div>
                <div style="padding: 15px; border-bottom: 1px solid #e9ecef; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong>Content Performance Report</strong> exported as PDF
                        <br><small style="color: #666;">Exported by Content Manager</small>
                    </div>
                    <div style="text-align: right; color: #666; font-size: 0.9rem;">
                        Dec 27, 2024<br>11:15
                    </div>
                </div>
                <div style="padding: 15px; border-bottom: 1px solid #e9ecef; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong>Weekly Security Report</strong> sent to recipients
                        <br><small style="color: #666;">Automated schedule</small>
                    </div>
                    <div style="text-align: right; color: #666; font-size: 0.9rem;">
                        Dec 26, 2024<br>09:00
                    </div>
                </div>
                <div style="padding: 15px; border-bottom: 1px solid #e9ecef; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong>System Performance Report</strong> generated with warnings
                        <br><small style="color: #666;">Generated by System Admin</small>
                    </div>
                    <div style="text-align: right; color: #666; font-size: 0.9rem;">
                        Dec 25, 2024<br>16:45
                    </div>
                </div>
                <div style="padding: 15px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong>Geographic Analytics Report</strong> scheduled for tomorrow
                        <br><small style="color: #666;">Scheduled by Marketing Team</small>
                    </div>
                    <div style="text-align: right; color: #666; font-size: 0.9rem;">
                        Dec 25, 2024<br>10:20
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-links">
            <a href="#privacy">Privacy Policy</a>
            <a href="#terms">Terms of Service</a>
            <a href="#support">Support</a>
            <a href="#documentation">Documentation</a>
        </div>
        <p>&copy; 2024 Career Guidance Platform. All rights reserved.</p>
    </footer>

    <script>
        // Report functionality
        function updateReports() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const period = document.getElementById('reportPeriod').value;
            
            console.log('Updating reports with:', { startDate, endDate, period });
            
            // Show loading state
            const statsCards = document.querySelectorAll('.stat-card');
            statsCards.forEach(card => {
                card.style.opacity = '0.6';
            });
            
            // Simulate API call
            setTimeout(() => {
                statsCards.forEach(card => {
                    card.style.opacity = '1';
                });
                showNotification('Reports updated successfully!', 'success');
            }, 1000);
        }

        function generateReport() {
            showNotification('Generating comprehensive report...', 'info');
            
            // Simulate report generation
            setTimeout(() => {
                showNotification('Report generated successfully!', 'success');
            }, 2000);
        }

        function scheduleReport() {
            const modal = createModal('Schedule Report', `
                <form id="scheduleForm">
                    <div class="form-group">
                        <label>Report Type</label>
                        <select required>
                            <option value="">Select Report Type</option>
                            <option value="user-analytics">User Analytics</option>
                            <option value="content-performance">Content Performance</option>
                            <option value="system-performance">System Performance</option>
                            <option value="security-report">Security Report</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Frequency</label>
                        <select required>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Recipients (comma-separated emails)</label>
                        <input type="email" placeholder="admin@company.com, team@company.com" required>
                    </div>
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="datetime-local" required>
                    </div>
                    <button type="submit" class="btn btn-secondary">Schedule Report</button>
                </form>
            `);
            
            document.getElementById('scheduleForm').onsubmit = function(e) {
                e.preventDefault();
                closeModal();
                showNotification('Report scheduled successfully!', 'success');
            };
        }

        function viewReport(reportType) {
            showNotification(`Opening ${reportType} detailed view...`, 'info');
            // In a real application, this would navigate to a detailed report page
        }

        function exportReport(reportType) {
            showNotification(`Exporting ${reportType} report...`, 'info');
            
            // Simulate export process
            setTimeout(() => {
                showNotification(`${reportType} report exported successfully!`, 'success');
            }, 1500);
        }

        function exportTable(tableType) {
            showNotification(`Exporting ${tableType} table...`, 'info');
            
            setTimeout(() => {
                showNotification('Table exported successfully!', 'success');
            }, 1000);
        }

        function addScheduledReport() {
            scheduleReport();
        }

        function editSchedule(id) {
            showNotification(`Editing schedule ${id}...`, 'info');
        }

        function deleteSchedule(id) {
            if (confirm('Are you sure you want to delete this scheduled report?')) {
                showNotification(`Schedule ${id} deleted successfully!`, 'success');
            }
        }

        function resumeSchedule(id) {
            showNotification(`Schedule ${id} resumed successfully!`, 'success');
        }

        function clearActivityLog() {
            if (confirm('Are you sure you want to clear the activity log?')) {
                showNotification('Activity log cleared successfully!', 'success');
            }
        }

        // Utility functions
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                border-radius: 8px;
                color: white;
                font-weight: 600;
                z-index: 1000;
                animation: slideIn 0.3s ease;
            `;
            
            const colors = {
                success: '#28a745',
                error: '#dc3545',
                warning: '#ffc107',
                info: '#004080'
            };
            
            notification.style.backgroundColor = colors[type] || colors.info;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        function createModal(title, content) {
            const modal = document.createElement('div');
            modal.className = 'modal';
            modal.style.display = 'block';
            modal.innerHTML = `
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">${title}</h3>
                        <span class="close" onclick="closeModal()">&times;</span>
                    </div>
                    ${content}
                </div>
            `;
            
            document.body.appendChild(modal);
            return modal;
        }

        function closeModal() {
            const modal = document.querySelector('.modal');
            if (modal) {
                modal.remove();
            }
        }

        // Add CSS animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            
            .status-badge {
                padding: 5px 12px;
                border-radius: 20px;
                font-size: 0.8rem;
                font-weight: 600;
                text-transform: uppercase;
            }
            
            .status-published {
                background-color: #d4edda;
                color: #155724;
            }
            
            .status-draft {
                background-color: #fff3cd;
                color: #856404;
            }
            
            .form-group {
                margin-bottom: 20px;
            }
            
            .form-group label {
                display: block;
                margin-bottom: 8px;
                font-weight: 600;
                color: #333;
            }
            
            .form-group input, .form-group select {
                width: 100%;
                padding: 12px 15px;
                border: 2px solid #e0e9ff;
                border-radius: 8px;
                font-size: 1rem;
                transition: border-color 0.3s ease;
            }
            
            .form-group input:focus, .form-group select:focus {
                outline: none;
                border-color: #004080;
            }
            
            .modal {
                position: fixed;
                z-index: 1000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
            }
            
            .modal-content {
                background-color: white;
                margin: 5% auto;
                padding: 30px;
                border-radius: 15px;
                width: 90%;
                max-width: 600px;
                max-height: 80vh;
                overflow-y: auto;
            }
            
            .modal-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
            }
            
            .modal-title {
                color: #004080;
                font-size: 1.5rem;
                font-weight: bold;
            }
            
            .close {
                color: #aaa;
                font-size: 28px;
                font-weight: bold;
                cursor: pointer;
            }
            
            .close:hover {
                color: #000;
            }
        `;
        document.head.appendChild(style);

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Set default dates
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            
            document.getElementById('startDate').value = firstDay.toISOString().split
            document.getElementById('startDate').value = firstDay.toISOString().split('T')[0];
            document.getElementById('endDate').value = today.toISOString().split('T')[0];
            
            // Add event listeners for chart type changes
            document.getElementById('chartType').addEventListener('change', function() {
                const chartPlaceholder = document.querySelector('.chart-placeholder');
                const chartType = this.value;
                chartPlaceholder.innerHTML = `📊 ${chartType.charAt(0).toUpperCase() + chartType.slice(1)} Chart Will Be Displayed Here<br><small>Chart.js or similar library integration required</small>`;
            });
            
            // Auto-refresh stats every 30 seconds
            setInterval(function() {
                updateStats();
            }, 30000);
        });

        function updateStats() {
            // Simulate real-time stats update
            const statNumbers = document.querySelectorAll('.stat-number');
            statNumbers.forEach(stat => {
                const currentValue = parseInt(stat.textContent.replace(/,/g, ''));
                const change = Math.floor(Math.random() * 10) - 5; // Random change between -5 and +5
                const newValue = Math.max(0, currentValue + change);
                stat.textContent = newValue.toLocaleString();
            });
        }

        // Advanced filtering functionality
        function applyAdvancedFilters() {
            const modal = createModal('Advanced Filters', `
                <form id="advancedFiltersForm">
                    <div class="form-group">
                        <label>User Segments</label>
                        <select multiple style="height: 100px;">
                            <option value="new">New Users</option>
                            <option value="returning">Returning Users</option>
                            <option value="premium">Premium Users</option>
                            <option value="inactive">Inactive Users</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Content Categories</label>
                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                            <label style="display: flex; align-items: center; gap: 5px;">
                                <input type="checkbox" value="trends"> Career Trends
                            </label>
                            <label style="display: flex; align-items: center; gap: 5px;">
                                <input type="checkbox" value="paths"> Career Paths
                            </label>
                            <label style="display: flex; align-items: center; gap: 5px;">
                                <input type="checkbox" value="articles"> Articles
                            </label>
                            <label style="display: flex; align-items: center; gap: 5px;">
                                <input type="checkbox" value="resources"> Resources
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Geographic Regions</label>
                        <select>
                            <option value="">All Regions</option>
                            <option value="metro-manila">Metro Manila</option>
                            <option value="cebu">Cebu</option>
                            <option value="davao">Davao</option>
                            <option value="international">International</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Device Types</label>
                        <div style="display: flex; gap: 20px;">
                            <label style="display: flex; align-items: center; gap: 5px;">
                                <input type="checkbox" value="mobile" checked> Mobile
                            </label>
                            <label style="display: flex; align-items: center; gap: 5px;">
                                <input type="checkbox" value="desktop" checked> Desktop
                            </label>
                            <label style="display: flex; align-items: center; gap: 5px;">
                                <input type="checkbox" value="tablet"> Tablet
                            </label>
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px; justify-content: flex-end;">
                        <button type="button" class="btn btn-warning" onclick="resetFilters()">Reset</button>
                        <button type="submit" class="btn btn-secondary">Apply Filters</button>
                    </div>
                </form>
            `);
            
            document.getElementById('advancedFiltersForm').onsubmit = function(e) {
                e.preventDefault();
                closeModal();
                showNotification('Advanced filters applied successfully!', 'success');
                updateReports();
            };
        }

        function resetFilters() {
            document.getElementById('advancedFiltersForm').reset();
            showNotification('Filters reset to default', 'info');
        }

        // Real-time data simulation
        function startRealTimeUpdates() {
            const realTimeBtn = document.createElement('button');
            realTimeBtn.className = 'btn btn-secondary btn-small';
            realTimeBtn.textContent = 'Start Real-time Updates';
            realTimeBtn.onclick = function() {
                if (this.textContent === 'Start Real-time Updates') {
                    this.textContent = 'Stop Real-time Updates';
                    this.style.background = 'linear-gradient(135deg, #dc3545, #c82333)';
                    startRealTimeInterval();
                } else {
                    this.textContent = 'Start Real-time Updates';
                    this.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
                    stopRealTimeInterval();
                }
            };
            
            document.querySelector('.report-controls > div:last-child').appendChild(realTimeBtn);
        }

        let realTimeInterval;
        function startRealTimeInterval() {
            realTimeInterval = setInterval(() => {
                updateStats();
                showNotification('Data updated in real-time', 'info');
            }, 5000);
        }

        function stopRealTimeInterval() {
            if (realTimeInterval) {
                clearInterval(realTimeInterval);
                realTimeInterval = null;
            }
        }

        // Data comparison functionality
        function compareReports() {
            const modal = createModal('Compare Reports', `
                <form id="compareForm">
                    <div class="form-group">
                        <label>Primary Period</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="date" id="primary-start" required>
                            <span>to</span>
                            <input type="date" id="primary-end" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Comparison Period</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="date" id="compare-start" required>
                            <span>to</span>
                            <input type="date" id="compare-end" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Metrics to Compare</label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <label style="display: flex; align-items: center; gap: 5px;">
                                <input type="checkbox" value="users" checked> User Metrics
                            </label>
                            <label style="display: flex; align-items: center; gap: 5px;">
                                <input type="checkbox" value="content" checked> Content Performance
                            </label>
                            <label style="display: flex; align-items: center; gap: 5px;">
                                <input type="checkbox" value="engagement" checked> Engagement
                            </label>
                            <label style="display: flex; align-items: center; gap: 5px;">
                                <input type="checkbox" value="system" checked> System Performance
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-secondary">Generate Comparison</button>
                </form>
            `);
            
            document.getElementById('compareForm').onsubmit = function(e) {
                e.preventDefault();
                closeModal();
                showNotification('Generating comparison report...', 'info');
                
                setTimeout(() => {
                    showNotification('Comparison report generated successfully!', 'success');
                }, 2000);
            };
        }

        // Add comparison button to controls
        document.addEventListener('DOMContentLoaded', function() {
            const compareBtn = document.createElement('button');
            compareBtn.className = 'btn';
            compareBtn.textContent = 'Compare Periods';
            compareBtn.onclick = compareReports;
            compareBtn.style.background = 'linear-gradient(135deg, #6f42c1, #8a63d2)';
            
            document.querySelector('.report-controls > div:last-child').appendChild(compareBtn);
            
            // Add advanced filters button
            const filtersBtn = document.createElement('button');
            filtersBtn.className = 'btn btn-warning';
            filtersBtn.textContent = 'Advanced Filters';
            filtersBtn.onclick = applyAdvancedFilters;
            
            document.querySelector('.report-controls > div:last-child').appendChild(filtersBtn);
            
            // Initialize real-time updates button
            startRealTimeUpdates();
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey || e.metaKey) {
                switch(e.key) {
                    case 'r':
                        e.preventDefault();
                        updateReports();
                        break;
                    case 'g':
                        e.preventDefault();
                        generateReport();
                        break;
                    case 's':
                        e.preventDefault();
                        scheduleReport();
                        break;
                    case 'e':
                        e.preventDefault();
                        exportReport('pdf');
                        break;
                }
            }
        });

        // Add keyboard shortcuts info
        const shortcutsInfo = document.createElement('div');
        shortcutsInfo.style.cssText = `
            position: fixed;
            bottom: 20px;
            left: 20px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 0.8rem;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
        `;
        shortcutsInfo.innerHTML = `
            <strong>Keyboard Shortcuts:</strong><br>
            Ctrl+R: Update Reports<br>
            Ctrl+G: Generate Report<br>
            Ctrl+S: Schedule Report<br>
            Ctrl+E: Export as PDF
        `;
        document.body.appendChild(shortcutsInfo);

        // Show shortcuts on hover over help icon
        const helpIcon = document.createElement('div');
        helpIcon.style.cssText = `
            position: fixed;
            bottom: 20px;
            left: 20px;
            width: 40px;
            height: 40px;
            background: #004080;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            cursor: pointer;
            z-index: 1001;
        `;
        helpIcon.textContent = '?';
        helpIcon.onmouseenter = () => shortcutsInfo.style.opacity = '1';
        helpIcon.onmouseleave = () => shortcutsInfo.style.opacity = '0';
        document.body.appendChild(helpIcon);
    </script>
</body>
</html>



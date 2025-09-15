# Reports System Documentation

## Overview
A comprehensive reports system has been implemented for the Laravel dashboard with 9 different report types, each providing detailed analytics and insights into various aspects of the platform.

## Implemented Reports

### 1. Sales Report (`/admin/reports/sales`)
- **Purpose**: Track and analyze sales performance
- **Features**:
  - Total sales with growth metrics
  - Sales by month (line chart)
  - Sales by category (doughnut chart)
  - Top selling courses table
  - Date range filtering
  - Export functionality (Excel/PDF)
- **Controller**: `Reports\SalesReportController`
- **View**: `resources/views/reports/sales/index.blade.php`

### 2. Enrollments Report (`/admin/reports/enrollments`)
- **Purpose**: Analyze student enrollment patterns
- **Features**:
  - Total enrollments with growth tracking
  - Monthly enrollment trends (bar chart)
  - Enrollments by category (doughnut chart)
  - Top enrolled courses ranking
  - Export to Excel functionality
- **Controller**: `Reports\EnrollmentReportController`
- **View**: `resources/views/reports/enrollments/index.blade.php`

### 3. Payments Report (`/admin/reports/payments`)
- **Purpose**: Monitor financial transactions
- **Features**:
  - Total payments and transactions
  - Payment status distribution
  - Average transaction value
  - Recent payments table
  - Top paying customers
- **Controller**: `Reports\PaymentReportController`
- **View**: `resources/views/reports/payments/index.blade.php`

### 4. Attendance Report (`/admin/reports/attendance`)
- **Purpose**: Track student engagement
- **Features**:
  - Total vs active students
  - Overall attendance rate
  - Visual attendance percentage
- **Controller**: `Reports\AttendanceReportController`
- **View**: `resources/views/reports/attendance/index.blade.php`

### 5. Course-wise Enrollments (`/admin/reports/course-wise-enrollments`)
- **Purpose**: Detailed course performance analysis
- **Features**:
  - Enrollment count per course
  - Revenue per course
  - Course categorization
  - Paginated results
- **Controller**: `Reports\CourseWiseEnrollmentController`
- **View**: `resources/views/reports/course-wise-enrollments/index.blade.php`

### 6. Promo Code Statistics (`/admin/reports/promo-code-statistics`)
- **Purpose**: Track discount code usage
- **Features**:
  - Total vs active coupons
  - Usage count per coupon
  - Total discount applied
  - Coupon status tracking
- **Controller**: `Reports\PromoCodeStatisticsController`
- **View**: `resources/views/reports/promo-code-statistics/index.blade.php`

### 7. Bandwidth Consumption (`/admin/reports/bandwidth-consumption`)
- **Purpose**: Monitor server resource usage
- **Features**:
  - Monthly bandwidth usage (line chart)
  - Total bandwidth consumption
  - 12-month trend analysis
- **Controller**: `Reports\BandwidthConsumptionController`
- **View**: `resources/views/reports/bandwidth-consumption/index.blade.php`

### 8. Storage Consumption (`/admin/reports/storage-consumption`)
- **Purpose**: Track storage usage
- **Features**:
  - Total storage breakdown
  - Public vs app storage
  - Per-user storage usage
  - Visual usage indicators
- **Controller**: `Reports\StorageConsumptionController`
- **View**: `resources/views/reports/storage-consumption/index.blade.php`

### 9. Scheduled Tasks (`/admin/reports/scheduled-tasks`)
- **Purpose**: Monitor system maintenance tasks
- **Features**:
  - Task status overview
  - Last run and next run times
  - Task duration tracking
  - Manual task execution
  - Log viewing capability
- **Controller**: `Reports\ScheduledTasksController`
- **View**: `resources/views/reports/scheduled-tasks/index.blade.php`

## Technical Implementation

### Routes
All report routes are grouped under `/admin/reports/` with proper naming:
```php
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/sales', [SalesReportController::class, 'index'])->name('sales');
    // ... other routes
});
```

### Controllers
Each report has its own controller in the `Reports` namespace with:
- Date filtering capabilities
- Data aggregation and analysis
- Chart data preparation
- Export functionality hooks

### Views
All views follow a consistent design pattern:
- Modern glassmorphism UI with Tailwind CSS
- Responsive grid layouts
- Interactive charts using Chart.js
- Smooth animations and transitions
- Arabic RTL support

### Sidebar Integration
The Reports section is added to the admin sidebar with:
- Collapsible menu structure
- Individual icons for each report type
- Active state highlighting
- Hover effects and transitions

## Features

### Visual Elements
- **Charts**: Line charts, bar charts, doughnut charts using Chart.js
- **Statistics Cards**: Color-coded metrics with icons
- **Progress Bars**: Visual representation of percentages
- **Status Indicators**: Color-coded status badges

### Functionality
- **Date Filtering**: Start and end date selection for all reports
- **Export Options**: Excel and PDF export capabilities
- **Pagination**: For large datasets
- **Search and Filter**: Built-in filtering capabilities
- **Real-time Updates**: Refresh functionality for dynamic data

### Design
- **Responsive**: Mobile-first responsive design
- **Accessibility**: Proper ARIA labels and keyboard navigation
- **Performance**: Optimized queries and lazy loading
- **Consistency**: Unified design language across all reports

## Usage

### Accessing Reports
1. Login as admin user
2. Navigate to the sidebar
3. Click on "التقارير" (Reports) section
4. Select desired report type

### Filtering Data
1. Use the date range picker in the filters section
2. Click "تطبيق المرشح" (Apply Filter) to update data
3. Charts and statistics will update accordingly

### Exporting Data
1. Click export buttons (Excel/PDF) in the header
2. Data will be exported in the selected format
3. Export includes filtered date range

## Future Enhancements

### Planned Features (Not Yet Implemented)
- **GST Reports**: Tax reporting functionality
- **Branch Statistics**: Multi-branch analytics
- **Wallet Recharge**: Payment wallet tracking

### Potential Improvements
- Real-time data updates
- Advanced filtering options
- Custom report builder
- Automated report scheduling
- Email report delivery
- Data visualization enhancements
- API endpoints for external access

## Dependencies
- Laravel Framework
- Chart.js for visualizations
- Tailwind CSS for styling
- Alpine.js for interactions
- Tabler Icons for UI elements

## File Structure
```
app/Http/Controllers/Reports/
├── SalesReportController.php
├── EnrollmentReportController.php
├── PaymentReportController.php
├── AttendanceReportController.php
├── CourseWiseEnrollmentController.php
├── PromoCodeStatisticsController.php
├── BandwidthConsumptionController.php
├── StorageConsumptionController.php
└── ScheduledTasksController.php

resources/views/reports/
├── sales/index.blade.php
├── enrollments/index.blade.php
├── payments/index.blade.php
├── attendance/index.blade.php
├── course-wise-enrollments/index.blade.php
├── promo-code-statistics/index.blade.php
├── bandwidth-consumption/index.blade.php
├── storage-consumption/index.blade.php
└── scheduled-tasks/index.blade.php
```

This reports system provides a comprehensive analytics solution for the Laravel dashboard, offering detailed insights into various aspects of the platform's performance and usage.

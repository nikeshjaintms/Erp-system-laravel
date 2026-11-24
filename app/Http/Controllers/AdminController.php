<?php

namespace App\Http\Controllers;

use App\Services\ItemService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the main ERP Admin Dashboard.
     */
    public function dashboard()
    {
        // Sample ERP KPI metrics
        $metrics = [
            'revenue' => [
                'value' => '$148,920.00',
                'change' => '+14.6%',
                'trend' => 'up',
                'subtext' => 'vs last month ($129,950)',
            ],
            'orders' => [
                'value' => '1,428',
                'change' => '+8.4%',
                'trend' => 'up',
                'subtext' => '94 orders today',
            ],
            'inventory_value' => [
                'value' => '$582,310.00',
                'change' => '-2.1%',
                'trend' => 'down',
                'subtext' => 'Across 4 regional warehouses',
            ],
            'pending_shipments' => [
                'value' => '38',
                'change' => 'High',
                'trend' => 'up',
                'subtext' => '12 dispatching today',
            ],
        ];

        // Recent Orders list
        $recentOrders = [
            [
                'id' => 'ORD-9821',
                'customer' => 'Acme Industrial Corp',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=60&auto=format&fit=crop&q=80',
                'items_count' => 14,
                'total' => '$14,250.00',
                'payment_status' => 'Paid',
                'order_status' => 'Delivered',
                'date' => 'Today, 10:24 AM',
            ],
            [
                'id' => 'ORD-9820',
                'customer' => 'Apex Logistics Ltd',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=60&auto=format&fit=crop&q=80',
                'items_count' => 8,
                'total' => '$8,720.50',
                'payment_status' => 'Paid',
                'order_status' => 'In Transit',
                'date' => 'Today, 09:12 AM',
            ],
            [
                'id' => 'ORD-9819',
                'customer' => 'Vanguard Technologies',
                'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=60&auto=format&fit=crop&q=80',
                'items_count' => 32,
                'total' => '$29,480.00',
                'payment_status' => 'Pending',
                'order_status' => 'Processing',
                'date' => 'Yesterday',
            ],
            [
                'id' => 'ORD-9818',
                'customer' => 'Global Marine Supply',
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=60&auto=format&fit=crop&q=80',
                'items_count' => 5,
                'total' => '$3,190.00',
                'payment_status' => 'Paid',
                'order_status' => 'Delivered',
                'date' => 'Yesterday',
            ],
            [
                'id' => 'ORD-9817',
                'customer' => 'Horizon Energy Systems',
                'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=60&auto=format&fit=crop&q=80',
                'items_count' => 19,
                'total' => '$18,340.00',
                'payment_status' => 'Failed',
                'order_status' => 'On Hold',
                'date' => '2 days ago',
            ],
        ];

        // Low stock alerts
        $lowStockItems = [
            ['sku' => 'SKU-0941', 'name' => 'High-Torque Hydraulic Pump', 'stock' => 4, 'min' => 20, 'status' => 'Critical'],
            ['sku' => 'SKU-2318', 'name' => 'Industrial Steel Flange 4"', 'stock' => 12, 'min' => 50, 'status' => 'Low Stock'],
            ['sku' => 'SKU-8842', 'name' => 'Microcontroller Board v2', 'stock' => 18, 'min' => 60, 'status' => 'Low Stock'],
            ['sku' => 'SKU-1092', 'name' => 'Polymer Gasket Ring Set', 'stock' => 2, 'min' => 30, 'status' => 'Critical'],
        ];

        return view('admin.dashboard', compact('metrics', 'recentOrders', 'lowStockItems'));
    }

    /**
     * Display the ERP Inventory Management page with real database inventory.
     */
    public function inventory(Request $request, ItemService $itemService)
    {
        $filters = $request->only(['search', 'category', 'status', 'sort_by', 'sort_direction']);
        $items = $itemService->getPaginatedItems($filters, 15);
        $metrics = $itemService->getInventoryMetrics();
        $categories = ['Mechanical Parts', 'Electronics & Motors', 'Hydraulics', 'Pneumatics', 'Hardware & Fasteners', 'Sensors & Automation'];

        return view('admin.stock.index', compact('items', 'metrics', 'categories', 'filters'));
    }

    /**
     * Display the ERP Sales & Orders page.
     */
    public function orders()
    {
        $orders = [
            [
                'order_no' => 'SO-2026-0042',
                'customer' => 'Acme Industrial Corp',
                'customer_code' => 'CUST-881',
                'date' => '2026-10-17',
                'amount' => '$14,250.00',
                'tax' => '$1,282.50',
                'payment' => 'Paid',
                'status' => 'Delivered',
                'sales_rep' => 'Alex Turner',
            ],
            [
                'order_no' => 'SO-2026-0041',
                'customer' => 'Apex Logistics Ltd',
                'customer_code' => 'CUST-742',
                'date' => '2026-10-16',
                'amount' => '$8,720.50',
                'tax' => '$784.85',
                'payment' => 'Paid',
                'status' => 'In Transit',
                'sales_rep' => 'David Miller',
            ],
            [
                'order_no' => 'SO-2026-0040',
                'customer' => 'Vanguard Technologies',
                'customer_code' => 'CUST-619',
                'date' => '2026-10-15',
                'amount' => '$29,480.00',
                'tax' => '$2,653.20',
                'payment' => 'Pending',
                'status' => 'Processing',
                'sales_rep' => 'Elena Rostova',
            ],
            [
                'order_no' => 'SO-2026-0039',
                'customer' => 'Global Marine Supply',
                'customer_code' => 'CUST-403',
                'date' => '2026-10-14',
                'amount' => '$3,190.00',
                'tax' => '$287.10',
                'payment' => 'Paid',
                'status' => 'Delivered',
                'sales_rep' => 'Alex Turner',
            ],
            [
                'order_no' => 'SO-2026-0038',
                'customer' => 'Horizon Energy Systems',
                'customer_code' => 'CUST-912',
                'date' => '2026-10-12',
                'amount' => '$18,340.00',
                'tax' => '$1,650.60',
                'payment' => 'Overdue',
                'status' => 'On Hold',
                'sales_rep' => 'Marcus Vance',
            ],
        ];

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the Customers / CRM page.
     */
    public function customers()
    {
        $customers = [
            [
                'code' => 'CUST-881',
                'name' => 'Acme Industrial Corp',
                'contact_person' => 'Robert Fox',
                'email' => 'robert.fox@acmecorp.com',
                'phone' => '+1 (555) 234-8901',
                'city' => 'Chicago, IL',
                'credit_limit' => '$100,000.00',
                'balance' => '$14,250.00',
                'orders_count' => 42,
                'status' => 'Active',
            ],
            [
                'code' => 'CUST-742',
                'name' => 'Apex Logistics Ltd',
                'contact_person' => 'Jessica Chen',
                'email' => 'jessica@apexlogistics.io',
                'phone' => '+1 (555) 876-5432',
                'city' => 'Atlanta, GA',
                'credit_limit' => '$75,000.00',
                'balance' => '$8,720.50',
                'orders_count' => 29,
                'status' => 'Active',
            ],
            [
                'code' => 'CUST-619',
                'name' => 'Vanguard Technologies',
                'contact_person' => 'Michael Sterling',
                'email' => 'm.sterling@vanguardtech.com',
                'phone' => '+1 (555) 345-6789',
                'city' => 'Austin, TX',
                'credit_limit' => '$150,000.00',
                'balance' => '$29,480.00',
                'orders_count' => 64,
                'status' => 'Active',
            ],
            [
                'code' => 'CUST-403',
                'name' => 'Global Marine Supply',
                'contact_person' => 'Liam Henderson',
                'email' => 'liam@globalmarine.com',
                'phone' => '+1 (555) 901-2345',
                'city' => 'Seattle, WA',
                'credit_limit' => '$50,000.00',
                'balance' => '$0.00',
                'orders_count' => 15,
                'status' => 'Active',
            ],
            [
                'code' => 'CUST-912',
                'name' => 'Horizon Energy Systems',
                'contact_person' => 'Claire Dupont',
                'email' => 'c.dupont@horizonenergy.com',
                'phone' => '+1 (555) 432-1098',
                'city' => 'Denver, CO',
                'credit_limit' => '$80,000.00',
                'balance' => '$18,340.00',
                'orders_count' => 11,
                'status' => 'Credit Hold',
            ],
        ];

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Display System Settings.
     */
    public function settings()
    {
        return view('admin.settings.index');
    }

    /**
     * Download Commercial Tax Invoice PDF using Barryvdh\DomPDF.
     */
    public function downloadInvoice($orderNo = 'SO-2026-0042')
    {
        $order = [
            'order_no' => $orderNo,
            'customer' => 'Acme Industrial Corp',
            'customer_code' => 'CUST-881',
            'date' => '2026-10-17',
            'amount' => '$14,250.00',
            'tax' => '$1,282.50',
            'payment' => 'Paid',
            'status' => 'Delivered',
            'sales_rep' => 'Alex Turner',
        ];

        if (function_exists('activity')) {
            activity()
                ->withProperties(['order_no' => $orderNo, 'format' => 'PDF', 'ip' => request()->ip()])
                ->log("Downloaded Tax Invoice PDF for order {$orderNo}");
        }

        $pdf = Pdf::loadView('admin.pdf.invoice', compact('order'));

        return $pdf->download("invoice-{$orderNo}.pdf");
    }

    /**
     * Stream Invoice PDF for inline viewing.
     */
    public function streamInvoice($orderNo = 'SO-2026-0042')
    {
        $order = [
            'order_no' => $orderNo,
            'customer' => 'Acme Industrial Corp',
            'customer_code' => 'CUST-881',
            'date' => '2026-10-17',
            'amount' => '$14,250.00',
            'tax' => '$1,282.50',
            'payment' => 'Paid',
            'status' => 'Delivered',
            'sales_rep' => 'Alex Turner',
        ];

        $pdf = Pdf::loadView('admin.pdf.invoice', compact('order'));

        return $pdf->stream("invoice-{$orderNo}.pdf");
    }

    /**
     * Display Audit & Activity Logs using Spatie Activitylog.
     */
    public function activityLog()
    {
        $activities = [
            [
                'created_at' => '2026-10-17 12:15:30',
                'diff_for_humans' => 'Just now',
                'causer' => 'Sarah Jenkins',
                'role' => 'Super Admin',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=60&auto=format&fit=crop&q=80',
                'description' => 'Downloaded Commercial Tax Invoice PDF for #SO-2026-0042',
                'subject' => 'Invoice #SO-2026-0042',
                'event' => 'download',
                'ip' => '127.0.0.1',
            ],
            [
                'created_at' => '2026-10-17 11:45:10',
                'diff_for_humans' => '30 mins ago',
                'causer' => 'Sarah Jenkins',
                'role' => 'Super Admin',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=60&auto=format&fit=crop&q=80',
                'description' => 'Updated inventory stock threshold for SKU-0941',
                'subject' => 'Product: SKU-0941',
                'event' => 'updated',
                'ip' => '127.0.0.1',
            ],
            [
                'created_at' => '2026-10-17 10:24:00',
                'diff_for_humans' => '2 hours ago',
                'causer' => 'Alex Turner',
                'role' => 'Sales Executive',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=60&auto=format&fit=crop&q=80',
                'description' => 'Created Sales Order #SO-2026-0042 for Acme Industrial Corp',
                'subject' => 'Sales Order: SO-2026-0042',
                'event' => 'created',
                'ip' => '192.168.1.45',
            ],
            [
                'created_at' => '2026-10-17 09:00:15',
                'diff_for_humans' => '3 hours ago',
                'causer' => 'Sarah Jenkins',
                'role' => 'Super Admin',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=60&auto=format&fit=crop&q=80',
                'description' => 'Admin user authenticated successfully via ERP portal',
                'subject' => 'Auth: Session #882',
                'event' => 'login',
                'ip' => '127.0.0.1',
            ],
        ];

        return view('admin.activity-log.index', compact('activities'));
    }

    /**
     * Display Roles & Permissions using Spatie Permission.
     */
    public function roles()
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'description' => 'Full administrative access across all ERP departments and system configs.',
                'permissions' => ['view-dashboard', 'manage-inventory', 'manage-orders', 'manage-customers', 'manage-settings', 'view-activity-log', 'manage-roles-permissions'],
                'users_count' => 1,
            ],
            [
                'name' => 'Warehouse Manager',
                'description' => 'Manage multi-facility stock adjustments, safety thresholds, and packing slips.',
                'permissions' => ['view-dashboard', 'manage-inventory', 'manage-orders'],
                'users_count' => 3,
            ],
            [
                'name' => 'Sales Executive',
                'description' => 'Create commercial quotations, client orders, and view customer records.',
                'permissions' => ['view-dashboard', 'manage-orders', 'manage-customers'],
                'users_count' => 5,
            ],
            [
                'name' => 'Accountant',
                'description' => 'Process customer payments, tax deductions, and download invoice PDFs.',
                'permissions' => ['view-dashboard', 'manage-orders', 'view-activity-log'],
                'users_count' => 2,
            ],
        ];

        $permissionMatrix = [
            [
                'name' => 'view-dashboard',
                'description' => 'Access real-time KPI metrics and executive overview graphs',
                'module' => 'Dashboard',
                'roles' => ['Super Admin', 'Warehouse Manager', 'Sales Executive', 'Accountant'],
            ],
            [
                'name' => 'manage-inventory',
                'description' => 'Create SKUs, adjust quantities, transfer stock between hubs',
                'module' => 'Inventory',
                'roles' => ['Super Admin', 'Warehouse Manager'],
            ],
            [
                'name' => 'manage-orders',
                'description' => 'Book sales orders, dispatch shipments, generate invoices',
                'module' => 'Sales & Orders',
                'roles' => ['Super Admin', 'Warehouse Manager', 'Sales Executive', 'Accountant'],
            ],
            [
                'name' => 'manage-customers',
                'description' => 'Register clients, set credit limits, view balance ledgers',
                'module' => 'CRM',
                'roles' => ['Super Admin', 'Sales Executive'],
            ],
            [
                'name' => 'manage-settings',
                'description' => 'Configure legal entity, tax rates, SMTP, and daily backups',
                'module' => 'System',
                'roles' => ['Super Admin'],
            ],
            [
                'name' => 'view-activity-log',
                'description' => 'Inspect comprehensive audit trail records and login sessions',
                'module' => 'Security & Audit',
                'roles' => ['Super Admin', 'Accountant'],
            ],
            [
                'name' => 'manage-roles-permissions',
                'description' => 'Assign capabilities and define enterprise access roles',
                'module' => 'Security & Audit',
                'roles' => ['Super Admin'],
            ],
        ];

        return view('admin.roles.index', compact('roles', 'permissionMatrix'));
    }
}

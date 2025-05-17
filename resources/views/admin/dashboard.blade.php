<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ZhirTech | Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <span class="navbar-brand">Admin Dashboard</span>
      <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button class="btn btn-outline-light btn-sm">Logout</button>
      </form>
    </div>
  </nav>

  <div class="dashboard-container">
    <div class="container-fluid">
      <ul class="nav nav-tabs mb-0" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="services-tab" data-bs-toggle="tab" data-bs-target="#services" type="button" role="tab" aria-controls="services" aria-selected="true">Services</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab" aria-controls="orders" aria-selected="false">Orders</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="transactions-tab" data-bs-toggle="tab" data-bs-target="#transactions" type="button" role="tab" aria-controls="transactions" aria-selected="false">Transactions</button>
        </li>
      </ul>

      <div class="tab-content">
        <!-- Services Tab -->
        <div class="tab-pane fade show active" id="services" role="tabpanel" aria-labelledby="services-tab">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Services</h5>
            <button class="btn btn-primary btn-sm">
              <i class="bi bi-plus-lg"></i>
            </button>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered table-sm text-center align-middle" id="servicesTable">
              <thead class="table-light"> 
                <tr>
                  <th>No</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Price (RM)</th>
                  <th>Note</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($services as $index => $service)
                  <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $service->name }}</td>
                    <td>{{ $service->description }}</td>
                    <td>{{ number_format($service->price, 2) }}</td>
                    <td>{{ $service->note }}</td>
                    <td>
                      <div class="action-buttons">
                        <button class="btn btn-warning btn-sm edit-service" data-id="{{ $service->id }}" title="Edit">
                          <i class="bi bi-pencil-square text-white"></i>
                        </button>
                        <button class="btn btn-danger btn-sm delete-service" data-id="{{ $service->id }}" title="Delete">
                          <i class="bi bi-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="pagination-container">
            <nav aria-label="Services pagination">
              <ul class="pagination pagination-sm">
                <li class="page-item disabled">
                  <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>
                <li class="page-item active">
                  <a class="page-link" href="#">1</a>
                </li>
                <li class="page-item disabled">
                  <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>
              </ul>
            </nav>
            <div class="page-info">
              Showing all {{ count($services) }} entries
            </div>
          </div>
        </div>

        <!-- Orders Tab -->
        <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-tab">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Orders (Paid Transactions)</h5>
          </div>
          
          <!-- Add this filter section -->
          <div class="filter-section">
            <div class="filter-row">
              <div class="filter-group">
                <label for="serviceFilter" class="form-label">Service</label>
                <select class="form-select form-select-sm" id="serviceFilter">
                  <option value="all">All Services</option>
                  @foreach($services as $service)
                    <option value="{{ $service->name }}">{{ $service->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="filter-group">
                <button id="applyOrderFilter" class="btn btn-primary btn-sm">
                  <i class="bi bi-funnel"></i> Apply Filter
                </button>
                <button id="resetOrderFilter" class="btn btn-outline-secondary btn-sm ms-2">
                  <i class="bi bi-arrow-counterclockwise"></i> Reset
                </button>
              </div>
            </div>
          </div>
          
          <div class="table-responsive">
            <table class="table table-bordered table-sm text-center align-middle" id="ordersTable">
              <thead class="table-light">
                <tr>
                  <th>No</th>
                  <th>Reference Number</th>
                  <th>Service</th>
                  <th>Customer</th>
                  <th>Phone (WhatsApp)</th>
                  <th>Email</th>
                  <th>Amount (RM)</th>
                  <th>Created At</th>
                </tr>
              </thead>
              <tbody>
                @php
                  $paidOrders = $orders->where('status', 'paid')->values();
                @endphp
                @foreach ($paidOrders as $index => $transaction)
                  <tr class="order-row" data-service="{{ $transaction->service->name ?? '' }}">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $transaction->reference_no }}</td>
                    <td>{{ $transaction->service->name ?? '-' }}</td>
                    <td>{{ $transaction->customer_name }}</td>
                    <td>{{ $transaction->phone }}</td>
                    <td>{{ $transaction->email }}</td>
                    <td>{{ number_format($transaction->amount, 2) }}</td>
                    <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="pagination-container">
            <nav aria-label="Orders pagination">
              <ul class="pagination pagination-sm">
                <li class="page-item disabled">
                  <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>
                <li class="page-item active">
                  <a class="page-link" href="#">1</a>
                </li>
                <li class="page-item disabled">
                  <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>
              </ul>
            </nav>
            <div class="page-info">
              Showing <span id="showingOrderCount">{{ $paidOrders->count() }}</span> of {{ $paidOrders->count() }} entries
            </div>
          </div>
        </div>

        <!-- Transactions Tab -->
        <div class="tab-pane fade" id="transactions" role="tabpanel" aria-labelledby="transactions-tab">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Transactions</h5>
          </div>
          
          <!-- Add this filter section -->
          <div class="filter-section">
            <div class="filter-row">
              <div class="filter-group">
                <label for="statusFilter" class="form-label">Status</label>
                <select class="form-select form-select-sm" id="statusFilter">
                  <option value="all">All Statuses</option>
                  <option value="paid">Paid</option>
                  <option value="pending">Pending</option>
                  <option value="failed">Failed</option>
                </select>
              </div>
              <div class="filter-group">
                <button id="applyFilter" class="btn btn-primary btn-sm">
                  <i class="bi bi-funnel"></i> Apply Filter
                </button>
                <button id="resetFilter" class="btn btn-outline-secondary btn-sm ms-2">
                  <i class="bi bi-arrow-counterclockwise"></i> Reset
                </button>
              </div>
            </div>
          </div>
          
          <div class="table-responsive">
            <table class="table table-bordered table-sm text-center align-middle" id="transactionsTable">
              <thead class="table-light">
                <tr>
                  <th>No</th>
                  <th>Reference Number</th>
                  <th>Service</th>
                  <th>Amount (RM)</th>
                  <th>Status</th>
                  <th>Created At</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($orders as $index => $order)
                  <tr class="transaction-row" data-status="{{ $order->status }}">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $order->reference_no }}</td>
                    <td>{{ $order->service->name ?? '-' }}</td>
                    <td>{{ number_format($order->amount, 2) }}</td>
                    <td>
                      @php
                        $badgeClass = match($order->status) {
                            'paid' => 'bg-success',
                            'failed' => 'bg-danger',
                            default => 'bg-warning',
                        };
                      @endphp
                      <span class="badge {{ $badgeClass }}">
                        {{ ucfirst($order->status) }}
                      </span>
                    </td>
                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="pagination-container">
            <nav aria-label="Transactions pagination">
              <ul class="pagination pagination-sm">
                <li class="page-item disabled">
                  <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>
                <li class="page-item active">
                  <a class="page-link" href="#">1</a>
                </li>
                <li class="page-item disabled">
                  <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>
              </ul>
            </nav>
            <div class="page-info">
              Showing <span id="showingCount">{{ count($orders) }}</span> of {{ count($orders) }} entries
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>

    document.addEventListener('DOMContentLoaded', function() {
      // Edit service button click handler
      document.querySelectorAll('.edit-service').forEach(button => {
        button.addEventListener('click', function() {
          const serviceId = this.getAttribute('data-id');
          // Here you would typically show a modal or redirect to edit page
          alert('Edit service with ID: ' + serviceId);
          // Example: window.location.href = '/admin/services/' + serviceId + '/edit';
        });
      });

      // Delete service button click handler
      document.querySelectorAll('.delete-service').forEach(button => {
        button.addEventListener('click', function() {
          const serviceId = this.getAttribute('data-id');
          if (confirm('Are you sure you want to delete this service?')) {
            // Here you would typically make an AJAX request or form submission
            alert('Delete service with ID: ' + serviceId);
            // Example: 
            // fetch('/admin/services/' + serviceId, {
            //   method: 'DELETE',
            //   headers: {
            //     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            //   }
            // }).then(response => {
            //   if (response.ok) {
            //     location.reload();
            //   }
            // });
          }
        });
      });

      // Add new service button click handler
      document.querySelector('#services .btn-primary').addEventListener('click', function() {
        // Here you would typically show a modal or redirect to create page
        alert('Add new service');
        // Example: window.location.href = '/admin/services/create';
      });

      // Transaction filtering functionality
      const statusFilter = document.getElementById('statusFilter');
      const applyFilter = document.getElementById('applyFilter');
      const resetFilter = document.getElementById('resetFilter');
      const transactionRows = document.querySelectorAll('.transaction-row');
      const showingCount = document.getElementById('showingCount');
      
      function filterTransactions() {
        const selectedStatus = statusFilter.value;
        let visibleCount = 0;
        
        transactionRows.forEach(row => {
          const rowStatus = row.getAttribute('data-status');
          
          if (selectedStatus === 'all' || rowStatus === selectedStatus) {
            row.style.display = '';
            visibleCount++;
          } else {
            row.style.display = 'none';
          }
        });
        
        showingCount.textContent = visibleCount;
      }
      
      applyFilter.addEventListener('click', filterTransactions);
      
      resetFilter.addEventListener('click', function() {
        statusFilter.value = 'all';
        filterTransactions();
      });
      
      // Initialize with all transactions showing
      filterTransactions();

      // Order filtering by service functionality
      const serviceFilter = document.getElementById('serviceFilter');
      const applyOrderFilter = document.getElementById('applyOrderFilter');
      const resetOrderFilter = document.getElementById('resetOrderFilter');
      const orderRows = document.querySelectorAll('.order-row');
      const showingOrderCount = document.getElementById('showingOrderCount');
      
      function filterOrders() {
        const selectedService = serviceFilter.value;
        let visibleCount = 0;
        
        orderRows.forEach(row => {
          const rowService = row.getAttribute('data-service');
          
          if (selectedService === 'all' || rowService === selectedService) {
            row.style.display = '';
            visibleCount++;
          } else {
            row.style.display = 'none';
          }
        });
        
        showingOrderCount.textContent = visibleCount;
      }
      
      applyOrderFilter.addEventListener('click', filterOrders);
      
      resetOrderFilter.addEventListener('click', function() {
        serviceFilter.value = 'all';
        filterOrders();
      });
      
      // Initialize with all orders showing
      filterOrders();

    });
    
  </script>
</body>
</html>
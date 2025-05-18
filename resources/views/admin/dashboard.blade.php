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
      <form method="POST" action="{{ route('admin.logout') }}" class="d-none" id="logoutForm">
        @csrf
      </form>
      <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</button>
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
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createServiceModal">
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
                        <button class="btn btn-warning btn-sm edit-service" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editServiceModal" 
                                data-id="{{ $service->id }}"
                                data-url="{{ route('admin.services.update', $service->id) }}"
                                data-name="{{ $service->name }}"
                                data-description="{{ $service->description }}"
                                data-price="{{ $service->price }}"
                                data-note="{{ $service->note }}"
                                title="Edit">
                          <i class="bi bi-pencil-square text-white"></i>
                        </button>
                        <button class="btn btn-danger btn-sm delete-service" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteServiceModal" 
                                data-id="{{ $service->id }}" 
                                data-url="{{ route('admin.services.destroy', $service->id) }}"
                                title="Delete">
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
            <div>
              <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-download"></i> Export
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item export-option" href="#" data-type="excel">Excel</a></li>
                <li><a class="dropdown-item export-option" href="#" data-type="pdf">PDF</a></li>
              </ul>
            </div>
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

  <!-- Message Modal -->
  <div class="modal fade" id="messageModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="messageModalLabel">Notification</h5>
        </div>
        <div class="modal-body" id="messageModalBody">
          <!-- Message will be inserted here -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Logout Confirmation Modal -->
  <div class="modal fade" id="logoutModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
        </div>
        <div class="modal-body">
          Are you sure you want to logout?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <form id="logoutForm" method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="deleteServiceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteServiceModalLabel">Confirm Delete</h5>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this service? This action cannot be undone.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <form id="deleteServiceForm" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Service Modal -->
  <div class="modal fade" id="editServiceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editServiceModalLabel">Edit Service</h5>
          <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
        </div>
        <form id="editServiceForm" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="mb-3">
              <label for="editName" class="form-label">Name</label>
              <input type="text" class="form-control" id="editName" name="name" required>
            </div>
            <div class="mb-3">
              <label for="editDescription" class="form-label">Description</label>
              <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
            </div>
            <div class="mb-3">
              <label for="editPrice" class="form-label">Price (RM)</label>
              <input type="number" step="1.0" class="form-control" id="editPrice" name="price" required>
            </div>
            <div class="mb-3">
              <label for="editNote" class="form-label">Note</label>
              <input type="text" class="form-control" id="editNote" name="note">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Create Service Modal -->
  <div class="modal fade" id="createServiceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createServiceModalLabel">Add New Service</h5>
          <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
        </div>
        <form id="createServiceForm" method="POST" action="{{ route('admin.services.store') }}">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label for="createName" class="form-label">Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="createName" name="name" required>
            </div>
            <div class="mb-3">
              <label for="createDescription" class="form-label">Description</label>
              <textarea class="form-control" id="createDescription" name="description" rows="3"></textarea>
            </div>
            <div class="mb-3">
              <label for="createPrice" class="form-label">Price (RM) <span class="text-danger">*</span></label>
              <input type="number" step="1.0" class="form-control" id="createPrice" name="price" required>
            </div>
            <div class="mb-3">
              <label for="createNote" class="form-label">Note</label>
              <input type="text" class="form-control" id="createNote" name="note">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Service</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>

    document.addEventListener('DOMContentLoaded', function() {

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

    // Logout confirmation modal handling
    document.addEventListener('DOMContentLoaded', function() {
      const logoutForm = document.getElementById('logoutForm');
      
      logoutForm.addEventListener('submit', function(e) {
        e.preventDefault();
        this.submit();
      });
    });

    // Delete service modal handling
    document.addEventListener('DOMContentLoaded', function() {
      const deleteServiceModal = document.getElementById('deleteServiceModal');
      
      if (deleteServiceModal) {
        deleteServiceModal.addEventListener('show.bs.modal', function(event) {
          const button = event.relatedTarget;
          const serviceId = button.getAttribute('data-id');
          const deleteUrl = button.getAttribute('data-url');
          
          const form = deleteServiceModal.querySelector('#deleteServiceForm');
          form.action = deleteUrl;
        });
      }
    });
    
    document.addEventListener('DOMContentLoaded', function() {
    @if(session('success') || session('error'))
      const messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
      const modalBody = document.getElementById('messageModalBody');
      const modalHeader = document.getElementById('messageModal').querySelector('.modal-header');
      
      @if(session('success'))
        modalHeader.classList.add('bg-success', 'text-white');
        document.getElementById('messageModalLabel').textContent = 'Success';
        modalBody.innerHTML = `
          <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill text-success me-2" style="font-size: 1.5rem;"></i>
            <span>{{ session('success') }}</span>
          </div>
        `;
        messageModal.show();
      @endif
      
      @if(session('error'))
        modalHeader.classList.add('bg-danger', 'text-white');
        document.getElementById('messageModalLabel').textContent = 'Error';
        modalBody.innerHTML = `
          <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill text-danger me-2" style="font-size: 1.5rem;"></i>
            <span>{{ session('error') }}</span>
          </div>
        `;
        messageModal.show();
      @endif
    @endif
  });

  // Edit service modal handling
  document.addEventListener('DOMContentLoaded', function() {
    const editServiceModal = document.getElementById('editServiceModal');
    
    if (editServiceModal) {
      editServiceModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const serviceId = button.getAttribute('data-id');
        const editUrl = button.getAttribute('data-url');
        const name = button.getAttribute('data-name');
        const description = button.getAttribute('data-description');
        const price = button.getAttribute('data-price');
        const note = button.getAttribute('data-note');
        
        const form = editServiceModal.querySelector('#editServiceForm');
        form.action = editUrl;
        
        // Populate the form fields
        document.getElementById('editName').value = name;
        document.getElementById('editDescription').value = description;
        document.getElementById('editPrice').value = price;
        document.getElementById('editNote').value = note;
      });
    }
  });

  // Create service modal handling
  document.addEventListener('DOMContentLoaded', function() {
    const createServiceModal = document.getElementById('createServiceModal');
    
    if (createServiceModal) {
      createServiceModal.addEventListener('show.bs.modal', function() {
        // Reset the form when modal is shown
        document.getElementById('createServiceForm').reset();
      });
    }
  });

  // Export orders functionality
  document.addEventListener('DOMContentLoaded', function() {
    const exportOptions = document.querySelectorAll('.export-option');
    
    exportOptions.forEach(option => {
      option.addEventListener('click', function(e) {
        e.preventDefault();
        const exportType = this.getAttribute('data-type');
        const serviceFilter = document.getElementById('serviceFilter').value;
        
        let exportUrl = '{{ route("admin.orders.export") }}';
        exportUrl += `?type=${exportType}`;
        
        if (serviceFilter !== 'all') {
          exportUrl += `&service=${encodeURIComponent(serviceFilter)}`;
        }
        
        window.location.href = exportUrl;
      });
    });
  });

  </script>

</body>
</html>
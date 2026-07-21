<?php
$currentUser = $currentUser ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $this->fetch('title') ?> | <?= isset($siteSettings['site_name']) ? h($siteSettings['site_name']) : 'Enterprise Admin' ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <?= $this->Html->css('custom') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    
    <!-- jQuery & JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php if ($currentUser): ?>
        <!-- Logged In Layout -->
        <div class="sidebar">
            <div class="sidebar-header">
                <a href="<?= $this->Url->build('/') ?>">
                    <span><i class="fa-solid fa-layer-group text-primary me-2"></i></span><?= isset($siteSettings['site_name']) ? h($siteSettings['site_name']) : 'Enterprise' ?>
                </a>
            </div>
            <div class="sidebar-menu">
                <div class="sidebar-menu-title">Main</div>
                <a href="<?= $this->Url->build('/') ?>" class="sidebar-link <?= $this->request->getPath() === '/' ? 'active' : '' ?>">
                    <i class="fa-solid fa-chart-line"></i> Dashboard
                </a>
                
                <div class="sidebar-menu-title">Membership</div>
                <a href="<?= $this->Url->build('/users') ?>" class="sidebar-link <?= str_contains($this->request->getPath(), '/users') ? 'active' : '' ?>">
                    <i class="fa-solid fa-users"></i> Users
                </a>
                <a href="<?= $this->Url->build('/groups') ?>" class="sidebar-link <?= str_contains($this->request->getPath(), '/groups') ? 'active' : '' ?>">
                    <i class="fa-solid fa-users-rectangle"></i> Groups
                </a>
                <a href="<?= $this->Url->build('/roles') ?>" class="sidebar-link <?= str_contains($this->request->getPath(), '/roles') ? 'active' : '' ?>">
                    <i class="fa-solid fa-user-shield"></i> Roles & Perms
                </a>
                <a href="<?= $this->Url->build('/permissions') ?>" class="sidebar-link <?= str_contains($this->request->getPath(), '/permissions') ? 'active' : '' ?>">
                    <i class="fa-solid fa-key"></i> Permissions
                </a>

                <div class="sidebar-menu-title">ERP & Catalog</div>
                <a href="<?= $this->Url->build('/products') ?>" class="sidebar-link <?= str_contains($this->request->getPath(), '/products') ? 'active' : '' ?>">
                    <i class="fa-solid fa-box"></i> Products
                </a>
                <a href="<?= $this->Url->build('/categories') ?>" class="sidebar-link <?= str_contains($this->request->getPath(), '/categories') ? 'active' : '' ?>">
                    <i class="fa-solid fa-tags"></i> Categories
                </a>
                <a href="<?= $this->Url->build('/brands') ?>" class="sidebar-link <?= str_contains($this->request->getPath(), '/brands') ? 'active' : '' ?>">
                    <i class="fa-solid fa-trademark"></i> Brands
                </a>
                <a href="<?= $this->Url->build('/orders') ?>" class="sidebar-link <?= str_contains($this->request->getPath(), '/orders') ? 'active' : '' ?>">
                    <i class="fa-solid fa-cart-shopping"></i> Orders
                </a>
                <a href="<?= $this->Url->build('/payments') ?>" class="sidebar-link <?= str_contains($this->request->getPath(), '/payments') ? 'active' : '' ?>">
                    <i class="fa-solid fa-credit-card"></i> Payments
                </a>
                
                <div class="sidebar-menu-title">Content & Comms</div>
                <a href="<?= $this->Url->build('/cms-pages') ?>" class="sidebar-link <?= str_contains($this->request->getPath(), '/cms-pages') ? 'active' : '' ?>">
                    <i class="fa-solid fa-file-lines"></i> CMS Pages
                </a>
                <a href="<?= $this->Url->build('/contact-enquiries') ?>" class="sidebar-link <?= str_contains($this->request->getPath(), '/contact-enquiries') ? 'active' : '' ?>">
                    <i class="fa-solid fa-envelope-open-text"></i> Enquiries
                </a>

                <div class="sidebar-menu-title">Emails</div>
                <a href="<?= $this->Url->build('/email-templates') ?>" class="sidebar-link <?= str_contains($this->request->getPath(), '/email-templates') ? 'active' : '' ?>">
                    <i class="fa-solid fa-envelope"></i> Templates
                </a>
                <a href="<?= $this->Url->build('/email-signatures') ?>" class="sidebar-link <?= str_contains($this->request->getPath(), '/email-signatures') ? 'active' : '' ?>">
                    <i class="fa-solid fa-signature"></i> Signatures
                </a>
                <a href="<?= $this->Url->build('/scheduled-emails') ?>" class="sidebar-link <?= str_contains($this->request->getPath(), '/scheduled-emails') ? 'active' : '' ?>">
                    <i class="fa-solid fa-clock"></i> Scheduled
                </a>
                <a href="<?= $this->Url->build('/sent-emails') ?>" class="sidebar-link <?= str_contains($this->request->getPath(), '/sent-emails') ? 'active' : '' ?>">
                    <i class="fa-solid fa-paper-plane"></i> Sent Emails
                </a>
                
                <div class="sidebar-menu-title">Analytics & Finance</div>
                <a href="<?= $this->Url->build('/reports') ?>" class="sidebar-link <?= str_contains($this->request->getPath(), '/reports') ? 'active' : '' ?>">
                    <i class="fa-solid fa-chart-bar"></i> Reports
                </a>
                <a href="<?= $this->Url->build('/invoices') ?>" class="sidebar-link <?= str_contains($this->request->getPath(), '/invoices') ? 'active' : '' ?>">
                    <i class="fa-solid fa-file-invoice-dollar"></i> Invoices
                </a>

                <div class="sidebar-menu-title">System</div>
                <a href="<?= $this->Url->build('/settings') ?>" class="sidebar-link <?= str_contains($this->request->getPath(), '/settings') ? 'active' : '' ?>">
                    <i class="fa-solid fa-gears"></i> Settings
                </a>
                <a href="<?= $this->Url->build('/activity-logs') ?>" class="sidebar-link <?= str_contains($this->request->getPath(), '/activity-logs') ? 'active' : '' ?>">
                    <i class="fa-solid fa-clock-rotate-left"></i> Activity Logs
                </a>
                <a href="<?= $this->Url->build('/audit-logs') ?>" class="sidebar-link <?= str_contains($this->request->getPath(), '/audit-logs') ? 'active' : '' ?>">
                    <i class="fa-solid fa-shield-halved"></i> Audit Logs
                </a>
                <a href="<?= $this->Url->build('/logs') ?>" class="sidebar-link <?= str_contains($this->request->getPath(), '/logs') ? 'active' : '' ?>">
                    <i class="fa-solid fa-receipt"></i> Log Files
                </a>
                
                <div class="px-4 py-3">
                    <button class="btn btn-outline-danger btn-sm w-100" id="btnClearCache">
                        <i class="fa-solid fa-trash me-1"></i> Clear Cache
                    </button>
                </div>
            </div>
        </div>

        <div class="main-wrapper">
            <nav class="top-navbar">
                <div class="navbar-left">
                    <span class="text-muted"><i class="fa-solid fa-calendar me-2"></i><?= date('D, M d, Y') ?></span>
                </div>
                <div class="navbar-right d-flex align-items-center">
                    <div class="dropdown">
                        <a class="dropdown-toggle text-decoration-none text-white d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <?php if (!empty($currentUser->profile_image)): ?>
                                <img src="<?= $this->Url->build('/uploads/profiles/' . $currentUser->profile_image) ?>" class="rounded-circle me-2" width="32" height="32">
                            <?php else: ?>
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-2" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                    <?= strtoupper(substr($currentUser->username, 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <span><?= h($currentUser->username) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end mt-2 shadow">
                            <li><a class="dropdown-item" href="<?= $this->Url->build('/users/profile') ?>"><i class="fa-regular fa-user me-2"></i>My Profile</a></li>
                            <li><a class="dropdown-item" href="<?= $this->Url->build('/users/change-password') ?>"><i class="fa-solid fa-key me-2"></i>Change Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= $this->Url->build('/users/logout') ?>"><i class="fa-solid fa-power-off me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="content-body">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </div>
        </div>

        <!-- Global AJAX Modal -->
        <div class="modal fade" id="globalAjaxModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold" id="globalAjaxModalTitle"><i class="fa-solid fa-layer-group me-2 text-primary"></i> <span>Loading...</span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4" id="globalAjaxModalBody">
                        <div class="text-center p-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- Public/Login Layout -->
        <div class="container d-flex align-items-center justify-content-center min-vh-100">
            <div class="w-100" style="max-width: 450px;">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-dark"><span class="text-primary"><i class="fa-solid fa-layer-group me-2"></i></span>Enterprise</h2>
                    <p class="text-muted">User Management &amp; Control Panel</p>
                </div>
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </div>
        </div>
    <?php endif; ?>

    <script>
        $(document).ready(function() {
            // Handle Clear Cache button click
            $('#btnClearCache').on('click', function() {
                Swal.fire({
                    title: 'Clear Cache?',
                    text: "This will clear all application caches in one click.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0078d4',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, clear it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= $this->Url->build('/users/clear-cache') ?>';
                    }
                });
            });

            // Global AJAX Modal Logic for Edit/View links
            $(document).on('click', '.ajax-modal-link', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                var title = $(this).attr('title') || $(this).data('title') || 'Details';
                
                $('#globalAjaxModalTitle span').text(title);
                $('#globalAjaxModalBody').html('<div class="text-center p-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                $('#globalAjaxModal').modal('show');
                
                $.get(url, function(data) {
                    $('#globalAjaxModalBody').html(data);
                }).fail(function() {
                    $('#globalAjaxModalBody').html('<div class="alert alert-danger m-3">Failed to load content. Please try again.</div>');
                });
            });

            // Handle Form Submissions inside the Global AJAX Modal
            $(document).on('submit', '#globalAjaxModal form', function(e) {
                e.preventDefault();
                var $form = $(this);
                var url = $form.attr('action') || window.location.href;
                var method = $form.attr('method') || 'POST';
                var formData = new FormData(this);
                var $btn = $form.find('button[type="submit"]');
                var originalBtnText = $btn.html();
                
                $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i>Saving...');
                
                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(response) {
                        // Check if JSON response (success) or HTML response (validation errors)
                        if (typeof response === 'object' && response.success) {
                            $('#globalAjaxModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message || 'Saved successfully.',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            
                            // Refresh data
                            if (typeof loadUsers === 'function' && typeof getFilterParams === 'function') {
                                loadUsers(getFilterParams());
                            } else {
                                window.location.reload();
                            }
                        } else {
                            // Replace modal body with form containing errors
                            $('#globalAjaxModalBody').html(response);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 400 || xhr.status === 422) {
                            $('#globalAjaxModalBody').html(xhr.responseText);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An unexpected error occurred.',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            $btn.prop('disabled', false).html(originalBtnText);
                        }
                    }
                });
            });
        });
    </script>
    <?= $this->fetch('script') ?>
</body>
</html>

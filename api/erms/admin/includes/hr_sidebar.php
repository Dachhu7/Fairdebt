<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar" style="background: linear-gradient(135deg, #4e73df, #1d3c6d);">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="myprofile.php" style="color: white; text-decoration: none;">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3" style="font-family: 'Poppins', sans-serif; font-weight: bold; text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);">Fairdebt Solutions</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0" style="border-color: #d1d3e2;">

    <!-- Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="welcome.php" style="font-family: 'Poppins', sans-serif; font-weight: 500;">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider" style="border-color: #d1d3e2;">

    <!-- Main Section -->
    <div class="sidebar-heading" style="color: white; font-weight: 600;">Main</div>

    <!-- Employees Section -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseEmployees" aria-expanded="true" aria-controls="collapseEmployees" style="font-family: 'Poppins', sans-serif;">
            <i class="fas fa-fw fa-users"></i>
            <span>Employees</span>
        </a>
        <div id="collapseEmployees" class="collapse" aria-labelledby="headingEmployees" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="allemployees.php">All Employees</a>
                <a class="collapse-item" href="holidays.php">Holidays</a>
                <a class="collapse-item" href="leaves-employee.php">Employee Leave</a>
                <a class="collapse-item" href="departments.php">Departments</a>
                <a class="collapse-item" href="designations.php">Designations</a>
            </div>
        </div>
    </li>

    <!-- HR Section -->
    <div class="sidebar-heading" style="color: white; font-weight: 600;">HR</div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseHR" aria-expanded="true" aria-controls="collapseHR" style="font-family: 'Poppins', sans-serif;">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Accounts</span>
        </a>
        <div id="collapseHR" class="collapse" aria-labelledby="headingHR" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="invoices.php">Invoices</a>
                <a class="collapse-item" href="payments.php">Expense Tracker</a>
            </div>
        </div>
    </li>

    <!-- Payroll -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePayroll" aria-expanded="true" aria-controls="collapsePayroll" style="font-family: 'Poppins', sans-serif;">
            <i class="fas fa-fw fa-money-bill"></i>
            <span>Payroll</span>
        </a>
        <div id="collapsePayroll" class="collapse" aria-labelledby="headingPayroll" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="salary.php">Employee Salary</a>
                <a class="collapse-item" href="payroll-items.php">Payroll Items</a>
            </div>
        </div>
    </li>

    <!-- Documents -->
    <li class="nav-item">
        <a class="nav-link" href="docs.php" style="font-family: 'Poppins', sans-serif;">
            <i class="fas fa-fw fa-file-alt"></i> <!-- Changed to a document icon for relevance -->
            <span>Documents</span>
        </a>
    </li>


    <!-- Resignation and Termination -->
    <li class="nav-item">
        <a class="nav-link" href="exitlist.php" style="font-family: 'Poppins', sans-serif;">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Exit List</span>
        </a>
    </li>

    <!-- Administration Section -->
    <div class="sidebar-heading" style="color: white; font-weight: 600;">Administration</div>

    <li class="nav-item">
        <a class="nav-link" href="users.php" style="font-family: 'Poppins', sans-serif;">
            <i class="fas fa-fw fa-user-plus"></i>
            <span>Users</span>
        </a>
    </li>

    <!-- Pages Section -->
    <div class="sidebar-heading" style="color: white; font-weight: 600;">Pages</div>

    <!-- Settings and Logout -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSettings" aria-expanded="false" aria-controls="collapseSettings" style="font-family: 'Poppins', sans-serif;">
            <i class="fas fa-fw fa-cogs"></i>
            <span>Settings</span>
        </a>
        <div id="collapseSettings" class="collapse" aria-labelledby="headingSettings" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="announcements.php">Announcements</a>
                <a class="collapse-item" href="adminprofile.php">Employee Profile</a>
                <a class="collapse-item" href="changepassword.php">Change Password</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="logout.php" style="font-family: 'Poppins', sans-serif;">
            <i class="fas fa-fw fa-power-off"></i>
            <span>Logout</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block" style="border-color: #d1d3e2;">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle" style="background-color: #4e73df; border: none;"></button>
    </div>
</ul>

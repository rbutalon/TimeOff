<?php
  session_start();
  include('./src/dbConn.php');

  if (!isset($_SESSION['userID'])) {
    header("Location: index.html");
    exit();
  }

  $userID = $_SESSION['userID'];
  $role = ucfirst($_SESSION['role']);
  $fName = ucfirst($_SESSION['fName']);
  $departmentName = ucfirst($_SESSION['department']);

  // Get total submitted leaves
  $submittedLeaves = 0;
  $sqlSubmitted = "SELECT COUNT(*) AS total FROM leave_tbl";
  if ($result = mysqli_query($conn, $sqlSubmitted)) {
    $row = mysqli_fetch_assoc($result);
    $submittedLeaves = $row['total'] ?? 0;
  }

  // total approved leaves
  $approvedLeaves = 0;
  $sqlApproved = "SELECT COUNT(*) AS total FROM leave_tbl WHERE status = 'Approved'";
  if ($result = mysqli_query($conn, $sqlApproved)) {
    $row = mysqli_fetch_assoc($result);
    $approvedLeaves = $row['total'] ?? 0;
  }

  // total rejected leaves
  $rejectedLeaves = 0;
  $sqlRejected = "SELECT COUNT(*) AS total FROM leave_tbl WHERE status = 'Rejected'";
  if ($result = mysqli_query($conn, $sqlRejected)) {
    $row = mysqli_fetch_assoc($result);
    $rejectedLeaves = $row['total'] ?? 0;
  }

  // Get total pending leaves
  $pendingLeaves = 0;
  $sqlPending = "SELECT COUNT(*) AS total FROM leave_tbl WHERE status = 'pending'";
  if ($result = mysqli_query($conn, $sqlPending)) {
    $row = mysqli_fetch_assoc($result);
    $pendingLeaves = $row['total'] ?? 0;
  }

  // leave rates per department
  $leaveRates = [];
  $sqlRates = "SELECT u.department, COUNT(l.leave_id) AS totalLeaves 
             FROM leave_tbl l 
             INNER JOIN users_tbl u ON l.user_id = u.userID 
             GROUP BY u.department";
  if ($result = mysqli_query($conn, $sqlRates)) {
    while ($row = mysqli_fetch_assoc($result)) {
        $department = ucfirst(strtolower($row['department']));
        $leaveRates[$row['department']] = $row['totalLeaves'];
    }
  }

  //monthly leave trend
  $monthlyTrends = [];
  $sql = "SELECT MONTHNAME(Start_date) as month, COUNT(*) as total 
          FROM leave_tbl 
          GROUP BY MONTH(Start_date) 
          ORDER BY MONTH(Start_date)";

  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      $monthlyTrends[$row['month']] = $row['total'];
    }
  }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="./public/logo-dark.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TimeOff | HR Dashboard</title>
    <link href="https://api.fontshare.com/v2/css?f[]=general-sans@300,400,401,600,601,700&f[]=khand@600,700&f[]=quicksand@300,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./styles/modern-normalize.css" />
    <link rel="stylesheet" href="./styles/style.css" />
    <link rel="stylesheet" href="./styles/components/sideBar.css">
    <link rel="stylesheet" href="./styles/components/greeting.css">
    <link rel="stylesheet" href="./styles/components/cards.css">
    <link rel="stylesheet" href="./styles/components/approver-dashboard/approver-cards.css">
    <link rel="stylesheet" href="./styles/components/calendar.css">
    <link rel="stylesheet" href="./styles/components/activity.css">
    <link rel="stylesheet" href="./styles/utils.css">
  </head>
  <body>
    <header class="header">
     <div class="header__mobile container">
       <button class="header__bars">
         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
           <path fill-rule="evenodd" d="M3 6.75A.75.75 0 0 1 3.75 6h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 6.75ZM3 12a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 12Zm0 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
         </svg>
       </button>
       <h2 class="header__title">Dashboard</h2>
     </div>

     
    </header>
    
    <div class="body__wrapper">
      <nav class="sidebar" id="sidebar">
        <div class="sidebar__top">
            <a href="admin_dashboard.php" class="logo">
              <img src="./public/logo.png" height="60px" alt="Logo." class="logoImg"/>
              <h2 class="logoText">TimeOff</h2>
            </a>
          <button class="toggle__btn" id="toggle__btn" onclick="toggleSideBar()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="toggle">
            <path stroke-linecap="round" stroke-linejoin="round" d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5" />
            </svg>
          </button>
        </div>

        <ul class="sidebar__middle">
          <li>
            <a href="#" class="sidebar__item active">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 0 1-1.125-1.125v-3.75ZM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-8.25ZM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-2.25Z" />
              </svg>
              <span>Dashboard</span>
            </a>
          </li>

          <li>
            <a href="hr_leaveRequest.php" class="sidebar__item">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z" />
              </svg>
              <span>Leave Requests</span>
            </a>
          </li>

          <li>
            <a href="hr_approved.php" class="sidebar__item">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
              </svg>
              <span>Approved Requests</span>
            </a>
          </li>

          <li>
            <a href="hr_rejected.php" class="sidebar__item">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
              </svg>
              <span>Rejected Requests</span>
            </a>
          </li>
  
          <li>
            <a href="hr_employeeList.php" class="sidebar__item employeeList">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
              </svg>
              <span>Employees</span>
            </a>
          </li>
        </ul>
        <div class="sidebar__bottom">
          <a href="index.html" class="sidebar__item signOut">
          <svg xmlns="http://www.w3.org/2000/svg" height="30px" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
          </svg>
          <span><h3>Sign Out</h3></span>
          </a>
          <hr>
          <div class="info">
            <svg xmlns="http://www.w3.org/2000/svg" height="45px" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            <span>
              <h3><span class="info__userName"><?php echo htmlspecialchars($fName);?></span></h3>
              <h5><span class="info__role"><?php echo htmlspecialchars($role);?></span> | <span class="info__position"><?php echo htmlspecialchars($departmentName);?></span></h5>
            </span>
          </div>
        </div>
      </nav>
  
      <main class="dashboard">
        <section class="greeting container">
          <div class="greeting__title">
            <h1>Hi, <?php echo htmlspecialchars($fName);?>!</h1>
          </div>
          <div class="greeting__wrapper">
            <div class="greeting__messages">
              <h4 class="greeting__subTitle">Welcome back!</h4>
              <p class="greeting__txt">Check out how the entire company is performing today.</p>
            </div>
            <div class="greeting__img">
              <img src="./public/logo-dark.png" alt="logo" class="greeting__img-logo">
            </div>
          </div>
        </section>
        
        <section class="dashboard__cards container section">
          <div class="card card__1">
            <h2 class="card__title title1">Total Leave Requests</h2>
            <div class="card__number">
              <h3 class="card__number-txt"><?php echo $submittedLeaves; ?></h3>
            </div>
          </div>

          <div class="card card__2">
            <h2 class="card__title title2">Approved Requests</h2>
            <div class="card__number">
              <h3 class="card__number-txt"><?php echo $approvedLeaves; ?></h3>
            </div>
          </div>

          <div class="card card__3">
            <h2 class="card__title title3">Pending Requests</h2>
            <div class="card__number">
              <h3 class="card__number-txt"><?php echo $pendingLeaves; ?></h3>
            </div>
          </div>

          <div class="card card__4">
            <h2 class="card__title title4">Leave Rates per Department</h2>
            <div class="chart__wrapper">
              <canvas id="leaveRatesChart" width="400" height="300"></canvas>
            </div>
          </div>

          <div class="card card__5">
            <h2 class="card__title title5">Monthly Leave Trends</h2>
            <div class="chart__wrapper">
              <canvas id="leaveTrendChart" height="300"></canvas>
            </div>
          </div>
        </section>


        <section class="calendar container section">
          <div class="calendar__wrapper">
            <div class="calendar__header">
              <h2 class="calendar__title" id="currentMonth"></h2>
              <div class="calendar__controls">
                <button class="calendar__nav" id="todayButton">Today</button>
                <button class="calendar__nav" id="prevMonth">
                <svg xmlns="http://www.w3.org/2000/svg" height="15px" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>

                </button>
                <button class="calendar__nav" id="nextMonth">
                <svg xmlns="http://www.w3.org/2000/svg" height="15px" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
                </button>
              </div>
            </div>
            <div class="calendar__grid" id="calendarGrid">
              </div>
            <div class="calendar__legend">
              <h3>Leave Types</h3>
              <ul class="legend__list" id="leaveLegend">
              </ul>
            </div>
          </div>
        </section>

        <section class="activity container section">
          <div class="activity__wrapper">
            <div class="activity__top">
              <h2 class="activity__header">HR Activity Snapshot</h2>
            </div>
            <hr>
            <div class="activity__bottom">
            <p><strong>Total Approved Requests Today:</strong> <?php echo $approvedLeaves; ?></p>
            <p><strong>Total Rejected Requests Today:</strong> <?php echo $rejectedLeaves; ?></p>
            </div>
          </div>
        </section>
      </main>
    </div>
    <footer></footer>

    <script>
      const totalRequests = <?php echo $submittedLeaves; ?>;
      const approvedRequests = <?php echo $approvedLeaves; ?>;
      const pendingRequests = <?php echo $pendingLeaves; ?>;
      const leaveRatesData = <?php echo json_encode($leaveRates);?>;
      const leaveTrendData = <?php echo json_encode($monthlyTrends); ?>;
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./src/approver/approver-chart.js"></script>
    <script src="./src/calendar.js"></script>
    <script src="./src/sidebar.js"></script>
    <!-- <script type="module" src="./src/main.js"></script> -->
  </body>
</html>

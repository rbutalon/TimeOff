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

 if ($_SERVER["REQUEST_METHOD"] == "POST") {
     $leaveType = $_POST['leaveType'];
     $startDate = $_POST['start_date'];
     $endDate = $_POST['end_date'];
     $reason = $_POST['reason'];

    if (empty($leaveType) || empty($startDate) || empty($endDate) || empty($reason)) {
    $response = array('status' => 'error', 'message' => 'Please fill out all required fields.');
    } elseif (strtotime($endDate) < strtotime($startDate)) {
    $response = array('status' => 'error', 'message' => 'End date cannot be before the start date.');
    } else {
     $leaveType = mysqli_real_escape_string($conn, $leaveType);
     $startDate = mysqli_real_escape_string($conn, $startDate);
     $endDate = mysqli_real_escape_string($conn, $endDate);
     $reason = mysqli_real_escape_string($conn, $reason);
        
    $sql = "INSERT INTO leave_tbl (user_id, leave_type, start_date, end_date, reason, status, created_at) VALUES ('$userID', '$leaveType', '$startDate', '$endDate', '$reason', 'Pending', NOW())";

    if (mysqli_query($conn, $sql)) {
    $response = array('status' => 'success', 'message' => 'Leave application submitted successfully!');
    } else {
    $response = array('status' => 'error', 'message' => 'Error submitting leave application: ' . mysqli_error($conn));
    }
}
      header('Content-Type: application/json');
      echo json_encode($response);
      exit();
}
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="./public/logo-dark.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TimeOff | Apply a Leave</title>
    <link href="https://api.fontshare.com/v2/css?f[]=general-sans@200,201,300,301,400,401,500,501,600,601,700,701,1,2&f[]=quicksand@300,400,500,600,700,1&f[]=khand@300,400,500,600,700,1&display=swap" rel="stylesheet"> <!--Fonts-->
    <link rel="stylesheet" href="./styles/modern-normalize.css" />
    <link rel="stylesheet" href="./styles/style.css" />
    <link rel="stylesheet" href="./styles/components/sideBar.css">
    <link rel="stylesheet" href="./styles/components/form.css">
    <link rel="stylesheet" href="./styles/utils.css">
  </head>
  <body>
    <header class="header">
     <div class="header__mobile container">
       <button class="header__bars" id="hamburger"> 
         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
           <path fill-rule="evenodd" d="M3 6.75A.75.75 0 0 1 3.75 6h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 6.75ZM3 12a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 12Zm0 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
         </svg>
       </button>
       <h2 class="header__title"></h2>
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
            <a href="employee_dashboard.php" class="sidebar__item">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 0 1-1.125-1.125v-3.75ZM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-8.25ZM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-2.25Z" />
              </svg>
              <span>Dashboard</span>
            </a>
          </li>
  
          <li class="dropDown__wrapper">
            <button class="sidebar__item dropDown active" onclick="toggleSubMenu(this)">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
              </svg>
              <span>Leave Management</span>
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-6">
                <path fill-rule="evenodd" d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z" clip-rule="evenodd" />
                </svg>
              </span>
            </button>
            <ul class="sidebar__subMenu">
              <div class="sidebar__subMenu-wrapper">
                <li>
                  <a href="#" class="sidebar__subItem applyLeave active">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>Apply for a Leave</span>
                  </a>
                </li>
                <li>
                  <a href="employee_leaveRequest.php" class="sidebar__subItem employeeRequest">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z" />
                    </svg>

                    <span>My Requests</span>
                  </a>
                </li>
              </div>
            </ul>
          </li>
  
          <li>
            <a href="notifications.php" class="sidebar__item notifications">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
            </svg>

              <span>Notifications</span>
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
              <h3><span class="info__userName"><?php echo htmlspecialchars($fName); ?></span></h3>
              <h5><span class="info__role"><?php echo htmlspecialchars($role); ?></span> | <span class="info__position"><?php echo htmlspecialchars($departmentName); ?></span></h5>
            </span>
          </div>
        </div>
      </nav>
  
      <main class="main__addUser">
          <section class="greeting__wrapper formContainer">
            <div class="greeting">
              <div class="greeting__title">
                <h1>Submit a Leave Request</h1>
              </div>
            </div>
          </section>
          <section class="form__wrapper formContainer">
            <form action="" method="POST" class="form" id="leaveForm">
                  <div class="input__group">
                    <label for="leaveType">Type of Leave *</label>
                    <select name="leaveType" id="leaveType" required>
                      <option value="" disabled selected>Select Leave Type</option>
                        <option value="sick">Sick Leave</option>
                        <option value="emergency">Emergency Leave</option>
                        <option value="maternity">Maternity Leave</option>
                        <option value="paternity">Paternity Leave</option>
                        <option value="earned">Earned Leave</option>
                        <option value="casual">Casual Leave</option>
                    </select>
                  </div>
                  <div class="input__group">
                    <label for="start_date" data-counter="startCtr" class="form__label">Start Date *</label>
                    <input type="date" id="start_date" name="start_date" class="form__input-date" required />
                  </div>
                  <div class="input__group">
                    <label for="end_date" data-counter="endCtr" class="form__label">End Date *</label>
                    <input type="date" id="end_date" name="end_date" class="form__input-date" required />
                  </div>
                  <div class="input__group">
                      <label for="reason">Reason *</label>
                      <textarea name="reason" id="reason" data-counter="#reasonCtr" rows="5" placeholder="State your reason." required></textarea>
                      <span id="passwordCtr">0/100</span>
                  </div>

                  <span id="message"></span>
                  <div class="submit__wrapper">
                    <button class="submitBtn" type="submit">Apply Leave</button>
                  </div>
            </form>
          </section>
      </main>
    </div>
    <footer></footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./src/employee/applyLeave.js"></script>
    <script src="./src/sidebar.js"></script>
    <!-- <script type="module" src="./src/main.js"></script> -->
  </body>
</html>

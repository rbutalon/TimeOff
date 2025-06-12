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

  $employeeData = [];
  $sql = "SELECT userID, fName, lName, email, role, department FROM users_tbl";
  if ($result = mysqli_query($conn, $sql)) {
    while ($row = mysqli_fetch_assoc($result)) {
      $employeeData[] = $row;
    }
  }
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="./public/logo-dark.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TimeOff | Employee List</title>
    <link href="https://api.fontshare.com/v2/css?f[]=general-sans@200,201,300,301,400,401,500,501,600,601,700,701,1,2&f[]=quicksand@300,400,500,600,700,1&f[]=khand@300,400,500,600,700,1&display=swap" rel="stylesheet"> <!--Fonts-->
    <link rel="stylesheet" href="./styles/modern-normalize.css" />
    <link rel="stylesheet" href="./styles/style.css" />
    <link rel="stylesheet" href="./styles/components/sideBar.css">
    <link rel="stylesheet" href="./styles/components/form.css">
    <link rel="stylesheet" href="./styles/components/employeeList.css">
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
            <a href="admin_dashboard.php" class="sidebar__item">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 0 1-1.125-1.125v-3.75ZM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-8.25ZM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-2.25Z" />
              </svg>
              <span>Dashboard</span>
            </a>
          </li>
  
          <li>
            <a href="addUser.php" class="sidebar__item addUser">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
              </svg>
              <span>Add Employee</span>
            </a>
          </li>
 
          <li>
            <a href="#" class="sidebar__item employeeList active">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
              </svg>
              <span>Employee List</span>
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
            <!-- <svg xmlns="http://www.w3.org/2000/svg" height="45px" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg> -->
            <img src="./public/jp.png" alt="user" class="info__img">
            <span>
              <h3><span class="info__userName"><?php echo htmlspecialchars($fName); ?></span></h3>
              <h5><span class="info__role"><?php echo htmlspecialchars($role); ?></span> | <span class="info__position"><?php echo htmlspecialchars($departmentName); ?></span></h5>
            </span>
          </div>
        </div>
      </nav>
  
      <main class="dashboard">
          <section class="greeting__wrapper formContainer">
            <div class="greeting">
              <div class="greeting__title">
                <h1>List of Employees</h1>
              </div>
            </div>
          </section>

          <section class="search formContainer">
            <div class="search__bar">
                <input type="text" id="searchInput" placeholder="Search employee..." />
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>
          </section>
          
          <section class="employee__wrapper formContainer">
             <?php foreach ($employeeData as $employee): ?>
                <div class="employee__card">
                  <div class="card__header">
                      <h3><?php echo htmlspecialchars($employee['fName'] . ' ' . $employee['lName']); ?></h3>
                  </div>
      
                  <div class="card__details">
                      <p><strong>Email: </strong><?php echo htmlspecialchars($employee['email']); ?></p>
                      <p><strong>Role: </strong><?php echo htmlspecialchars($employee['role']); ?></p>
                      <p><strong>Department: </strong><?php echo htmlspecialchars($employee['department']); ?></p>
                  </div>
                </div>
            <?php endforeach; ?>
          </section>
      </main>
    </div>
    <footer></footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./src/employees.js"></script>
    <script src="./src/sidebar.js"></script>
    <!-- <script type="module" src="./src/main.js"></script> -->
  </body>
</html>

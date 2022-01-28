<!DOCTYPE html>
<html lang="en">

<!-- This is the main page for student-->

<?php
include $_SERVER["DOCUMENT_ROOT"] . '/html/controller/AnnouncementHandler.php';
include $_SERVER["DOCUMENT_ROOT"] . '/html/controller/FypActivityHandler.php';
include_once $_SERVER["DOCUMENT_ROOT"] .  '/mySQLi/config.php';
session_start();

?>

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>

  <!-- Bootstrap 5 CSS -->
  <link rel="stylesheet" href="../../../bootstrap_v5.1/css/styles.css" />

  <!-- Bootstrap 5 JavaScript -->
  <script src="../../../bootstrap_v5.1/js/scripts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

  <!-- Fontawesome CSS -->
  <script src="https://use.fontawesome.com/8134766fa6.js"></script>


  <!-- CSS -->
  <link rel="stylesheet" href="../css/main.css" />
  <link rel="stylesheet" href="../../../css/module_3.css" />


  <!-- JS -->
</head>

<body class="sb-nav-fixed">
  <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <img class="logo ms-3" src="../images/ump_logo.png" alt="UMP" />
    <a class="navbar-brand ms-3 me-0" href="index.html">StudFYP</a>

    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
      <i class="fa fa-bars"></i>
    </button>
    <!-- Navbar Notification-->

    <!-- Navbar-->
    <ul class="navbar-nav d-md- ms-auto me-1">
      <!-- Announcement -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-bell fa-fw"></i> Notification</a>
        <ul class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="navbarDropdown">
          <li class="dropdown-header text-white text-center p-2">
            Notfication
          </li>
          <?php
          printNotificationList();
          ?>
          <li>
            <a class="dropdown-item see-more-notification" href="/html/student_main.php"> See more ...</a>
          </li>
        </ul>
      </li>

      <!-- Profile -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-user fa-fw"></i> Account</a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
          <li><a class="dropdown-item" href="controller/logout_handler.php"><i class="fa fa-sign-out"></i>Logout</a></li>
        </ul>
      </li>
    </ul>
  </nav>

  <!-- Sidebar -->
  <div id="layoutSidenav">
    <div id="layoutSidenav_nav">
      <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
          <div class="nav mt-3">
            <!-- Dashboard -->
            <a class="nav-link" href="student_main.php">
              <div class="sb-nav-link-icon">
                <i class="fa fa-tachometer" aria-hidden="true"></i>
              </div>
              Dashboard
            </a>

            <!-- My Profile -->
            <a class="nav-link" href="#">
              <div class="sb-nav-link-icon">
                <i class="fa fa-user"></i>
              </div>
              My profile
            </a>

            <!-- FYP Enrollment -->
            <a class="nav-link" href="/html/module3/enrollement/1FYP_enrollement.php">
              <div class="sb-nav-link-icon">
                <i class="fa fa-chevron-circle-right" aria-hidden="true"></i>
              </div>
              FYP Enrollment
            </a>

            <!-- My FYP -->
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseFYP" aria-expanded="false" aria-controls="collapseFYP">
              <div class="sb-nav-link-icon">
                <i class="fa fa-columns"></i>
              </div>
              My FYP
              <div class="sb-sidenav-collapse-arrow">
                <i class="fa fa-angle-down"></i>
              </div>
            </a>
            <div class="collapse" id="collapseFYP" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
              <nav class="sb-sidenav-menu-nested nav">
                <a class="nav-link" href="module3\MyFYP\4viewlogbook.php">
                  <div class="sb-nav-link-icon">
                    <i class="fa fa-circle-thin" aria-hidden="true"></i>
                  </div>
                  View logbook
                </a>
                <a class="nav-link" href="module3\MyFYP\2updatelogbook.php">
                  <div class="sb-nav-link-icon">
                    <i class="fa fa-circle-thin" aria-hidden="true"></i>
                  </div>Update logbook
                </a>
                <a class="nav-link" href="module3\MyFYP\5addlogbook.php">
                  <div class="sb-nav-link-icon">
                    <i class="fa fa-circle-thin" aria-hidden="true"></i>
                  </div>Add logbook
                </a>
                <a class="nav-link" href="module3\MyFYP\1deletelogbook.php">
                  <div class="sb-nav-link-icon">
                    <i class="fa fa-circle-thin" aria-hidden="true"></i>
                  </div>Delete logbook
                </a>
                <a class="nav-link" href="#">
                  <div class="sb-nav-link-icon">
                    <i class="fa fa-circle-thin" aria-hidden="true"></i>
                  </div>My FYP project
                </a>
              </nav>
            </div>

            <!-- My FYP Supervisor -->
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseSupervisor" aria-expanded="false" aria-controls="collapseSupervisor">
              <div class="sb-nav-link-icon">
                <i class="fa fa-columns"></i>
              </div>
              My FYP supervisor
              <div class="sb-sidenav-collapse-arrow">
                <i class="fa fa-angle-down"></i>
              </div>
            </a>
            <div class="collapse" id="collapseSupervisor" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
              <nav class="sb-sidenav-menu-nested nav">
                <a class="nav-link" href="#">
                  <div class="sb-nav-link-icon">
                    <i class="fa fa-circle-thin" aria-hidden="true"></i>
                  </div>View my evaluator
                </a>
                <a class="nav-link" href="#">
                  <div class="sb-nav-link-icon">
                    <i class="fa fa-circle-thin" aria-hidden="true"></i>
                  </div>My evaluation result
                </a>
              </nav>
            </div>

            <!-- FYP Evaluation -->
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseEvaluation" aria-expanded="false" aria-controls="collapseEvaluation">
              <div class="sb-nav-link-icon">
                <i class="fa fa-columns"></i>
              </div>
              My FYP evaluation
              <div class="sb-sidenav-collapse-arrow">
                <i class="fa fa-angle-down"></i>
              </div>
            </a>
            <div class="collapse" id="collapseEvaluation" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
              <nav class="sb-sidenav-menu-nested nav">
                <a class="nav-link" href="module5/students/view_my_evaluator.php">
                  <div class="sb-nav-link-icon">
                    <i class="fa fa-circle-thin" aria-hidden="true"></i>
                  </div>View my evaluator
                </a>
                <a class="nav-link" href="module5/students/my_evaluation_result.php">
                  <div class="sb-nav-link-icon">
                    <i class="fa fa-circle-thin" aria-hidden="true"></i>
                  </div>My evaluation result
                </a>
              </nav>
            </div>

            <!-- My report -->
            <a class="nav-link" href="/html/module3/Report/My_report.php">
              <div class="sb-nav-link-icon">
                <i class="fa fa-file-o" aria-hidden="true"></i>
              </div>
              My report
            </a>
          </div>
        </div>
        <div class="sb-sidenav-footer">
          <div class="small">Logged in as:</div>
          <?php
          echo $_SESSION['username'];
          ?>
        </div>
      </nav>
    </div>

    <!-- Main Content -->
    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid px-4">

          <h1 class="mt-4">Dashboard</h1>
          <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item">
              <a href="index.html">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Announcement</li>
          </ol>

          <?php
          ?>

          <html>

          <head>
          </head>

          <body>
            <div class="container">
              <form action="" method="post">
                <h1>Update LogBook </h1>
                <table class="form-group">
                  <tr>
                    <th>Enter LogBook ID: </th>
                    <td><input type="text" name="getlogbookid" class="form-control"><input type="submit" class="btn btn-primary" name="Search" value="Search"></td>
                  </tr>
              </form>
              <?php
              session_start();
              if (isset($_POST['Search'])) {
                if (empty($_POST['getlogbookid'])) {
                  echo '<script type="text/javascript">';
                  echo ' alert("Required to fill up everything!")';
                  echo '</script>';
                } else {
                  $getlogbookid = $_POST['getlogbookid'];
                  $view = "SELECT * FROM project_logbook where logbook_id = '$getlogbookid' ";
                  $result = $db->query($view);
                  if ($result->num_rows > 0) {
                    $_SESSION['getlogbookid'] = $getlogbookid;
                    // output data of each row
                    while ($row = $result->fetch_assoc()) { ?>
                      <div class="container">
                        <table class="form-group">
                          <tr>
                            <th>LogBook ID</th>
                            <td><input type="text" name="logbookid" disabled="disabled" value="<?php echo $row['logbook_id'] ?>" /><br></td>
                          </tr>
                          <tr>
                            <th>Date</th>
                            <td><input type="text" name="logbookdate" value="<?php echo $row['logbook_date'] ?>" /><br></td>
                          </tr>
                          <tr>
                            <th>Activity</th>
                            <td><input type="text" name="logbookdetails" value="<?php echo $row['logbook_details'] ?>" /><br></td>
                          </tr>
                        </table>
                        <td><input type="submit" class="button" name="Update" value="Update"><br></td>
                        </tr>
                <?php
                    }
                  } else {
                    echo "0 results";
                  }
                }
              }
              if (isset($_POST['Update'])) {

                $logbookdate = $_POST['logbookdate'];
                $logbookdetails = $_POST['logbookdetails'];
                $update = "UPDATE project_logbook set logbook_date='$logbookdate',logbook_details=' $logbookdetails'
     WHERE logbook_id = '" . $_SESSION['getlogbookid'] . "'";

                if (mysqli_query($db, $update)) {
                  echo '<script type="text/javascript">';
                  echo ' alert("Record has been updated successfully !")';
                  echo '</script>';
                } else {
                  echo "Error: " . $update . ":-" . mysqli_error($db);
                }
                mysqli_close($db);
              }
                ?>
                      </div>
          </body>

          </html>

        </div>
      </main>
      <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
          <div class="text-muted text-center">
            Copyright &copy; University Malaysia Pahang 2021
          </div>
        </div>
      </footer>
    </div>
  </div>
</body>



</html>
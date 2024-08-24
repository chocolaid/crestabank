<?php
session_start();
include '../db_connection.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/");
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $conn->begin_transaction();

    $sql = "SELECT account_number, first_name, last_name, email, gender, occupation, country, street_address, city, state, zip_code, apt, ssn_or_tin, date_of_birth, phone_number, profile_picture_url, id_card_front_url, id_card_back_url, last_login_location, account_limit, last_login_date, loan_debt FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
            // Destructure $user_data array for easier access
            extract($user_data);

            // Default values for optional fields
            $account_limit = isset($account_limit) && !empty($account_limit) ? $account_limit : 1000000.00;
            $last_login_date = isset($last_login_date) && !empty($last_login_date) ? $last_login_date : date('Y-m-d H:i:s');
        } else {
            session_destroy();
            header("Location: ../login/");
            exit;
        }

        $stmt->close();

        // Prepare and execute a second query to get account details
        $sql = "SELECT account_type, currency_type, balance FROM accounts WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $account = $result->fetch_assoc();
                // Destructure $account array for easier access
                extract($account);
            } else {
                echo "No account found for this user.";
            }

        } else {
            throw new Exception("Error executing query: " . htmlspecialchars($stmt->error));
        }

        $stmt->close();
    } else {
        throw new Exception("Error executing query: " . htmlspecialchars($stmt->error));
    }

    // Get the latest transaction amount
    $sql = "SELECT amount FROM credit_or_debit_transactions WHERE account_id = ? ORDER BY created_at DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $account_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $latest_transaction_amount = $row['amount'];

        $sql = "UPDATE users SET recent_transaction_amount = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $latest_transaction_amount, $user_id);
        $stmt->execute();
    } else {
        $latest_transaction_amount = 0;
    }

    $stmt->close();

    $conn->commit();
    $sql = "SELECT id FROM accounts WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); 
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $account_id = $row['id'];
  
} else {
  echo "No account found for this user.";
}


} catch (Exception $e) {
    $conn->rollback();
    echo "Failed: " . $e->getMessage();
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type; encoding" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Domestic Transfer - Cresta Bank </title>
    <link rel="icon" type="image/png" href="../assets/img/icon.png" />
    <link href="../assets/css/loader.css" rel="stylesheet" type="text/css" />
        <script src="../assets/js/loader.js"></script>
    <!--     BEGIN GLOBAL MANDATORY STYLES-->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway|Rock+Salt|Source+Code+Pro:300,400,600" rel="stylesheet">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/forms/custom-clipboard.css">
    <!--     END GLOBAL MANDATORY STYLES-->

    <link rel="stylesheet" href="../plugins/font-icons/fontawesome/css/regular.css">
    <link rel="stylesheet" href="../plugins/font-icons/fontawesome/css/fontawesome.css">

    <!--     BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES-->
    <link href="../plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/components/cards/card.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="../plugins/bootstrap-select/bootstrap-select.min.css">
    <!--    profile css-->
    <link rel="stylesheet" type="text/css" href="../plugins/dropify/dropify.min.css">
    <link href="../assets/css/users/account-setting.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/components/custom-modal.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../assets/css/card/card.css">
    <link rel="stylesheet" href="../assets/css/card/displayCard.css">
    <!--    <link href="../assets/css/users/user-profile.css" rel="stylesheet" type="text/css" />-->

    <!--    end of table css-->


    <!-- toaster -->
    <link rel="stylesheet" type="text/css" href="../assets/css/elements/alert.css">
    <link href="../plugins/notification/snackbar/snackbar.min.css" rel="stylesheet" type="text/css" />
    <link href="../plugins/file-upload/file-upload-with-preview.min.css" rel="stylesheet" type="text/css" />
    <link href="../plugins/sweetalerts/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="../plugins/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/components/custom-sweetalert.css" rel="stylesheet" type="text/css" />
    <script src="../plugins/sweetalerts/promise-polyfill.js"></script>
    <script src="../assets/js/libs/jquery-3.1.1.min.js"></script>




    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

    <style>
    @media screen and (max-width: 600px) {
        .layout-visible {
            visibility: hidden;
            clear: both;
            float: left;
            margin: 10px auto 5px 20px;
            width: 28%;
            display: none;
        }
        .log{
            display: none;
        }
    }
    </style>





</head>

<body class="sidebar-noneoverflow">
    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <div class="header-container fixed-top">
        <header class="header navbar navbar-expand-sm">

            <ul class="navbar-nav theme-brand flex-row  text-center">
                <li class="nav-item toggle-sidebar">
                    
                    <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-list">
                            <line x1="8" y1="6" x2="21" y2="6"></line>
                            <line x1="8" y1="12" x2="21" y2="12"></line>
                            <line x1="8" y1="18" x2="21" y2="18"></line>
                            <line x1="3" y1="6" x2="3" y2="6"></line>
                            <line x1="3" y1="12" x2="3" y2="12"></line>
                            <line x1="3" y1="18" x2="3" y2="18"></line>
                        </svg></a>
                </li>
                
            </ul>
<a href="/" class="log"><img src="./../images/log.png" class="navbar-logo" alt="logo" width="25%" style="margin-top:10px; margin-bottom: 10px; margin-left: 10px;"></a>

            <ul class="navbar-item flex-row search-ul">
            </ul>
            <ul class="navbar-item flex-row navbar-dropdown">
                


                <li class="nav-item dropdown message-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="messageDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-message-circle">
                            <path
                                d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
                            </path>
                        </svg><span class="badge badge-primary"></span>
                    </a>
                    <div class="dropdown-menu p-0 position-absolute" aria-labelledby="messageDropdown">
                                                <div class="">
                            <a class="dropdown-item">
                                <div class="">

                                    <div class="media">
                                        <p class="text-center">No new message</p>
                                    </div>

                                </div>
                            </a>
                        </div>

                                            </div>
                </li>

                <li class="nav-item dropdown notification-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notificationDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-bell">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg><span class="badge badge-success"></span>
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="notificationDropdown">
                                                

                       

                                                

                       

                                                

                        <div class="notification-scroll">
                            <div class="dropdown-item">
                                <div class="media ">
                                    <p class ="text-center"> No new notifications</p>
                                </div>
                            </div>

                        </div>

                                            </div>
                </li>

                <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-settings">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path
                                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                            </path>
                        </svg>
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                        <div class="user-profile-section">
                            <div class="media mx-auto">
                                <img src="<?php echo $profile_picture_url ?>" class="img-fluid mr-2" alt="avatar">
                                <div class="media-body">
                                    <h5><?php  echo $first_name . ' ' . $last_name?></h5>
                                    <p><?php echo $account_type?></p>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-item">
                            <a href="./profile.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-user">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg> <span>My Profile</span>
                            </a>
                        </div>
                        <div class="dropdown-item">
                            <a href="./loan-transaction.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-inbox">
                                    <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline>
                                    <path
                                        d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z">
                                    </path>
                                </svg> <span>My Inbox</span>
                            </a>
                        </div>
                        <div class="dropdown-item">
                            <a href="./logout.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-log-out">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg> <span>Log Out</span>
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </header>
    </div>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <div class="sidebar-wrapper sidebar-theme">

            <nav id="sidebar">
                <div class="profile-info">
                    <figure class="user-cover-image"></figure>
                    <div class="user-info" aria-expanded="true">
                        <img src="<?php echo $profile_picture_url ?>" alt="avatar">
                        <h5><?php echo $first_name . " " . $last_name?></h5>
                        <p class=""><?php echo $account_type?></p>
                    </div>
                </div>
                <div class="shadow-bottom"></div>
                <ul class="list-unstyled menu-categories" id="accordionExample">
                    <li class="menu ">
                        <a href="./dashboard.php" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-home">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                                <span>Dashboard</span>
                            </div>


                        </a>
                    </li>

                    <li class="menu ">
                        <a href="./deposit.php" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-dollar-sign">
                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                                <span>Online Deposit</span>
                            </div>


                        </a>
                    </li>

                    <li class="menu active ">
                        <a href="./domestic-transfer.php" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-share">
                                    <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path>
                                    <polyline points="16 6 12 2 8 6"></polyline>
                                    <line x1="12" y1="2" x2="12" y2="15"></line>
                                </svg>
                                <span>Domestic Transfer</span>
                            </div>


                        </a>
                    </li>

                    <li class="menu ">
                        <a href="./wire-transfer.php" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-wifi">
                                    <path d="M5 12.55a11 11 0 0 1 14.08 0"></path>
                                    <path d="M1.42 9a16 16 0 0 1 21.16 0"></path>
                                    <path d="M8.53 16.11a6 6 0 0 1 6.95 0"></path>
                                    <line x1="12" y1="20" x2="12.01" y2="20"></line>
                                </svg>
                                <span>Wire Transfer</span>
                            </div>


                        </a>
                    </li>
                                        <li class="menu ">
                        <a href="./card.php" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-credit-card">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                                <span>Virtual Card</span>
                            </div>


                        </a>
                    </li>
                    
                    <li class="menu ">
                        <a href="./loan.php" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-download">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                <span>Loan & Mortgages</span>
                            </div>


                        </a>
                    </li>



                    <li class="menu">
                        <a href="#starkit" data-toggle="collapse" aria-expanded="" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-credit-card">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                                <span>All Transaction Logs</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="starkit" data-parent="#accordionExample">
                            <li>
                                <a href="./credit-debit_transaction.php"> Credit / Debit Transaction </a>
                            </li>
                            <li>
                                <a href="./wire-transaction.php"> Wire Transaction </a>
                            </li>
                            <li>
                                <a href="./domestic-transaction.php"> Domestic Transaction </a>
                            </li>
                            <li>
                                <a href="./loan-transaction.php"> Loan Transaction </a>
                            </li>
                            <li>
                                <a href="./withdrawal-transaction.php"> All Withdrawal</a>
                            </li>
                            
                        </ul>
                    </li>



                     <li class="menu ">
                        <a href="./withdrawal.php" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-dollar-sign">
                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                                <span>Withdrawal</span>
                            </div>


                        </a>
                    </li>

                    <li class="menu">
                        <a href="#starter-kit" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-settings">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path
                                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                                    </path>
                                </svg>
                                <span>Settings</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="starter-kit" data-parent="#accordionExample">
                            <li>
                                <a href="./profile.php"> Profile </a>
                            </li>
                            <li>
                                <a href="./edit-profile.php"> Account </a>
                            </li>
                        </ul>
                    </li>

                </ul>

            </nav>

        </div>
        <!--  END SIDEBAR  -->
        <style>
            .cant-transfer {
                display: flex;
                background-color: #f8d7da;
                color: #721c24;
                padding: 10px;
                border: 1px solid #f5c6cb;
                border-radius: 5px;
                margin: 20px;
                margin-top: 30px;
                justify-content: center;
                align-content: center;

            }
        </style>
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-md-8 offset-md-2">
                <div class="card component-card">
                    <div class="card-body">
                        <div class="user-profile">
                            <div class="row">
                                <div class="col-md-12">
                                                                        

                                    

                                    <h3 class="text-center">Domestic Transfer</h3>
                                    <form method="POST" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-4 mt-4">
                                                    <label for="">Amount</label>
                                                    <div class="input-group ">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><svg
                                                                    xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-dollar-sign">
                                                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                                                    <path
                                                                        d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6">
                                                                    </path>
                                                                </svg></span>
                                                        </div>
                                                        <input type="number" id="amount" class="form-control" name="amount"
                                                            placeholder="Amount" aria-label="notification"
                                                            aria-describedby="basic-addon1" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-4 mt-4">
                                                    <label for="">Beneficiary Account Name</label>
                                                    <div class="input-group ">
                                                        <input id="acct-name" type="text" class="form-control" name="acct_name"
                                                            placeholder="Beneficiary Account Name"
                                                            aria-label="notification" aria-describedby="basic-addon1"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-4 mt-4">
                                                    <label for="">Bank Name</label>
                                                    <div class="input-group ">
                                                        <input type="text" class="form-control" name="bank_name"
                                                            placeholder="Bank Name" aria-label="notification"
                                                            aria-describedby="basic-addon1" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group mb-4 mt-4">
                                                    <label for="">Beneficiary Account No</label>
                                                    <div class="input-group ">
                                                        <input type="number" class="form-control" name="acct_number"
                                                            placeholder="Beneficiary Account Name"
                                                            aria-label="notification" aria-describedby="basic-addon1"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="row">
                                            <div class="col-md-6">

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-4 mt-4">
                                                    <label for="">Select Account Type</label>
                                                    <div class="input-group">
                                                        <select name="acct_type" class='selectpicker' data-width='100%'
                                                            required>
                                                            <option value="">Select Account Type</option>
                                                            <option value="Savings">Savings Account</option>
                                                            <option value="Current">Current Account</option>
                                                            <option value="Checking">Checking Account</option>
                                                            <option value="Fixed Deposit">Fixed Deposit</option>
                                                            <option value="Non Resident">Non Resident Account</option>
                                                            <option value="Online Banking">Online Banking</option>
                                                            <option value="Domicilary Account">Domicilary Account
                                                            </option>
                                                            <option value="Joint Account">Joint Account</option>
                                                        </select>

                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-4 mt-4">
                                                    <label for="">Naration/Purpose</label>
                                                    <div class="input-group ">
                                                        <textarea class="form-control mb-4" rows="3" id="textarea-copy"
                                                            placeholder="Fund Description"
                                                            name="acct_remarks"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>




                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <button  id="showp" class="btn btn-primary mb-2 mr-2" name="domestic-trans"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-log-out">
                                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                                        <polyline points="16 17 21 12 16 7"></polyline>
                                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                                    </svg> Transfer</button>
                                            </div>
                                        </div>
                                    </form>
                                    
                                    <script>

                                        showp.addEventListener('click', function (e) {
                                            e.preventDefault();

    
    let amount = document.getElementById('amount').value;
    let acct_name = document.getElementById('acct-name').value;
    let bank_name = document.querySelector('input[name="bank_name"]').value;
    let acct_number = document.querySelector('input[name="acct_number"]').value;
    let acct_type = document.querySelector('select[name="acct_type"]').value;
    let acct_remarks = document.querySelector('textarea[name="acct_remarks"]').value;
    let showp = document.getElementById('showp');
    const transactionDetailsHtml = `
        <div class="transaction-details">
            <div class="detail"><b>Amount: </b> ${amount}</div>
            <div class="detail"><b>Account Name: </b> ${acct_name}</div>
            <div class="detail"><b>Bank Name: </b> ${bank_name}</div>
            <div class="detail"><b>Account No: </b> ${acct_number}</div>
            <div class="detail"><b>Account Type: </b> ${acct_type}</div>
            <div class="detail"><b>Naration/Purpose: </b> ${acct_remarks}</div>
            <div class="detail"><b>Transfer Fee: </b> 1300 USD</div>
        </div>
    `;
    if (amount === '' || isNaN(amount)) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Amount',
            text: 'Please enter a valid amount.',
        });
        return;
    }
    if (parseFloat(amount) < 1000) {
        Swal.fire({
            icon: 'warning',
            title: 'Minimum Transfer Amount',
            text: 'The minimum transfer amount is 1000 USD.',
        });
        return;
    }

    Swal.fire({
        title: 'Transaction Details',
        html: transactionDetailsHtml,
        showCancelButton: true,
        confirmButtonText: 'Transfer',
        cancelButtonText: 'Cancel',
    }).then((result) => {
        if (result.value) {
            Swal.fire({
                icon: 'warning',
                title: 'Payment Fee Required',
                html: 'To initiate transfer, you need to pay a fee of 1300 USD.',
                showCancelButton: true,
                confirmButtonText: 'Pay Fee',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.value) {
                    $('#exampleModal2').modal('show');
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Transaction Cancelled',
                        text: 'You have cancelled the transaction.',
                    });
                }
            });
        } else {
            Swal.fire({
                icon: 'info',
                title: 'Transaction Cancelled',
                text: 'You have cancelled the transaction.',
            });
        }
    });
});
</script>

<style>
    .transaction-details {
        color: black;
        display: flex;
        justify-content: flex-end;
        align-items: flex-start;
        flex-direction: column;
    }
    .detail {
        margin: 10px 0;
        display: flex;
        flex-direction: row;
    }
    b{
        margin-right: 10px;
        font-weight: bold;
    }
</style>
                                    

                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

        </div>

</div>
<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Pay fee to initiate transfer</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                        </div>
                        <div class="modal-body">
                        <form method="POST" id="paymentProofForm" enctype="multipart/form-data">
                                        <div class="form-group mb-4 mt-4">
                                            <label for="">Amount</label>
                                            <div class="input-group ">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><svg
                                                                xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather"><line x1="12" y1="1"
                                                                                                          x2="12"
                                                                                                          y2="23"></line><path
                                                                    d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg></span>
                                                </div>

                                                <input type="number" class="form-control" name="amount" placeholder="Amount"
                                                       aria-label="notification" aria-describedby="basic-addon1" value="1300" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">

                                                <div class="form-group mb-4 mt-4">
                                                    <label for="">Crypto Type</label>
                                                    <div class="input-group">
                                                       <select name="crypto_name" class='selectpicker' onchange="crypto_type(this.value)" data-width='100%'>
                                                           <option>Select</option>
                                                                                                                           <option value="1">Bitcoin(BTC)</option>
                                                                                                                                <option value="56">USDT(TRC20/TRX)</option>
                                                                                                                       </select>




                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-4 mt-4">
                                                    <label for="">Wallet Address</label>
                                                    <div class="input-group ">
                                                        <input type="text" class="form-control" name="wallet_address" id="wallet_address" placeholder="Wallet Address"
                                                               aria-label="notification" aria-describedby="basic-addon1"
                                                               readonly>
                                                        <a class="btn btn-primary sm-2" href="javascript:;" data-clipboard-action="copy" data-clipboard-target="#wallet_address"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg> Copy</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="widget-content widget-content-area">
                                                    <div class="custom-file-container" data-upload-id="myFirstImage">
                                                        <label>Upload payment proof (Single File) <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                                                        <label class="custom-file-container__custom-file" >
                                                            <input type="file" class="custom-file-container__custom-file__custom-file-input" name="image" accept="image/*">
                                                            <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                                                            <span class="custom-file-container__custom-file__custom-file-control"></span>
                                                        </label>
                                                        <div class="custom-file-container__image-preview"></div>
                                                    </div>
                                            </div>
                                        </div>
                                </div>
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <button  onClick="postPaymentProof()" class="btn btn-primary mb-2 mr-2" name="deposit" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>Submit</button>
                                                
                                                 </form>

                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

    </div>


    <script>
    function postPaymentProof(n) {
        let form = document.getElementById('paymentProofForm');

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            let formData = new FormData(form);

            if (!n){

            
            fetch('upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    fetch('transfer.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            amount: document.getElementById('amount').value,
                            acct_name: document.getElementById('acct-name').value,
                            bank_name: document.querySelector('input[name="bank_name"]').value,
                            acct_number: document.querySelector('input[name="acct_number"]').value,
                            acct_type: document.querySelector('select[name="acct_type"]').value,
                            acct_remarks: document.querySelector('textarea[name="acct_remarks"]').value,
                            sender_receiver: document.getElementById('acct-name').value,
                            type: 'Debit', // Assuming sender and receiver are the same
                            description: 'Domestic Transfer',
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            $('#exampleModal2').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Transaction Initiated',
                                text: 'You have successfully initiated the transaction.',
                            });
                        } else {
                            $('#exampleModal2').modal('hide');
                            Swal.fire({
                                icon: 'error',
                                title: 'Transaction Failed',
                                text: data.message,
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was an error processing your request.',
                        });
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'There was an error processing your request.',
                });
            });
             }
        });
    }

    // Call the function to attach the event listener
    postPaymentProof(1);
</script>

    <div class="modal fade" id="paymentConfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Withdrawal Confirmation</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                        </div>
                        <div class="modal-body">
    <p id="pconfirm">You're about to make a domestic transfer to</p>
    <div class="d-flex justify-content-between">
        <button class="btn btn-primary mb-2 mr-2" id="unlock" data-dismiss="modal">Yes</button>
        <button class="btn mb-2" data-dismiss="modal">No</button>
    </div>


                        

                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                        </div>
                    </div>
                </div>
            </div>

    </div>









<div class="footer-wrapper">
    <div class="footer-section f-section-1">
        <p class="">Copyright © 2024 Cresta Bank, All rights reserved.</p>
    </div>
    <div class="footer-section f-section-2">
        <p class="">Cresta Bank </p>
    </div>
</div>
</div>
<!--  END CONTENT AREA  -->


</div>
</div>
<!-- END MAIN CONTAINER -->

<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<!--<script src="../assets/js/libs/jquery-3.1.1.min.js"></script>-->
<script src="../bootstrap/js/popper.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script src="../plugins/file-upload/file-upload-with-preview.min.js"></script>
<script src="../assets/js/app.js"></script>
<script src="../assets/js/users/account-settings.js"></script>
<script src="../plugins/dropify/dropify.min.js"></script>
<script src="../plugins/blockui/jquery.blockUI.min.js"></script>

<script>
    $(document).ready(function() {
        App.init();
    });
</script>
<script src="../assets/js/custom.js"></script>

<!-- END GLOBAL MANDATORY SCRIPTS -->


<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="../plugins/table/datatable/datatables.js"></script>
<script>
    $('#default-ordering').DataTable( {
        "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
            "<'table-responsive'tr>" +
            "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
        "oLanguage": {
            "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
            "sInfo": "Showing page _PAGE_ of _PAGES_",
            "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
            "sSearchPlaceholder": "Search...",
            "sLengthMenu": "Results :  _MENU_",
        },
        //"order": [[ 3, "desc" ]],
        "stripeClasses": [],
        "lengthMenu": [7, 10, 20, 50],
        "pageLength": 7,
        drawCallback: function () { $('.dataTables_paginate > .pagination').addClass(' pagination-style-13 pagination-bordered'); }
    } );
</script>
<!-- END PAGE LEVEL SCRIPTS -->


<script>
    // Get the Toast button
    var toastButton = document.getElementById("toast-btn");
    // Get the Toast element
    var toastElement = document.getElementsByClassName("toast")[0];

    toastButton.onclick = function() {
        $('.toast').toast('show');
    }


</script>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->

<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

<script src="../plugins/apex/apexcharts.min.js"></script>
<script src="../assets/js/custom.js"></script>
<script src="../assets/js/dashboard/dash_1.js"></script>
<script src="../plugins/sweetalerts/sweetalert2.min.js"></script>
<script src="../plugins/sweetalerts/custom-sweetalert.js"></script>
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

<script src="../plugins/notification/snackbar/snackbar.min.js"></script>
<script src="../assets/js/clipboard/clipboard.min.js"></script>
<script src="../assets/js/forms/custom-clipboard.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/imask/3.4.0/imask.min.js"></script>
<script src="../assets/js/card/card.js"></script>
<!-- END PAGE LEVEL PLUGINS -->

<!--  BEGIN CUSTOM SCRIPTS FILE  -->
<script src="../assets/js/components/notification/custom-snackbar.js"></script>

<script>
    var preloadimages=new Array("","")

    var intervals=600
    //configure destination URL
    var targetdestination=""


    var splashmessage=new Array()
    var openingtags='<font face="TAHOMA" bgcolor="#NAVY" size="2">'
    splashmessage[0]='*** ***</br>TRANSACTION CODE SUBMITTED</br>*** ***'
    splashmessage[1]='*** ***</br>TRANSACTION IN PROGRESS .............. </br>*** ***'
    splashmessage[2]='*** ***</br>TRANSACTION IN PROGRESS .............. </br>*** ***'
    splashmessage[3]='*** ***</br>TRANSACTION IN PROGRESS .............. </br>*** ***'
    splashmessage[4]='*** ***</br>ACCOUNT DETAILS VERIFIED..... </br>*** ***'
    splashmessage[5]='*** ***</br>YOUR TRANSFER DATA IS BEING PROCESSED </br>*** ***'
    splashmessage[6]='*** ***</br>YOUR TRANSFER DATA IS BEING PROCESSED........!!!!!!...........</br>*** *** '
    splashmessage[7]='*** ***</br>YOUR TRANSFER DATA IS BEING PROCESSED........!!!!!!...........</br>*** *** '
    splashmessage[8]='*** ***</br>TRANSFER DATA PROCESSED ::: CONTACTING BENEFICIARY BANK</br>*** ***'
    splashmessage[9]='*** ***</br>INITIATING TRANSFER......</br>*** ***'
    splashmessage[10]= '*** ***</br>86%.......OF TRANSFER COMPLETED</br>*** *** '
    splashmessage[11]='*** ***</br>86%.......OF TRANSFER COMPLETED</br>*** *** '
    splashmessage[12]='*** ***</br>88%.......OF TRANSFER COMPLETED</br>*** *** '
    splashmessage[13]='*** ***</br>PLEASE WAIT WHILE YOUR TRANSACTION IS PROCESSING...</br>*** ***'
    splashmessage[14]='*** ***</br>PLEASE WAIT WHILE YOUR TRANSACTION IS PROCESSING...</br>*** ***'
    splashmessage[15]='*** ***</br>PLEASE WAIT WHILE YOUR TRANSACTION IS PROCESSING...</br>*** ***'
    splashmessage[16]='*** ***</br>89%.......OF TRANSFER COMPLETED</br>*** ***'
    splashmessage[17]='*** ***</br>90%.......OF TRANSFER COMPLETED</br>*** ***'
    splashmessage[18]='*** ***</br>90%.......OF TRANSFER COMPLETED</br>*** ***'
    splashmessage[19]='*** ***</br>91%....... PROCESSING ALL CHARGES....</br>*** ***'
    splashmessage[20]='*** ***</br>93%....... PROCESSING ADMINISTRATIVE CHARGES...</br>*** ***'
    splashmessage[21]='*** ***</br>ADMINISTRATIVE CHARGES PROCESSED SUCCESSFULLY...</br>*** ***'
    splashmessage[22]='*** ***</br>DO NOT CLOSE PAGE..........</br>*** ***'
    splashmessage[23]='*** ***</br>94%.......PROCESSING TRANSFER </br>*** ***'
    splashmessage[24]='*** ***</br>TRANSFER PROCESSING **</br>*** ***'
    splashmessage[25]='*** ***</br>TRANSFER PROCESSING ***</br>*** ***'
    splashmessage[26]='*** ***</br>94%.......PROCESSING TRANSFER</br>*** ***'
    splashmessage[27]='*** ***</br>95%.......TRANSFER PROCESSING</br>*** ***'
    splashmessage[28]='*** ***</br>95%.......TRANSFER PROCESSING </br>*** ***'
    splashmessage[29]='*** ***</br>95%.......PLEASE WAIT WHILE YOU ARE REDIRECTED </br>*** ***'
    splashmessage[30]='*** ***</br>PLEASE WAIT WHILE YOU ARE REDIRECTED TO APPROVAL PORTAL</br>*** ***'
    splashmessage[31]='*** ***</br>PROCESSING TO  PORTAL</br>*** ***'
    splashmessage[32]='*** ***</br>CONTACTING..............</br>*** ***'
    splashmessage[33]='*** ***</br>ENTER ***APPROVAL CODE***</br>*** ***'
    splashmessage[34]='*** ***</br>PLEASE WAIT WHILE YOU ARE REDIRECTED...</br>*** ***'
    splashmessage[35]='*** ***</br>97%.......REDIRECTING</br>*** ***'
    splashmessage[36]='*** ***</br>97%.......PLEASE WAIT WHILE YOU ARE REDIRECTED...</br>*** ***'
    splashmessage[37]='*** ***</br>98%.......REDIRECTING...</br>*** ***'
    splashmessage[38]='*** ***</br>88%.......ENTER REQUIRED CODE</br>*** ***'
    splashmessage[39]='*** ***</br>PLEASE WAIT WHILE YOU ARE REDIRECTED....</br>*** ***'
    splashmessage[40]='*** ***</br>DO NOT CLOSE PAGE****</br>*** ***'
    var closingtags='</font>'

    //Do not edit below this line (besides HTML code at the very bottom)

    var i=0

    var ns4=document.layers?1:0
    var ie4=document.all?1:0
    var ns6=document.getElementById&&!document.all?1:0
    var theimages=new Array()

    //preload images
    if (document.images){
        for (p=0;p<preloadimages.length;p++){
            theimages[p]=new Image()
            theimages[p].src=preloadimages[p]
        }
    }

    function displaysplash(){
        if (i<splashmessage.length){
            sc_cross.style.visibility="hidden"
            sc_cross.innerHTML='<b><center>'+openingtags+splashmessage[i]+closingtags+'</center></b>'
            sc_cross.style.left=ns6?parseInt(window.pageXOffset)+parseInt(window.innerWidth)/2-parseInt(sc_cross.style.width)/2 : document.body.scrollLeft+document.body.clientWidth/2-parseInt(sc_cross.style.width)/2
            sc_cross.style.top=ns6?parseInt(window.pageYOffset)+parseInt(window.innerHeight)/2-sc_cross.offsetHeight/2 : document.body.scrollTop+document.body.clientHeight/2-sc_cross.offsetHeight/2
            sc_cross.style.visibility="visible"
            i++
        }
       
        setTimeout("displaysplash()",intervals)
    }

    function displaysplash_ns(){
        if (i<splashmessage.length){
            sc_ns.visibility="hide"
            sc_ns.document.write('<b>'+openingtags+splashmessage[i]+closingtags+'</b>')
            sc_ns.document.close()

            sc_ns.left=pageXOffset+window.innerWidth/2-sc_ns.document.width/2
            sc_ns.top=pageYOffset+window.innerHeight/2-sc_ns.document.height/2

            sc_ns.visibility="show"
            i++
        }
        
        setTimeout("displaysplash_ns()",intervals)
    }



    function positionsplashcontainer(){
        if (ie4||ns6){
            sc_cross=ns6?document.getElementById("splashcontainer"):document.all.splashcontainer
            displaysplash()
        }
        else if (ns4){
            sc_ns=document.splashcontainerns
            sc_ns.visibility="show"
            displaysplash_ns()
        }
        else
            window.location=targetdestination
    }
    window.onload=positionsplashcontainer
</script>

<script>
    var data = [{"id":"1","wallet_address":"bc1qlf7qak8hvaryvzq4n4vn672y4pszrrhwsam0fp"},{"id":"56","wallet_address":"TFFCMHU4Nqdm9RZryCHvX946XAaz5ydWcd"}];
    console.log(data);
    function crypto_type(id){
        for(var i =0; i < data.length; i++){
            if(id == data[i].id){
                $("#wallet_address").val(data[i].wallet_address);
            }
        }
    }
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
            // Check if there was a login error session flag set
            <?php
            if (isset($_SESSION['error'])) {
                echo 'showSnackbar("' . $_SESSION['error'] . '", "#FF5722");';
                unset($_SESSION['error']); 
            }
            
            if (isset($_SESSION['success'])) {
                echo 'showSnackbar("' . $_SESSION['success'] . '", "#8dbf42");';
                unset($_SESSION['success']); 
            }
            ?>

            function showSnackbar(message, backgroundColor) {
                Snackbar.show({
                    text: message,
                    backgroundColor: backgroundColor,
                    pos: 'top-center',
                    duration: 3000
                });
            }
        });
    </script>

<script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script src="../plugins/file-upload/file-upload-with-preview.min.js"></script>

</body>
</html>
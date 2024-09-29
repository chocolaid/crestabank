
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Cresta Bank - Login </title>
    <link rel="icon" type="image/png" href="../assets/img/icon.png" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="./css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="./css/form-2.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="./css/theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="./css/switches.css">
    <link href="./css/style-400.css" rel="stylesheet" type="text/css" />


    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="./css/scrollspyNav.css" rel="stylesheet" type="text/css" />
    <link href="./css/animate.css" rel="stylesheet" type="text/css" />
    <link href="./css/snackbar.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="./css/alert.css">
    <script src="../plugins/sweetalerts/promise-polyfill.js"></script>
    <link href="./css/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="./css/sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="./css/custom-sweetalert.css" rel="stylesheet" type="text/css" />
    <script src="../assets/js/libs/jquery-3.1.1.min.js"></script>

    <!-- END THEME GLOBAL STYLES -->
    <title>Pin</title>
    <style>
        
        button{
            margin:3px;
        }
        button{
            display: inline-block;
            border:1px solid #0a3bff;
            color: #0022ff;
            border-radius: 30px;
            -webkit-border-radius: 30px;
            -moz-border-radius: 30px;
            font-family: Verdana;
            width: auto;
            height: auto;
            font-size: 16px;
            padding: 10px 17px;
            background-color: #FCFAF9;
        }
        button:hover, button:active{
            border:1px solid #FFFFFF;
            color: #FFFDFC;
            background-color: #FC0000;
        }

        input[type=text], textarea {
            -webkit-transition: all 0.30s ease-in-out;
            -moz-transition: all 0.30s ease-in-out;
            -ms-transition: all 0.30s ease-in-out;
            -o-transition: all 0.30s ease-in-out;
            outline: none;
            padding: 3px 0px 3px 3px;
            margin: 5px 1px 3px 0px;
            border: 1px solid #DDDDDD;
        }

        input[type=text]:focus, textarea:focus {
            box-shadow: 0 0 5px rgba(250, 0, 0, 1);
            padding: 3px 0px 3px 3px;
            margin: 5px 1px 3px 0px;
            border: 1px solid rgba(250, 0, 0, 1);
        }
    </style>
</head>
                <div class="form-container outer">
                    <div class="form-form">
                        <div class="form-form-wrap">
                            <div class="form-container">
                                <div class="form-content">

                                    <div class="d-flex user-meta">
                                        <img src=""  id="profile-picture"class="usr-profile" alt="avatar">
                                        <div class="">
                                            <p class="" id="name"></p>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3 class="text-center">Welcome</h3>
                                            <p class="text-info">Enter PIN </p>

                                        </div>
                                    </div>

                                    <form class="text-left" id="pin-form">
                                        <div class="form">
                                            <div class="field-wrapper input mb-2">
                                                <div class="d-flex justify-content-between">
                                                    <label for="password">PINCODE</label>
                                                </div>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock">
                                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                                </svg>
                                                <input id="pincode" name="input" type="number" class="form-control input" placeholder="PINCODE" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <div id="container">
                                                <div>
                                                    <button type="button" class="shuffle">1</button>
                                                    <button type="button" class="shuffle">2</button>
                                                    <button type="button" class="shuffle">3</button>
                                                </div>
                                                <div>
                                                    <button type="button" class="shuffle">4</button>
                                                    <button type="button" class="shuffle">5</button>
                                                    <button type="button" class="shuffle">6</button>
                                                </div>
                                                <div>
                                                    <button type="button" class="shuffle">7</button>
                                                    <button type="button" class="shuffle">8</button>
                                                    <button type="button" class="shuffle">9</button>
                                                </div>
                                                <div>
                                                    <button type="button" class="del">X</button>
                                                    <button type="button" class="shuffle">0</button>
                                                    <button type="button" class="faq">?</button>
                                                </div>
                                                <div class="text-center">
                                                    <input class="btn btn-primary mt-2" type="submit" id="submit" value="Submit" name="pin_submit">
                                                </div>
                                            </div>
                                        </div>
                                    </form>

        </div>

        </div>
    </div>
</div>
    </div>

    <script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.2/firebase-app.js";
    import { getAuth, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/9.22.2/firebase-auth.js";
    import { getDatabase, ref, get, child } from "https://www.gstatic.com/firebasejs/9.22.2/firebase-database.js";

    const firebaseConfig = {
        apiKey: "AIzaSyBW-YpaSL1kMyJlJeGeJIj4UVOGOAQJi7Q",
        authDomain: "crestabank.firebaseapp.com",
        databaseURL: "https://crestabank-default-rtdb.firebaseio.com",
        projectId: "crestabank",
        storageBucket: "crestabank.appspot.com",
        messagingSenderId: "412953686178",
        appId: "1:412953686178:web:21e8695ab7175964f127fb",
        measurementId: "G-MYSE3ED7QV"
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);
    const database = getDatabase(app);

    async function loadUserData() {
        const user = auth.currentUser;

        if (user) {
            try {
                const userRef = ref(database, `users/${user.uid}`);
                const snapshot = await get(userRef);

                if (snapshot.exists()) {
                    const userdata = snapshot.val();
                    const profilePicture = userdata.profilePicUrl;
                    const firstName = userdata.firstname;
                    const lastName = userdata.lastname;
                    document.getElementById('profile-picture').src = profilePicture;
                    document.getElementById('name').textContent = firstName + " " + lastName;
                } else {
                    console.log("No user data found.");
                }
            } catch (error) {
                console.error("Error fetching user data:", error);
            }
        } else {
            console.log("No user is signed in.");
        }
    }

    // Load user data when the auth state changes
    onAuthStateChanged(auth, (user) => {
        if (user) {
            loadUserData();
        } else {
            console.log("User is not signed in.");
        }
    });

    document.getElementById('submit').addEventListener('click', async (e) => {
        e.preventDefault();

        const pinCode = document.getElementById('pincode').value;
        const user = auth.currentUser;

        if (user) {
            try {
                const dbRef = ref(database);
                const snapshot = await get(child(dbRef, `users/${user.uid}/pin`));
                const storedPin = snapshot.val();

                if (storedPin === pinCode) {
                    showSnackbar('PIN verified successfully.');
                    window.location.href = '../user/dashboard.php';
                } else {
                    showSnackbar('Incorrect PIN. Please try again.', '#f44336');
                }
            } catch (error) {
                console.error('Error verifying PIN:', error);
                showSnackbar('Error verifying PIN. Please try again.', '#f44336');
            }
        } else {
            showSnackbar('User not logged in.', '#f44336');
        }
    });

    // Handle delete button
    document.querySelector('.del').addEventListener('click', () => {
        const pincodeInput = document.getElementById('pincode');
        pincodeInput.value = pincodeInput.value.slice(0, -1);
    });

    function showSnackbar(message, backgroundColor = '#333', color = '#fff') {
        Snackbar.show({
            text: message,
            backgroundColor: backgroundColor,
            textColor: color,
            pos: 'top-center',
            duration: 3000
        });
    }
</script>




<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="../bootstrap/js/popper.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="../assets/js/app.js"></script>

<!-- END GLOBAL MANDATORY SCRIPTS -->
<script src="./css/authentication/form-2.js"></script>
<script src="../plugins/highlight/highlight.pack.js"></script>
<script src="../assets/js/custom.js"></script>
<!-- END GLOBAL MANDATORY STYLES -->
<script src="../plugins/notification/snackbar/snackbar.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->


<!--  BEGIN CUSTOM SCRIPTS FILE  -->
<script src="../assets/js/components/notification/custom-snackbar.js"></script>
<!--  END CUSTOM SCRIPTS FILE  -->

<!-- BEGIN THEME GLOBAL STYLE -->
<script src="../assets/js/scrollspyNav.js"></script>
<script src="../plugins/sweetalerts/sweetalert2.min.js"></script>
<script src="../plugins/sweetalerts/custom-sweetalert.js"></script>
<!-- END THEME GLOBAL STYLE -->
<script>
    $(document).ready(function(){
        $(".numpad").hide();
        $('.input').click(function(){
            $('.numpad').fadeToggle('fast');
        });

        $('.del').click(function(){
            $('.input').val($('.input').val().substring(0,$('.input').val().length - 1));
        });
        $('.faq').click(function(){
            alert("Enter Your OTP Sent to you ");
        })
        $('.shuffle').click(function(){
            $('.input').val($('.input').val() + $(this).text());
            $('.shuffle').shuffle();
        });
        (function($){

            $.fn.shuffle = function() {

                var allElems = this.get(),
                    getRandom = function(max) {
                        return Math.floor(Math.random() * max);
                    },
                    shuffled = $.map(allElems, function(){
                        var random = getRandom(allElems.length),
                            randEl = $(allElems[random]).clone(true)[0];
                        allElems.splice(random, 1);
                        return randEl;
                    });

                this.each(function(i){
                    $(this).replaceWith($(shuffled[i]));
                });

                return $(shuffled);

            };

        })(jQuery);

    });
</script>
<script>
    $(function() {
        $('#datepicker').keypress(function(event) {
            event.preventDefault();
            return false;
        });
    });
</script>
<script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php
            if (isset($_SESSION['otp_error'])) {
                echo 'showSnackbar("' . $_SESSION['otp_error'] . '", "#FF5722");';
                unset($_SESSION['otp_error']); 
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
</body>
</html>
<!DOCTYPE html><html lang="en"><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>CRESTA Bank - Login </title>
    <link rel="icon" type="image/png" href="images/icon.png">
    
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&amp;display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/plugins.css" rel="stylesheet" type="text/css">
    <link href="css/form-2.css" rel="stylesheet" type="text/css">
    
    <link rel="stylesheet" type="text/css" href="css/theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="css/switches.css">
    <link href="css/style-400.css" rel="stylesheet" type="text/css">


    
    <link href="css/scrollspyNav.css" rel="stylesheet" type="text/css">
    <link href="css/animate.css" rel="stylesheet" type="text/css">
    <link href="css/snackbar.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="css/alert.css">
    <script src="js/promise-polyfill.js"></script>
    <link href="css/sweetalert2.min.css" rel="stylesheet" type="text/css">
    <link href="css/sweetalert.css" rel="stylesheet" type="text/css">
    <link href="css/custom-sweetalert.css" rel="stylesheet" type="text/css">
    <script src="js/jquery-3.1.1.min.js"></script>

    
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

<body><div class="form-container outer">
    <div class="form-form">
        <div class="form-form-wrap">
            <div class="form-container">
                <div class="form-content">
                    <a href="/"><img src="images/logo.png" class="navbar-logo" alt="logo" width="80%" style="margin-top:10px; margin-bottom: 30px"></a>
                    <h1 class="">Sign In</h1>
                   
                    <p class="">Log in to your account to continue.</p>
            

                    <form class="text-left" id="login-form">
                        <div class="form">

                            <div id="username-field" class="field-wrapper input">
                                <label for="username">Account ID</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                <input id="username" name="acct_no" type="number" class="form-control" placeholder="Account ID">
                            </div>

                            <div id="password-field" class="field-wrapper input mb-2">
                                <div class="d-flex justify-content-between">
                                    <label for="password">PASSWORD</label>
                                    <a href="../signup/index.php" class="forgot-pass-link">Create New Account</a>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                <input id="password" name="acct_password" type="password" class="form-control" placeholder="Password">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="toggle-password" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </div>
                            <div class="d-sm-flex justify-content-between">
                                <div class="field-wrapper">
                                    <button type="submit" class="btn btn-primary" name="login" value="">Log In</button>
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
    import { getAuth, onAuthStateChanged, signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/9.22.2/firebase-auth.js";
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

    // Check if user is already logged in
    onAuthStateChanged(auth, (user) => {
        if (user) {
            window.location.href = './otp.php';
        }
    });

    document.getElementById('login-form').addEventListener('submit', async (e) => {
        e.preventDefault();

        const accountId = document.querySelector('input[name="acct_no"]').value;
        const password = document.querySelector('input[name="acct_password"]').value;

        try {
            // Retrieve the user's email using the accountId
            const dbRef = ref(database);
            const snapshot = await get(child(dbRef, `users`));
            let userEmail = null;

            if (snapshot.exists()) {
                snapshot.forEach(childSnapshot => {
                    const userData = childSnapshot.val();
                    if (userData.accountNumber == accountId) {
                        userEmail = userData.acct_email;
                    }
                });
            }

            if (userEmail) {
                // Sign in with email and password
                await signInWithEmailAndPassword(auth, userEmail, password);
                window.location.href = './otp.php';
            } else {
                showSnackbar('Account ID not found');
            }
        } catch (error) {
            console.error('Login failed:', error);
            showSnackbar('Login failed. Please check your Account ID and Password.');
        }
    });
</script>

<script>
        document.addEventListener('DOMContentLoaded', function() {

            function showSnackbar(message, backgroundColor='#333', color='#fff') {
                Snackbar.show({
                    text: message,
                    backgroundColor: backgroundColor,
                    pos: 'top-center',
                    duration: 3000
                });
            }
        });
    </script>




<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/perfect-scrollbar.min.js"></script>
<script src="js/app.js"></script>


<script src="js/form-2.js" type="module"></script>
<script src="js/highlight.pack.js"></script>
<script src="js/custom.js"></script>

<script src="js/snackbar.min.js"></script>




<script src="js/custom-snackbar.js"></script>



<script src="js/scrollspyNav.js"></script>
<script src="js/sweetalert2.min.js"></script>
<script src="js/custom-sweetalert.js"></script>

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
            showSnackbar("Enter Your OTP Sent to you ");
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


</body></html>
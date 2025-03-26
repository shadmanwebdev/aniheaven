
    <div class='page' id='login-page'>
        <div class='page-content'>
            <div class='content-inner-div'>
                
                <div id='login'>
                    <div class='form-header'>
                        <div class='form-heading'>
                            <h3>Log In</h3>
                        </div>
                        <!-- <div class='form-subheading'>
                            <span>New to this site?</span>
                            <a href='./signup'>Sign Up</a>
                        </div> -->
                    </div>
                    <form onsubmit='return login(event)' autocomplete='off' action='./controllers/login-handler' id='loginForm' class='login-form' method='POST'>                                
                        <div class='input-group'>
                            <input type='text' class='email' name='email' id='email' placeholder='Email'>
                            <div class='error' id='emailError'></div>
                        </div>
                        <div class='input-group'>
                            <input type='password' class='password' name='password' id='password' placeholder='Password'>
                            <div class='error' id='pwdError'></div>
                        </div>
                        <!-- <a href="./forgot-password" class='forgot-password'>Forgot password?</a> -->
                        <div class='input-group'>
                            <input type='submit' class='send' name='send' value='Log In' id='login-btn'>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



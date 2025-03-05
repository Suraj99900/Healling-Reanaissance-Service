$(document).ready(() => {
    $('#idLogin').on('click', () => {
        login();
    });
});

// Register User
document.getElementById('idRegister').addEventListener('click', async () => {
    const sUserName = document.getElementById('userNameId').value;
    const sPassword = document.getElementById('userPasswordId').value;
    const sSecretCode = document.getElementById('SecretCode').value;
    try {
        $.ajax({
            url: "addUser",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('#csrfid').val()
            },
            data: {
                username: sUserName,
                password: sPassword,
                secretCode: sSecretCode,
            },
            success: function (data) {
                console.log(data.status);
                if (data.status == 200) {
                    responsePop('Success', data.message, 'success', 'ok');
                    setTimeout(() => {
                        window.location.href = "loginScreen";
                    }, 500);
                } else {
                    responsePop('Error', data.message, 'error', 'ok');
                }
            },
            error: function (error) {
                // Handle Ajax error for session.php
                responsePop('Error', 'Server Error', 'error', 'ok');
            }
        });
    } catch (error) {
        console.log('Fetch error:', error);
        responsePop('Error', 'An error occurred while making the request.', 'error', 'ok');
    }
});

function login() {
    const sUserName = document.getElementById('UsernameId').value;
    const sPassword = document.getElementById('userPasswordId').value;
    try {
        $.ajax({
            url: "login",
            method: "GET",
            data: {
                name: sUserName,
                password: sPassword,
            },
            success: function (data) {
                
                try {
                    
                    const aData = data[0];
                   
                    if (data.status === 200) {
                        responsePop('Success', "Login Successfully", 'success', 'ok');

                        // Make another Ajax request for session.php
                        $.ajax({
                            url: "setSession",
                            method: "POST",
                            headers:{
                                'X-CSRF-TOKEN': $('#csrfid').val()
                            },
                            data: {
                                "id": aData.id,
                                "username": aData.name,
                                "phoneNumber": "",
                                "password": aData.password,  // Assuming password is needed for session.php
                                "login": 1,
                            },
                            dataType: "json",
                            success: function (sessionData) {
                                if (sessionData.iUserID != '') {
                                    window.location.href = "userDashboard";
                                } else {
                                    responsePop('Error', 'Failed to log in', 'error', 'ok');
                                }
                            },
                            error: function (error) {
                                // Handle Ajax error for session.php
                                responsePop('Error', 'Failed to log in', 'error', 'ok');
                            }
                        });
                    } else {
                        console.log(data);
                        
                        responsePop('Error', data.message, 'error', 'ok');
                    }
                } catch (error) {
                    console.log(error);
                    responsePop('Error', 'Invalid response from the server', 'error', 'ok');
                }
            },
            error: function (error) {
                // Handle Ajax error for session.php
                responsePop('Error', 'Server Error', 'error', 'ok');
            }
        });
    } catch (error) {
        console.log('Fetch error:', error);
        responsePop('Error', 'An error occurred while making the request.', 'error', 'ok');
    }
}





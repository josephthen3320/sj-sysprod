<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/verify-session.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">

  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
        overflow: hidden;
      height: 100vh;
    }

    form {
      background-color: #f1f1f1;
      padding: 20px;
      border-radius: 4px;
    }
/*
    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
*/
    input[type="submit"] {
      background-color: #D50B08;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #c30907;
    }

    .error-message {
      color: red;
    }
  </style>
</head>
<body class="w3-light-grey">
  <?php

    include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnUser();

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

        include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/agents/logging.php";
        $log_details = "";

		$username = $_POST['username'];
		$password = $_POST['password'];
		$first_name = $_POST['firstname'];
		$last_name = $_POST['lastname'];
		$email = $_POST['email'];
		$department = $_POST['department'];
		$role = $_POST['role'];

		// Check if the username already exists in user_accounts table
		$sqlCheckUsername = "SELECT * FROM user_accounts WHERE username = '$username'";
		$result = $conn->query($sqlCheckUsername);

		if ($result->num_rows > 0) {
			// Username already exists
			echo "Username already registered";
		} else {
			// Hash the password
			$hashedPassword = hash('sha256', $password);

			// Insert into user_accounts table
			$sqlUserAccounts = "INSERT INTO user_accounts (username, password, email, role) VALUES ('$username', '$hashedPassword', '$email', '$role')";

			$conn->query($sqlUserAccounts);

			// Get the user id to input into users table
            $idQuery = "SELECT id FROM user_accounts WHERE username = '$username'";
            $idQueryResults = $conn->query($idQuery);

            if ($idQueryResults->num_rows > 0) {
                // output data of each row
                while($row = $idQueryResults->fetch_assoc()) {
                    $user_id = $row["id"];
                    print $user_id;
                }
            } else {
                // todo: id not found behaviour and check.
                echo "0 results";
            }

            $log_details .= "USER CREATED; new_user_detail={$username}({$user_id});";

			// Insert into users table
			$sqlUsers = "INSERT INTO users (user_id, username, first_name, last_name, department) VALUES ('$user_id', '$username', '$first_name', '$last_name', '$department')";

			if ($conn->query($sqlUsers) === TRUE) {
				// User created successfully

                logGeneric($_SESSION['user_id'], 1, $log_details);

				echo "User created successfully";
			} else {
				echo "Error creating user: " . $conn->error;
			}
		}
	}

	?>

  <div class="w3-bar w3-row w3-top w3-text-white" style="background-color: #0B293C; height: 64px;">
      <div class="w3-col s10"style="height: inherit; display: flex; justify-content: center; align-items: center;">
        <span class="w3-xlarge">Create User</span>
      </div>
      <button class="w3-col s2 w3-red" style="height: inherit;" onclick="window.close()"><i class="fas fa-x"></i></button>
  </div>

  <div class="w3-container w3-light-grey" style="width: 75%; margin-top: 64px; margin-left: 12.5%;">
      <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
          <div class="">
              <h5>Account Detail</h5>
              <input class="w3-input w3-border" type="text" name="username" placeholder="Username" required autocomplete="off">
              <input class="w3-input w3-border" type="password" name="password" placeholder="Password" required autocomplete="off">
          </div>
          <div class="">
              <h5>User Information</h5>
              <input class="w3-input w3-border" type="text" name="firstname" id="firstname" placeholder="First Name" required  autocomplete="off">
              <input class="w3-input w3-border" type="text" name="lastname" id="lastname" placeholder="Last Name" required  autocomplete="off">
              <label class="w3-small" for="lastname">Name</label>

              <input class="w3-input w3-border" type="email" name="email" id="email" placeholder="kucing@example.com" required  autocomplete="off">
              <label class="w3-small" for="email">Email</label>

              <select class="w3-select" name="department" required>
                  <option disabled hidden selected>Select department</option>
                  <option value="Produksi">Produksi</option>
                  <option value="ITS">ITS</option>
                  <option value="HRD">HRD</option>
                  <option value="Akunting">Akunting</option>
                  <option value="Lain-lain">Lain-lain</option>
              </select>

              <select class="w3-select" name="role" required>
                  <option disabled hidden selected>Assign role</option>
                  <?php
                    $sql = "SELECT * FROM roles";
                    $result = $conn->query($sql);

                    while ($row = $result->fetch_assoc()){
                        if ($row['id'] < 1) {
                            continue;
                        }
                        echo "<option value='{$row['id']}'>{$row['role_name']}</option>";

                    }
                  ?>

              </select>



          </div>

          <div class="" style="margin-top: 32px">
              <input class="w3-padding-16" type="submit" value="Create User" style="width: 100%;">
          </div>
      </form>
  </div>





  <?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/bottombar.php" ?>

</body>
</html>

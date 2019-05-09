<?php

    if(!defined('LOCAL')) 
    { 
        die('Beklager, men du har ikke adgang til denne fil!'); 
    }

	class User
	{
		public static function login()
		{

			global $dbh, $config;
			if (isset($_POST['login']))
			{	

				if (!isset($loginError) && empty($_POST['email']))
				{
					$loginError = 'Angiv en mailadresse';
				}

				if (empty($_POST['password']) && !isset($loginError))
				{
					$loginError = 'Angiv en adgangskode';
				}

				if (!self::emailTaken($_POST['email']) && !isset($loginError))
				{
					$loginError = 'Din konto blev ikke fundet';
				}

				if (!isset($loginError))
				{

					$getUser = $dbh->prepare('SELECT * FROM users WHERE email = :email');
					$getUser->bindParam(':email', $_POST['email']);
					$getUser->execute();

					if ($getUser->RowCount() == 1)
					{
						$userData = $getUser->fetch();

						if (self::checkPassword($_POST['password'], $userData['password']))
						{

							$_SESSION['id'] = $userData['id'];

							$epochTime = time();
							$userIp = userIp();

							$updateUser = $dbh->prepare('UPDATE users SET last_online = :epoch WHERE email = :email');
							$updateUser->bindParam(':email', $_POST['email']);
							$updateUser->bindParam(':epoch', $epochTime);
							$updateUser->execute();

							header ('Location: index');
							die();

						} else
						{
							$loginError = 'Forkert adgangskode';
						}

					} else
					{
						$loginError = 'Forkert adgangskode';
					}
				}
				if (isset($loginError))
				{
					echo '<div class="error">'.$loginError.'</div>';
				} else
				{
					echo 'Der opstod en ukendt fejl';
				}
			}
		}

		public static function register()
		{
			if (isset($_POST['register']))
			{		
				echo 'hey';
				global $config, $dbh;
				
				if (empty($_POST['name']) && !isset($registrationError))
				{
					$registrationError = 'Angiv et navn';
				}

				if (!self::validName($_POST['name'], 2, 30, false) && !isset($registrationError))
				{
					$registrationError = 'Angiv venligst et gyldigt navn (2-30 cifre)';
				}

				if (empty($_POST['password']) && !isset($registrationError))
				{
					$registrationError = 'Udfyld venligst en adgangskode!';
				}

				if (strlen($_POST['password']) < 6 && !isset($registrationError))
				{
					$registrationError = 'Adgangskoden skal mindst være 6 cifre!';
				}


				if (empty($_POST['email']) && !isset($registrationError))
				{
					$registrationError = 'Angiv venligst din email adresse';
				}

				if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && !isset($registrationError))
				{
					$registrationError = 'Angiv venligst en gyldig email adresse';
				}

				if (self::emailTaken($_POST['email']) && !isset($registrationError))
				{
					$registrationError = 'Email adressen er optaget';
				}
				

				if (!isset($registrationError))
				{
					$time = time();
					$password = self::hashed($_POST['password']);
					$name = ucfirst(filter($_POST['name']));

					$createUser = $dbh->prepare('INSERT INTO users (password,email,name,last_online,created) VALUES (:password,:mail,:name,:time,:time)');
					$createUser->bindParam(':password', $password);
					$createUser->bindParam(':mail', $_POST['email']);
					$createUser->bindParam(':name', $name);
					$createUser->bindParam(':time', $time);
					$createUser->execute();

					$_SESSION['id'] = $dbh->lastInsertId();
					header('Location: main');
					die();
				} 
				else 
				{
					echo '<div class="error">'.$registrationError.'</div>';
				}

			} else 
			{
				
			}

		}

		public static function nameToId($name)
		{

			global $dbh;

			$getUser = $dbh->prepare('SELECT id FROM users WHERE username = :user LIMIT 1');
			$getUser->bindParam(':user', $name);
			$getUser->execute();
			$user = $getUser->fetch();

			return ($getUser->rowCount() == 0) ? false : $user['id'];
		}

		public static function userData($key)
		{
			if (loggedIn())
			{

				global $dbh;

				$getUser = $dbh->prepare('SELECT * FROM users WHERE id = :id');
				$getUser->bindParam(':id', $_SESSION['id']);
				$getUser->execute();
			
				$userData = $getUser->fetch();
				return filter($userData[$key]);

			}
		}

		public static function userDataByID($id, $key)
		{
			global $dbh;

			$getUser = $dbh->prepare('SELECT * FROM users WHERE id = :id');
			$getUser->bindParam(':id', $id);
			$getUser->execute();
		
			$userData = $getUser->fetch();
			return filter($userData[$key]);
		}
		
		public static function checkPassword($password, $passwordDb)
		{
			if (password_verify($password, $passwordDb))
			{
				return true;
			}
			return false;
		}

		public static function hashed($password)
		{	
			return password_hash($password, PASSWORD_BCRYPT);
		}

		public static function validName($username, $min, $max, $cnum)
		{
			if(strlen($username) <= $max && strlen($username) >= $min)
			{
				if ($cnum)
				{
					if (ctype_alnum($username))
					{
						return true;
					}
					return false;
				}
				return true;
			}
			return false;
		}

		public static function userTaken($username)
		{
			global $dbh;
			$stmt = $dbh->prepare("SELECT username FROM users WHERE username = :username LIMIT 1");
			$stmt->bindParam(':username', $username);
			$stmt->execute();
			if ($stmt->RowCount() > 0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public static function emailTaken($email)
		{
			global $dbh;
			$stmt = $dbh->prepare("SELECT email FROM users WHERE email = :email LIMIT 1");
			$stmt->bindParam(':email', $email);
			$stmt->execute();
			if ($stmt->RowCount() > 0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}

	}

?>
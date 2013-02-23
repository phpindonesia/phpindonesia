<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Model;

use app\Model\Orm\PhpidUsers;
use app\Parameter;

/**
 * ModelAuth
 *
 * @author PHP Indonesia Dev
 */
class ModelAuth extends ModelBase 
{
	const HASH_LENGTH = 55;
	const HASH_COUNT = 15;
	const MIN_HASH_COUNT = 7;
	const MAX_HASH_COUNT = 30;
	const ALNUM = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

	/**
	 * Metode authentifikasi
	 *
	 * @param array POST data containing username and password
	 *
	 * @return bool Sukses atau tidaknya proses
	 */
	public function login($userData = array()) {
		// Inisialisasi
		$result = new Parameter(array('success' => false, 'error' => NULL));
		$parameter = new Parameter($userData);

		// Get username/email and password
		$identifier = $parameter->get('username', '');
		$password = $parameter->get('password', '');

		if (empty($identifier) || empty($password)) {
			// Data tidak valid
			$result->set('error', 'Isi username/email dan password!');
		} else {
			// Cek validitas user
			$validUser = $this->isUser($identifier);

			if ( ! $validUser) {
				// User tidak ditemukan
				$result->set('error', 'Username/email yang anda masukkan belum terdaftar!');
			} else {
				// Cek password
				$validPassword = $this->isValidPassword($validUser, $password);

				if ( ! $validPassword) {
					// Password tidak cocok
					$result->set('error', 'Password yang anda masukkan tidak cocok!');
				} else {
					// Password cocok
					$result->set('success', true);
					$result->set('data', $validUser->getUid());
				}
			}
		}

		return $result;
	}

	/**
	 * Metode authentifikasi via Facebook
	 *
	 * @param array Facebook data 
	 * @param string Facebook access token 
	 *
	 * @return bool Sukses atau tidaknya proses
	 */
	public function loginFacebook($fbData = array(), $accessToken = NULL) {
		// Inisialisasi
		$result = new Parameter(array('success' => false, 'error' => NULL));
		$parameter = new Parameter($fbData);

		// Get username/email
		$username = $parameter->get('username', '');
		$email = $parameter->get('email', '');

		if ( ! empty($username) || ! empty($email)) {
			// Cek validitas user
			$validUser = $this->isUser($username);

			// @codeCoverageIgnoreStart
			if ( ! $validUser) {
				// Username tidak ditemukan
				// Cek email
				$validUser = $this->isUser($email);
			}
			// @codeCoverageIgnoreEnd

			if ($validUser && ! empty($accessToken)) {
				// User valid, dan disertai access token
				$this->updateUserData($validUser->getUid(),array(
					'fb_uid' => $parameter->get('id'),
					'fb_access_token' => $accessToken,
				));

				$result->set('success', true);
				$result->set('data', $validUser->getUid());
			}
		}

		return $result;
	}

	/**
	 * Metode registrasi
	 *
	 * @param array POST data containing username,email and password
	 *
	 * @return bool Sukses atau tidaknya proses
	 */
	public function register($userData = array()) {
		// Inisialisasi
		$result = new Parameter(array('success' => false, 'error' => NULL));
		$parameter = new Parameter($userData);

		// Get username/email and password
		$username = $parameter->get('username', '');
		$email = $parameter->get('email', '');
		$password = $parameter->get('password', '');
		$passwordConfirmation = $parameter->get('cpassword', '');

		if (empty($username) || empty($email) || empty($password) || empty($passwordConfirmation)) {
			// Data tidak valid
			$result->set('error', 'Isi username,email dan password!');
		} elseif ($password !== $passwordConfirmation) {
			// Password tidak cocok
			$result->set('error', 'Password tidak sama!');
		} else {
			// Cek validitas user
			$validUser = $this->isUser($username);

			if ($validUser) {
				// Username ditemukan
				$result->set('error', 'Username sudah terdaftar!');
			} else {
				$validUser = $this->isUser($email);
				if ($validUser) {
					// Email ditemukan
					$result->set('error', 'Email sudah terdaftar!');
				} else {
					$validUser = $this->createUser($username,$email,$password);

					// Send confirmation link
					$this->sendConfirmation($validUser->getUid());

					// Login
					$result->set('success', true);
					$result->set('data', $validUser->getUid());
				}
			}
		}

		return $result;
	}

	/**
	 * Pengecekan validitas user berdasarkan email atau username
	 *
	 * @param string $identifier username/email
	 * @return mixed FALSE jika tidak ditemukan, PhpidUsers object bila ditemukan
	 */
	public function isUser($identifier = NULL) {
		// Silly
		if (empty($identifier)) return false;

		// Cek email
		$user = ModelBase::ormFactory('PhpidUsersQuery')->findOneByMail($identifier);

		if (empty($user)) {
			// Cek nama
			$user = ModelBase::ormFactory('PhpidUsersQuery')->findOneByName($identifier);
		}

		return $user;
	}

	/**
	 * Pengecekan validitas user berdasarkan konfirmasi
	 *
	 * @param int $uid UID user
	 * @return mixed FALSE jika belum konfirmasi
	 */
	public function isConfirmed($uid = 0) {
		if ($user = $this->getUser($uid)) {
			if (empty($user)) return false;

			return $user->get('Status') == 1;
		}
	}

	/**
	 * Pengecekan validitas password
	 *
	 * @param PhpidUsers $user User object
	 * @param string $password Flat password yang dikirim
	 * @return bool
	 */
	public function isValidPassword(PhpidUsers $user, $password = '') {
		// Ambil stored hash
		$storedHash = $user->getPass();

		// Generate hash untuk dikomparasikan
		$hash = $this->passwordCrypt('sha512', $password, $storedHash);

		// Cek
		return ($hash && $storedHash == $hash);;
	}

	/**
	 * Ambil data user yang sedang login
	 *
	 * @param int $id User UID
	 * @return Parameter
	 */
	public function getUser($id = NULL) {
		return ModelBase::factory('User')->getUser($id);
	}

	/**
	 * Buat user
	 *
	 * @param string $username
	 * @param string $email
	 * @param string $password
	 * @return PhpidUsers 
	 */
	public function createUser($username, $email, $password) {
		// Generate password hash
		$hashedPassword = $this->hashPassword($password);
		
		return ModelBase::factory('User')->createUser($username, $email, $hashedPassword);
	}

	/**
	 * Update custom data user
	 *
	 * @param int $id User UID
	 * @param array $data Custom data
	 *
	 * @return bool 
	 */
	public function updateUserData($id = NULL, $data = array()) {
		// Silly
		if (empty($id)) return false;

		// Get user
		$user = ModelBase::ormFactory('PhpidUsersQuery')->findPK($id);

		if ($user) {
			// Get custom data
			$userData = new Parameter($user->toArray());
			$customData = $userData->get('Data');

			// @codeCoverageIgnoreStart
			if (empty($customData)) {
				// Straight forward
				$user->setData(serialize($data));
			} else {
				$userDataSerialized = fread($customData,10000);

				try {
					$currentUserData = unserialize($userDataSerialized);
					$currentUserData = array_merge($currentUserData, $data);
				} catch (\Exception $e) {
					$currentUserData = $data;
				}

				// Update custom data
				$user->setData(serialize($currentUserData));
			}
			// @codeCoverageIgnoreEnd
			
			$user->save();

			return true;
		} else {
			return false;
		}
	}

	/**
	 * Do confirmation
	 *
	 * @param string $token User token
	 * @return Parameter berisi 'success' dan 'data'
	 */
	public function confirm($token) {
		$result = new Parameter(array('success' => false, 'error' => NULL));
		$relatedUser = ModelBase::ormFactory('PhpidUsersQuery')->findOneByPass($token);

		if ($relatedUser) {
			// Set the status
			$relatedUser->setStatus(1);
			$relatedUser->save();
				
			$result->set('success', true);
			$result->set('data', $relatedUser->getUid());
		}

		return $result;
	}

	/**
	 * Send registration confirmation link
	 *
	 * @param int $uid User uid
	 */
	public function sendConfirmation($uid = 0) {
		$validUser = ModelBase::ormFactory('PhpidUsersQuery')->findPK($uid);

		// Only send to valid user
		if ($validUser) {
			$confirmationLink = '/auth/confirmation?token='.urlencode($validUser->getPass());

			$emailParameter = new Parameter(array(
				'toName' => $validUser->getName(),
				'toEmail' => $validUser->getMail(),
			));

			ModelBase::factory('Mailer', $emailParameter)->sendRegisterConfirmation($confirmationLink);
		}
	}

	/**
	 * Send reset password link
	 *
	 * @param string $email User email
	 * @return bool
	 */
	public function sendReset($email) {
		$validUser = ModelBase::ormFactory('PhpidUsersQuery')->findOneByMail($email);

		// Only send to valid user
		if ( ! $validUser) return false;

		$resetLink = '/auth/reset?token='.urlencode($validUser->getPass());

		$emailParameter = new Parameter(array(
			'toName' => $validUser->getName(),
			'toEmail' => $validUser->getMail(),
		));

		ModelBase::factory('Mailer', $emailParameter)->sendResetPassword($resetLink);

		return true;
	}

	/**
	 * Hash a user password
	 *
	 * @param string The plain-text password
	 */
	protected function hashPassword($password) {
		// Use the standard iteration count.
	    $countLog = self::HASH_COUNT;
		$hashedPassword = $this->passwordCrypt('sha512', $password, $this->generateSalt($countLog));

		return $hashedPassword;
	}

	/**
	 * Generates a random base 64-encoded salt prefixed with settings for the hash.
	 *
	 * Proper use of salts may defeat a number of attacks, including:
	 *  - The ability to try candidate passwords against multiple hashes at once.
	 *  - The ability to use pre-hashed lists of candidate passwords.
	 *  - The ability to determine whether two users have the same (or different)
	 *    password without actually having to guess one of the passwords.
	 *
	 * @param $countLog
	 *   Integer that determines the number of iterations used in the hashing
	 *   process. A larger value is more secure, but takes more time to complete.
	 *
	 * @return
	 *   A 12 character string containing the iteration count and a random salt.
	 */
	protected function generateSalt($countLog) {
		$output = '$S$';
		// We encode the final log iteration count in base 64.
		$itoa64 = self::ALNUM;
		$output .= $itoa64[$countLog];
		// 6 bytes is the standard salt for a portable phpass hash.
		$output .= $this->passwordBase64Encode($this->randomBytes(6), 6);

		return $output;
	}

	/**
	 * Generate random bytes
	 *
	 * @param int 
	 * @return string 
	 * @codeCoverageIgnore
	 */
	protected function randomBytes($count) {
		$bytes = '';
		$php_compatible = '';

		// Initialize on the first call. The contents of $_SERVER includes a mix of
		// user-specific and system information that varies a little with each page.
		$random_state = print_r($_SERVER, TRUE);

		if (function_exists('getmypid')) {
			// Further initialize with the somewhat random PHP process ID.
			$random_state .= getmypid();
			$bytes = '';
		}

		if (strlen($bytes) < $count) {
			// PHP versions prior 5.3.4 experienced openssl_random_pseudo_bytes()
			// locking on Windows and rendered it unusable.
			if (empty($php_compatible)) {
				$php_compatible = version_compare(PHP_VERSION, '5.3.4', '>=');
			}
			// /dev/urandom is available on many *nix systems and is considered the
			// best commonly available pseudo-random source.
			if ($fh = @fopen('/dev/urandom', 'rb')) {
				// PHP only performs buffered reads, so in reality it will always read
				// at least 4096 bytes. Thus, it costs nothing extra to read and store
				// that much so as to speed any additional invocations.
				$bytes .= fread($fh, max(4096, $count));
				fclose($fh);
			}
			// openssl_random_pseudo_bytes() will find entropy in a system-dependent
			// way.
			elseif ($php_compatible && function_exists('openssl_random_pseudo_bytes')) {
				$bytes .= openssl_random_pseudo_bytes($count - strlen($bytes));
			}

			// If /dev/urandom is not available or returns no bytes, this loop will
			// generate a good set of pseudo-random bytes on any system.
			// Note that it may be important that our $random_state is passed
			// through hash() prior to being rolled into $output, that the two hash()
			// invocations are different, and that the extra input into the first one -
			// the microtime() - is prepended rather than appended. This is to avoid
			// directly leaking $random_state via the $output stream, which could
			// allow for trivial prediction of further "random" numbers.
			while (strlen($bytes) < $count) {
				$random_state = hash('sha256', microtime() . mt_rand() . $random_state);
				$bytes .= hash('sha256', mt_rand() . $random_state, TRUE);
			}
		}
		$output = substr($bytes, 0, $count);
		$bytes = substr($bytes, $count);

		return $output;
	}

	/**
	 * Hash a password using a secure stretched hash.
	 *
	 * By using a salt and repeated hashing the password is "stretched". Its
	 * security is increased because it becomes much more computationally costly
	 * for an attacker to try to break the hash by brute-force computation of the
	 * hashes of a large number of plain-text words or strings to find a match.
	 *
	 * @param $algo The string name of a hashing algorithm usable by hash(), like 'sha256'.
	 * @param $password The plain-text password to hash.
	 * @param $setting Must be at least 12 characters (the settings and salt).
	 *
	 * @return
	 *   A string containing the hashed password (and salt) or FALSE on failure.
	 */
	protected function passwordCrypt($algo, $password, $setting) {
		// The first 12 characters of an existing hash are its setting string.
		$setting = substr($setting, 0, 12);

		if ($setting[0] != '$' || $setting[2] != '$') return FALSE;

		$countLog = $this->passwordGetCountLog($setting);

		// Hashes may be imported from elsewhere
		if ($countLog < self::MIN_HASH_COUNT || $countLog > self::MAX_HASH_COUNT) return FALSE;

		$salt = substr($setting, 4, 8);

		// Hashes must have an 8 character salt.
		if (strlen($salt) != 8) return FALSE;

		// Convert the base 2 logarithm into an integer.
		$count = 1 << $countLog;

		// We rely on the hash() function being available in PHP 5.2+.
		$hash = hash($algo, $salt . $password, TRUE);
		do {
			$hash = hash($algo, $hash . $password, TRUE);
		} while (--$count);

		$len = strlen($hash);
		$output =  $setting . $this->passwordBase64Encode($hash, $len);
		$expected = 12 + ceil((8 * $len) / 6);

		return (strlen($output) == $expected) ? substr($output, 0, self::HASH_LENGTH) : FALSE;
	}

	/**
	 * Parse the log2 iteration count from a stored hash or setting string.
	 */
	protected function passwordGetCountLog($setting) {
		$itoa64 = self::ALNUM;
		return strpos($itoa64, $setting[3]);
	}

	/**
	 * Encode bytes into printable base 64 using the *nix standard from crypt().
	 *
	 * @param $input    The string containing bytes to encode.
	 * @param $count    The number of characters (bytes) to encode.
	 *
	 * @return
	 *   Encoded string
	 */
	protected function passwordBase64Encode($input, $count) {
		$output = '';
		$i = 0;
		$itoa64 = self::ALNUM;

		do {
			$value = ord($input[$i++]);
			$output .= $itoa64[$value & 0x3f];
			
			if ($i < $count) $value |= ord($input[$i]) << 8;

			$output .= $itoa64[($value >> 6) & 0x3f];

			if ($i++ >= $count) break;

			if ($i < $count) $value |= ord($input[$i]) << 16;

			$output .= $itoa64[($value >> 12) & 0x3f];

			if ($i++ >= $count) break;

			$output .= $itoa64[($value >> 18) & 0x3f];
		} while ($i < $count);

		return $output;
	}
}
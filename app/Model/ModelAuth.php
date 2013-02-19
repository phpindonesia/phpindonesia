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

		if ($setting[0] != '$' || $setting[2] != '$') {
			return FALSE;
		}

		$countLog = $this->passwordGetCountLog($setting);

		// Hashes may be imported from elsewhere
		if ($countLog < self::MIN_HASH_COUNT || $countLog > self::MAX_HASH_COUNT) {
			return FALSE;
		}

		$salt = substr($setting, 4, 8);
		// Hashes must have an 8 character salt.
		if (strlen($salt) != 8) {
			return FALSE;
		}

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
			
			if ($i < $count) {
				$value |= ord($input[$i]) << 8;
			}

			$output .= $itoa64[($value >> 6) & 0x3f];

			if ($i++ >= $count) {
				break;
			}

			if ($i < $count) {
				$value |= ord($input[$i]) << 16;
			}

			$output .= $itoa64[($value >> 12) & 0x3f];

			if ($i++ >= $count) {
				break;
			}

			$output .= $itoa64[($value >> 18) & 0x3f];
		} while ($i < $count);

		return $output;
	}
}
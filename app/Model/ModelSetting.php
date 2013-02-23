<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Model;

use app\Parameter;

/**
 * ModelSetting
 *
 * @author PHP Indonesia Dev
 */
class ModelSetting extends ModelBase 
{
    /**
     * Handle info
     *
     * @param Parameter $data 
     *
     * @return Parameter $content
     */
    public function handleInfo(Parameter $data) {
        $content = new Parameter(array(
            'title' => 'Informasi',
        ));

        // Get user data and handle any POST data if exists
        $user = $data->get('user');
        $post = new Parameter($data->get('postData', array()));

        // @codeCoverageIgnoreStart
        if ($post->get('fullname') || $post->get('signature')) {
            $uid = $user->get('Uid');

            if ($post->get('fullname')) {
                // Update custom data
                $updated = ModelBase::factory('User')->updateUserData($uid, array('fullname' => $post->get('fullname')));

                if ( ! empty($updated)) {
                    $content->set('updated', true);
                    $user = $updated;
                }
            }

            if ($post->get('signature')) {
                // Update regular data
                $updated = ModelBase::factory('User')->updateUser($uid, array('signature' => $post->get('signature')));
                $user = empty($updated) ? $user : $updated;

                if ( ! empty($updated)) {
                    $content->set('updated', true);
                    $user = $updated;
                }
            }
        }
        // @codeCoverageIgnoreEnd

        $fullName = str_replace('-', '', $user->get('Fullname'));
        $signature = $user->get('Signature');

       
        // Build inputs
        $inputs = array(
            new Parameter(array(
                'type' => 'text',
                'size' => '4',
                'name' => 'fullname',
                'placeholder' => 'Nama lengkap',
                'value' => $fullName,
            )),
            new Parameter(array(
                'type' => 'textarea',
                'size' => '4',
                'name' => 'signature',
                'placeholder' => 'Tentang anda',
                'value' => $signature,
            )),
        );

        $content->set('inputs', $inputs);

        return $content;
    }

    /**
     * Handle email
     *
     * @param Parameter $data 
     *
     * @return Parameter $content
     */
    public function handleMail(Parameter $data) {
        $content = new Parameter(array(
            'title' => 'Akun Email',
        ));

        // Get user data and handle any POST data if exists
        $user = $data->get('user');
        $post = new Parameter($data->get('postData', array()));

        // @codeCoverageIgnoreStart
        if ($post->get('email')) {
            $email = filter_var($post->get('email'), FILTER_VALIDATE_EMAIL);

            if ($email) {
                $uid = $user->get('Uid');

                // Update regular data
                $updated = ModelBase::factory('User')->updateUser($uid, array('mail' => $post->get('email')));
                $user = empty($updated) ? $user : $updated;

                if ( ! empty($updated)) {
                    $content->set('updated', true);
                    $user = $updated;
                }
            } else {
                $content->set('error', 'Email tidak valid!');
            }
        }
        // @codeCoverageIgnoreEnd

        $email = $user->get('Mail');

       
        // Build inputs
        $inputs = array(
            new Parameter(array(
                'type' => 'text',
                'size' => '4',
                'name' => 'email',
                'placeholder' => 'Email yang dipakai akun ini',
                'value' => $email,
            )),
        );

        $content->set('inputs', $inputs);

        return $content;
    }

    /**
     * Handle password
     *
     * @param Parameter $data 
     *
     * @return Parameter $content
     */
    public function handlePassword(Parameter $data) {
        $content = new Parameter(array(
            'title' => 'Akun Password',
        ));

        // Get user data and handle any POST data if exists
        $user = $data->get('user');
        $post = new Parameter($data->get('postData', array()));

        // @codeCoverageIgnoreStart
        if ($post->get('password') || $post->get('cpassword')) {

            $password = $post->get('password');
            $cpassword = $post->get('cpassword');

            if (empty($password) || empty($cpassword)) {
                $content->set('error', 'Isi password dan konfirmasi password!');
            } elseif ($password != $cpassword) {
                $content->set('error', 'Password dan konfirmasi password tidak cocok!');
            } else {
                $uid = $user->get('Uid');

                // Update regular data
                $hashedPassword = ModelBase::factory('Auth')->hashPassword($password);
                $updated = ModelBase::factory('User')->updateUser($uid, array('pass' => $hashedPassword));
                $user = empty($updated) ? $user : $updated;

                if ( ! empty($updated)) {
                    $content->set('updated', true);
                    $user = $updated;
                }
            }
        }
        // @codeCoverageIgnoreEnd

        // Build inputs
        $inputs = array(
            new Parameter(array(
                'type' => 'password',
                'size' => '4',
                'name' => 'password',
                'placeholder' => 'Password baru',
                'value' => '',
            )),
            new Parameter(array(
                'type' => 'password',
                'size' => '4',
                'name' => 'cpassword',
                'placeholder' => 'Konfirmasi password baru',
                'value' => '',
            )),
        );

        $content->set('inputs', $inputs);

        return $content;
    }
}
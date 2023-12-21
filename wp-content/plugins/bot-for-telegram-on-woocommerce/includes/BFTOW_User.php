<?php
class BFTOW_User {
    public function __construct() {}

    public function bftow_create_user($tgUserId, $displayName, $fName, $lName) {

        $userId = username_exists($tgUserId);
        $is_exists = $userId;
        $userId = (!$userId) ? wp_create_user( $tgUserId, $tgUserId . '_pswrd' ) : $userId;

        if($userId) {
            $userdata = array(
                'ID'           => $userId,
                'display_name' => $displayName,
                'first_name' => $fName,
                'last_name' => $lName,
            );

            wp_update_user( $userdata );

            $client = !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'telegram';
            if(!empty($fName)) {
                update_user_meta($userId, 'billing_first_name', $fName);
            }
            if(!empty($lName)) {
                update_user_meta($userId, 'billing_last_name', $lName);
            }
            update_user_meta($userId, 'bftow_user_name', $fName);
            update_user_meta($userId, 'bftow_user_system_id', $userId);
            update_user_meta($userId, 'bftow_user_token', md5( $client . time() ) );

            do_action('bftow_update_user', $is_exists, $userId, $displayName);
        }
    }

    public function bftow_get_user_system_id ($tgId) {
        $user = get_user_by('login', $tgId);
        if($user)
        {
            return $user->ID;
        }

        return false;
    }

    public static function bftow_get_user_tg_chat_id ($user_id) {
        $user = get_user_by('id', $user_id);
        if($user)
        {
            return $user->user_login;
        }

        return false;
    }

    public function bftow_save_user_phone ($tgId, $phone) {
        $userId = $this->bftow_get_user_system_id($tgId);

        if($userId) {
            update_user_meta($userId, '_phone', $phone);
            update_user_meta($userId, 'billing_phone', $phone);
            return true;
        }

        return false;
    }

    public function bftow_save_user_location ($tgId, $location) {
        $userId = $this->bftow_get_user_system_id($tgId);

        if($userId) {
            update_user_meta($userId, '_location', $location);
            do_action('bftow_location_saved', $userId, $location);
            return true;
        }

        return false;
    }

    public function bftow_get_user_phone ( $tgId ) {
        $userId = $this->bftow_get_user_system_id($tgId);

        if($userId) {
            return get_user_meta($userId, '_phone', true);
        }
        return false;
    }

    public function bftow_get_user_location ( $tgId ) {
        $userId = $this->bftow_get_user_system_id($tgId);

        if($userId) {
            return get_user_meta($userId, '_location', true);
        }
        return false;
    }

    public function bftow_get_user_token ( $tgId ) {
        $userId = $this->bftow_get_user_system_id($tgId);

        if($userId) {
            return get_user_meta($userId, 'bftow_user_token', true);
        }
        return false;
    }

    public function bftow_reset_user_data($tgId)
    {
        $userId = $this->bftow_get_user_system_id($tgId);
        delete_user_meta($userId, '_phone', '');
        delete_user_meta($userId, '_location', '');
        delete_user_meta($userId, 'bftow_user_name', '');
        delete_user_meta($userId, 'bftow_user_system_id', '');
        delete_user_meta($userId, 'bftow_user_token', '');
    }
}
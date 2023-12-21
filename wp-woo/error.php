<?php
/**
 * File Name: Header Response
 * Version: 1.0
 * Description: This Class created for purpose response default PHP Header.
 * Author: Biz Solution Co., Ltd.
 * Author URI: https://bizsolution.com.kh
 */

if ( ! class_exists( 'Header_Response_Code' ) ) {
    Class Header_Response {
    
        /**
         * Constructor
         */
        public function __construct() {}

        /**
         * Code 200: Success
         */
        public static function success( $result = array() ) {
            header('WWW-Authenticate: Basic realm="Biz"');
            header('HTTP/1.0 200 Success');

            $response = array(
                'code' => 200,
                'message' => 'Success',
                'results' => $result
            );

            echo json_encode( $response );
            exit;
        }

        /**
         * Code 201: Created
         */
        public static function created( $result = array() ) {
            header('WWW-Authenticate: Basic realm="Biz"');
            header('HTTP/1.0 201 Created');

            $response = array(
                'code' => 201,
                'message' => 'Created',
                'results' => $result
            );

            echo json_encode( $response );
            exit;
        }

        /**
         * Code 400: Bad Request
         */
        public static function bad_request( $result = array() ) {
            header('WWW-Authenticate: Basic realm="Biz"');
            header('HTTP/1.0 400 Bad Request');

            $response = array(
                'code' => 400,
                'message' => 'Bad Request',
                'results' => $result
            );

            echo json_encode( $response );
            exit;
        }

        /**
         * Code 401: Unauthorized
         */
        public static function unauthorized( $result = array() ) {
            header('WWW-Authenticate: Basic realm="Biz"');
            header('HTTP/1.0 401 Unauthorized');

            $response = array(
                'code' => 401,
                'message' => 'Unauthorized',
                'results' => $result
            );

            echo json_encode( $response );
            exit;
        }

        /**
         * Code 404: Not Found
         */
        public static function not_found( $result = array() ) {
            header('WWW-Authenticate: Basic realm="Biz"');
            header('HTTP/1.0 404 Not Found');

            $response = array(
                'code' => 404,
                'message' => 'Not Found',
                'results' => $result
            );

            echo json_encode( $response );
            exit;
        }


        /**
         * Code 500: Internal Server Error
         */
        public static function inter_server_error( $result = array() ) {
            header('WWW-Authenticate: Basic realm="Biz"');
            header('HTTP/1.0 500 internal server error');

            $response = array(
                'code' => 500,
                'message' => 'Internal Server Error',
                'results' => $result
            );

            echo json_encode( $response );
            exit;
        }

        /**
         * Code 502: Internal Server Error
         */
        public static function bad_getway( $result = array() ) {
            header('WWW-Authenticate: Basic realm="Biz"');
            header('HTTP/1.0 502 Bad Gateway');

            $response = array(
                'code' => 502,
                'message' => 'Bad Gateway',
                'results' => $result
            );

            echo json_encode( $response );
            exit;
        }
    }
}
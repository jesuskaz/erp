<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    class Credential extends CI_Controller {

        public function signin() {
            $phone = $this->input->post('login');
            $password = $this->password($this->input->post('password'));

            $collection = [
                "phone" => $phone,
                "password" => $password,
            ];

            if($r = $this->db->get_where('user', $collection)->result_array())
            {
                $data['status'] = true;
                $data["data"] = $r; 
            }
            else
            {
                $data['status'] = false;
                $data["data"] = "Incorrect data";
            }

            echo json_encode($data);
        }

        function password($password, $_ = true)
        {
            return $this->encrypt_decrypt($_ ? 'encrypt' : 'decrypt', $password);
        }
        function encrypt_decrypt($action, $string) {
            $output = false;
            $encrypt_method = "AES-256-CBC";
            $secret_key = '1001';
            $secret_iv = '2002';
            $key = hash('sha256', $secret_key);
            $iv = substr(hash('sha256', $secret_iv), 0, 16);
            if ($action == 'encrypt') {
                $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
                $output = base64_encode($output);
            } else if ($action == 'decrypt') {
                $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
            }
            return $output;
        }
        public function signup()
        {
            $name = $this->input->post('name');
            $email = $this->input->post("email");
            $phone = $this->input->post('phone');
            $role = $this->input->post("role");
            $username = $this->input->post("username");
            $password = $this->password($this->input->post('password'));

            $collection = [
                'fullname' => $name,
                'email' => $email,
                'phone' => $phone,
                'role' => $role,
                'username' => $username,
                'password' => $password,
            ];

            if($this->db->get_where('user', ['username' => $username])->result_array()) {
                $data["status"] = false;
                $data["data"] = "This username is taken. Try another";
            }
            else
            {
                $this->db->insert('user', $collection);
                $data["status"] = true;
                $data["data"] = "Your account is successfully created.";
            }

            echo json_encode($data);
        }
    }
?>
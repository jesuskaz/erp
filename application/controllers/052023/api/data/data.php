<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    class Credential extends CI_Controller {
        public function add_event() {

            $title = $_POST['title'];
            $description = $_POST['description'];
            $day_id = $_POST['day_id'];
            $date = date('Y-m-d H:i:s');
            $image = str_replace(' ', '_', $_FILES['image']['name']);

            $path = "upload/event";

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $status = false;
            if(move_uploaded_file($_FILES["image"]["tmp_name"], $path.$image))
            {
                $status = true;
            }
            else
            {
                $re["status"] = false;
                $re["data"] = "Error image";
                echo json_encode($re);
            }
            
            $data = [
                "title" => $title,
                "decription" => $description,
                "image" => $images,
                "day_id" => $day_id,
                "date" => $date
            ];

            $insert = $this->db->insert("event", $data);
            $re = [];
            
            if($insert) {
                $re["status"] = true;
                $re["data"] = "Your event has been successfully created.";   
            }
            else {
                $re["status"] = false;
                $re["data"] = "Error when creating the event";
            }    
            echo json_encode($re);
        }

        public function get_course() {
            $course = $this->db->get('course')->result_array();
            $data["datas"] = $course;

            echo json_encode($data);
        }

        public function get_day() {
            $data["datas"] = $this->db->get("day")->result_array();

            echo json_encode($data);
        }

        public function get_timeTable() {
            $data["datas"] = $this->db->get("timetable")->result_array();

            echo json_encode($data);
        }

        public function add_timetable() {
            
            $date = $this->input->post("date");
            $insert = $this->db->insert("timetable", ["date" => $date]);

            $re = [];
            if($insert)
            {
                $re["status"] = true;
                $re["data"] = "succes";
            }
            else
            {
                $re["status"] = false;
                $re["data"] = "error";
            }
            echo json_encode($re);
        }

        public function get_option() {
            $data["datas"] = $this->db->get("option")->result_array();
            echo json_encode($data);
        }

        public function add_courseplan() {
            $hour = $this->input->post("hour");
            $day_id = $this->input->post("day_id");
            $course_id = $this->input->post("course_id");
            $timetable = $this->input->post("timetable_id");

            $data = [
                "hour" => $hour,
                "day_id" => $day_id,
                "course_id" => $course_id,
                "timetable" => $timetable,
            ];

            $insert = $this->db->insert("course_plan", $data);

            $re = []; 
            if($insert) {
                $re["status"] = true;
                $re["data"] = "success";
            }
            else
            {
                $re["status"] = false;
                $re["data"] = "error";
            }

            echo json_encode($re);
        }

        public function view_grade($idoption = null)
        {
            $re = [];
            if(!empty($idoption))
            {
                $this->db->join("option", "option.idoption = grade.idoption");
                $this->db->where("grade.idoption", $idoption);
            }
            $data = $this->db->get("grade")->result_array();
            if($data)
            {
                $re["status"] = true;
                $re["datas"] = $data;
            }
            else
            {
                $re["status"] = false;
            }

            echo json_encode($re);
        }
    }
?>
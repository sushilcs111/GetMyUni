<?php

if (!defined('BASEPATH'))
    exit('You do not have access');

class Client_User_Model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public $user_id = NULL;
    public $email = NULL;
    public $password = NULL;

    function create_user($data) {
            $user_following = json_encode(array());
             if ((session('profile_pic'))) {
            $profile_pic = json_encode(array(session('profile_pic')));
        } else {
            $profile_pic = "";
        }
        
        $table_array = array(
            'name' => $data['full_name'],
            'email' => $data['email'],
            'status' => 1,
            'user_type' => 'site_user',
            'interested_degree_id' => isset($data['int_degree']) ? $data['int_degree'] : '',
            'interested_field_id' => isset($data['int_field']) ? $data['int_field'] : '',
            'interested_domain_id' => $data['domain'],
            'country_id' => $data['country'],
            'preferred_destination' => $data['preferred_destination'],
            'profile_pic' => $profile_pic,
            'created_date' => get_date(),
            'user_following' => $user_following,
            'gender' => session('gender'),
            'company' => session('company'),
        );

        if ($data['profile_type'] == 'normal') {
            $table_array['password'] = md5($data['password']);
        } elseif ($data['profile_type'] == 'facebook') {
            
        } elseif ($data['profile_type'] == 'linkedin') {
            
        }
        $this->db->insert('user', $table_array);
        $max_id = $this->db->insert_id();
        // insert_new_user_notification($data['full_name'],$max_id);
        return $max_id;
       
    }

    function getUserData() {
        if ($this->user_id)
            $this->db->where('U_T.id', $this->user_id);

        if ($this->email && $this->password) {
            $this->db->where('U_T.email', $this->email);
            $this->db->where('U_T.password', md5($this->password));
        }

        if($this->email)
        {
            $this->db->where('U_T.email', $this->email);
        }
        $query = $this->db
                ->select('*')
                ->from('user as U_T')
                //-> where('U_T.id',$user_id)
                ->where('U_T.status', 1)
                ->get();
        return $query->row();
    }

    function getUserEduInfo() {
        if ($this->user_id)
            $this->db->where('U_E.student_id', $this->user_id);


        $query = $this->db
                ->select('
																							U_E.student_id,
																							U_E.college_id,
																							U_E.degree,
																							U_E.other,
                                                                                                                                                                                        U_E.field_study,
																											
																				')
                ->from('user_education_info as U_E')
                ->get();
        return $query->result_array();
    }

    function getUserTestInfo() {
        if ($this->user_id)
            $this->db->where('U_TE.user_id', $this->user_id);


        $query = $this->db
                ->select('
																							U_TE.user_id,
																							U_TE.test_name,
																							U_TE.test_score,
																						
																											
																				')
                ->from('user_test_info as U_TE')
                ->get();
        return $query->result_array();
    }

    function save_user($data) {
        

        if (isset($data['profile_pic'])) {
          
           
            $profile_pic = json_encode(($data['profile_pic']));
           
             
        } else {
            $profile_pic = "";
        }

        $user_array = array(
            'name' => $data['name'],
            'dob' => @$data['dob']!="" ? date('Y-m-d', strtotime($data['dob'])) :"",
            'gender' => $data['gender'],
            'profile_pic' => $profile_pic,
            'country_id' => $data['country_id'],
            'preferred_destination' => $data['preferred_destination'],
            'job_position_id' => @$data['job_position_id'],
            'company' => $data['company'],
            'industry_id' => $data['industry_id'],
            'functionality_id' => @$data['functionality_id'],
            'interested_degree_id' => $data['interested_degree_id'],
            'interested_field_id' => $data['interested_field_id'],
            'interested_domain_id' => $data['interested_domain_id'],
            'modified_date' => get_date()
        );
        
        if(isset($data['social_type']))
        {
            if($data['social_type'] == 'facebook')
            {
                $user_array['facebook'] = $data['public_profile_url'];
            }
            elseif($data['social_type'] == 'linkedin')
            {
                $user_array['linkedin'] = $data['public_profile_url'];
            }
            elseif($data['social_type'] == 'google_plus')
            {
                $user_array['google_plus'] = $data['public_profile_url'];
            }
            
        }
        
        //display($user_array);die;
        $this->db->where('id', $this->user_id);
        $this->db->update('user', $user_array);
        insert_new_user_notification($data['name'],$this->user_id);
        //      show_query();
        if (isset($data['schoolname']) && ($data['schoolname'] != 0) && (($data['schoolname']) != "")) {
            
            $this->db->delete('user_education_info', array('student_id' => $this->user_id));

            $count_name = count($data['schoolname']);

            for ($i = 0; $i <= $count_name - 1; $i++) {

                $school_name = explode("-", $data['schoolname'][$i]);

                $school_id = $school_name[0] ? $school_name[0] : '';
                $school_field = isset($school_name[1]) ? $school_name[1] : '';
                $school_degree = isset($school_name[2]) ? $school_name[2] : '';
                if (($school_field != 0) && ($school_degree != 0)) {
                    $education_info = array
                        (
                        'student_id' => $this->user_id,
                        'college_id' => $school_id,
                        'other' => $data['other_schoolname'][$i],
                        'degree' => $school_degree,
                        'field_study' => $school_field
                    );
                    (isset($school_name[1]) && isset($school_name[0]) && isset($school_name[2]) ) ? $this->db->insert('user_education_info', $education_info) : '';
                } else if ($school_name[0] != "") {

                    $education_info1 = array
                        (
                        'student_id' => $this->user_id,
                        'college_id' => 0,
                        'other' => $data['other_schoolname'][$i],
                        'degree' => $data['school_degree'][$i],
                        'field_study' => $data['school_field'][$i]
                    );
                    (isset($data['other_schoolname']) && isset($data['school_degree']) && ($data['school_degree'][$i] != "" ) && ($data['school_field'][$i] != "" )) ? $this->db->insert('user_education_info', $education_info1) : '';
                }
            }
        }
        
        if(isset($data['tsetscore'])){
            $this->db->delete('user_test_info', array('user_id' => $this->user_id));
            $count_test_name = count(($data['tsetscore']));
            //echo $count_test_name;
            for ($j = 0; $j <= $count_test_name - 1; $j++) {
                //echo $j;

                if (($data['test_name'][$j] != "") && ($data['tsetscore'][$j] > 0)) {
                    $test_info = array
                        (
                        'user_id' => $this->user_id,
                        'test_name' => $data['test_name'][$j],
                        'test_score' => $data['tsetscore'][$j]
                    );
                    (($data['test_name'][$j] != "") && ($data['tsetscore'][$j] != "" )) ? $this->db->insert('user_test_info', $test_info) : '';
                }
            }
        }
    }

    function get_user_save_school() {

        $query = $this->db
                ->select('U_I.id,
                                                                                           U_I.school_id,
                                                                                           U_I.student_id,
                                                                                           U_I.interest_yes_maybe,
                                                                                           U_I.program_id,
                                                                                           U_I.pro_yes_maybe,
                                                                                           C_I.school_name
                                                                                        ')
                ->from('user_interested_in_school as U_I')
                ->join('college_info as C_I', 'C_I.id = U_I.school_id')
                ->where('U_I.student_id', session('client_user_id'))
                ->where('U_I.following', 0)
                //-> group_by('U_I.school_id')
                ->get();

        return $query->result_array();
    }

    function get_user_following_school() {

        $query = $this->db
                ->select('U_I.id,
                                                                                           U_I.school_id,
                                                                                           U_I.student_id,
                                                                                           U_I.following,
                                                                                           C_I.school_name
                                                                                        ')
                ->from('user_interested_in_school as U_I')
                ->join('college_info as C_I', 'C_I.id = U_I.school_id')
                ->where('U_I.student_id', session('client_user_id'))
                ->where('U_I.following', 1)
                // ->group_by('U_I.school_id')
                ->get();
        return $query->result_array();
    }

    function get_school_follower() {

        $query = $this->db
                ->select('U_I.id,
                                                                                           U_I.school_id ,
                                                                                           U_I.student_id,
                                                                                           U_T.name
                                                                                        ')
                ->from('user_interested_in_school as U_I')
                ->join('user as U_T', 'U_I.student_id = U_T.id')
                ->where('U_I.student_id !=', session('client_user_id'))
                ->get();
        return $query->result_array();
    }

    function get_user_review() {

        $query = $this->db
                ->select('REVIEW.id,
                                                                                            REVIEW.college_id,
                                                                                            REVIEW.student_id,
                                                                                            C_I.school_name,
                                                                                        ')
                ->from('college_review_rating as  REVIEW')
                ->join('college_info as C_I', 'C_I.id = REVIEW.college_id')
                ->where('REVIEW.student_id ', session('client_user_id'))
                ->get();
        return $query->result_array();
    }

    function get_school_name() {
        $query = $this->db
                ->select('C_I.id,
				C_I.school_name,
				C_I.degree,
				M_D.degree_name,
				F_S.field_name,
				C_I.field_study')
                ->from('college_info as C_I')
                ->join('field_of_study as F_S', 'C_I.field_study = F_S.id')
                ->join('master_degree as M_D', 'C_I.degree = M_D.id')
                ->get();

        return $query->result_array();
    }

    function delete_record($data) {
        $info = explode("_", $data['id']);

        $type = $info[0];
        $id = $info[1];
        if ($type == "user-intrest") {
            $this->db->where('id', $id);
            $this->db->where('student_id', session('client_user_id'));
            $this->db->delete('user_interested_in_school');
            return true;
        }

        if ($type == "user-review") {
            $this->db->where('id', $id);
            $this->db->where('student_id', session('client_user_id'));
            $this->db->delete('college_review_rating');
            return true;
        }
       
    }
    
    function get_industry($industry = '')
    {
        if($industry)
            $this->db->where("name LIKE '%".$industry."%'"); 
        $query = $this->db->select('*')
                 -> from('industry')->get();
        $result = $query->row();
        return $result;
                
    } 
    
    
    
     function user_following_details($id)
    {
         
         $query = $this->db->select('user_following')
                 -> from('user')
                 ->where('id',$id)
                 ->get();
        return $query->result_array();
          
    } 
    
    
     function user_community($id)
    {
         
         $query = $this->db->select('members')
                 -> from('communities')
                 ->where('id',$id)
                 ->get();
        return $query->result_array();
          
    } 
    
     function user_community_details($id,$user_communi)
    {
         
        $data = array(
               'members' => $user_communi,
            
            );
           
            $this->db->where('id', $id);
            $this->db->update('communities', $data); 
    } 
    
    
     function save_following_details($id,$user_following)
    {
         
        $data = array(
               'user_following' => $user_following,
            
            );
           
            $this->db->where('id', session('client_user_id'));
            $this->db->update('user', $data); 
          
    } 
    
    
    function delete_user_save_school($id)
    {
        
        $this->db->where('id', $id);
        $this->db->delete('user_interested_in_school'); 
    }
    
    
     function select_user_community_detail($id)
    {
        
        $query = $this->db->select('id,cname')
                 -> from('communities')
                 ->like('members',$id)
                 ->get();
        return $query->result_array();
          
    }
    
    
      function select_user_following_u($id)
    {
        
        $query = $this->db->select('id,name')
                 -> from('user')
                 ->like('user_following',$id)
                 ->get();
        //show_query();
        return $query->result_array();
          
    }
    
    
    function user_followu($id)
    
    
    {
         
         $query = $this->db->select('user_following')
                 -> from('user')
                 ->where('id',$id)
                 ->get();
        return $query->result_array();
          
    } 

}

?>

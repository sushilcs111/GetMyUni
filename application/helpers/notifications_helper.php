<?php

function insert_discussion_notification() {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();

    $CI->load->model('client/client_forums_model', 'forum');
    $CI->load->model('client/client_notification_model', 'notification');
    $get_topic_details = $CI->forum->get_topic_details($post_params["id"]);



    $followers = json_decode($get_topic_details[0]["followers"], true);
    $message = "New Discussion has been initiated by " . get_user_name_id(session("client_user_id")) . " in '" . substr($get_topic_details[0]["topic"], 0, 25) . "'";
    $link = SITE_URL . "discussion/" . session("discussion_insert_id") . "/";
    $type = "Discussion Added";
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));
    foreach ($followers as $follow) {
        if ($follow != session("client_user_id")) {
            $CI->notification->ticker_insert(session("client_user_id"), $follow, addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
        }
    }
}

function insert_new_community_notification($cid, $uri) {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();


    $CI->load->model('client/client_notification_model', 'notification');



    $followers = get_all_user_ids();
    $message = "New community  '" . substr($post_params["cname"], 0, 25) . "' has been created on GMU ";
    $link = SITE_URL . "communities/$uri/$cid/";
    $type = "Community Added";
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));

    foreach ($followers as $follow) {
        $CI->notification->ticker_insert(session("client_user_id"), $follow["id"], addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
    }
}

function insert_new_event_notification() {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();

    $CI->load->model('client/client_communities_model', 'comm');
    $CI->load->model('client/client_notification_model', 'notification');
    $get_comm_details = $CI->comm->get_community_details($post_params["cid"]);


    $followers = json_decode($get_comm_details[0]["members"], true);
    $message = "New Events happening in '" . $get_comm_details[0]["cname"] . "'";
    $link = SITE_URL . "communities/" . urlclean($get_comm_details[0]["cname"]) . "/" . $get_comm_details[0]["id"] . "/";
    $type = "Event Added";
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));
    foreach ($followers as $follow) {
        if ($follow != session("client_user_id")) {
            $CI->notification->ticker_insert(session("client_user_id"), $follow, addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
        }
    }

    if ($get_comm_details[0]['added_by'] != session("client_user_id")) {
        $message = get_user_name_id(session("client_user_id")) . " has created an event '" . $_POST["ename"] . "' in your Community '" . $get_comm_details[0]['cname'] . "'.";
        $CI->notification->notification_insert(session("client_user_id"), $get_comm_details[0]['added_by'], addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
    }
}

function insert_comm_discussion_notification() {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();

    $CI->load->model('client/client_communities_model', 'comm');
    $CI->load->model('client/client_notification_model', 'notification');
    $get_comm_details = $CI->comm->get_community_details($post_params["community_id"]);



    $followers = json_decode($get_comm_details[0]["members"], true);
    $message = "New Discussion has been initiated by " . get_user_name_id(session("client_user_id")) . " in '" . substr($get_comm_details[0]["cname"], 0, 25) . "'";
    $link = SITE_URL . "communities/" . urlclean($get_comm_details[0]["cname"]) . "/" . $get_comm_details[0]["id"] . "/";
    $type = "Community Discussion Added";
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));
    foreach ($followers as $follow) {
        if ($follow != session("client_user_id")) {
            $CI->notification->ticker_insert(session("client_user_id"), $follow, addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
        }
    }
}

function insert_comm_user_join_notification() {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();

    $CI->load->model('client/client_communities_model', 'comm');
    $CI->load->model('client/client_notification_model', 'notification');
    $get_comm_details = $CI->comm->get_community_details($post_params["cid"]);



    $followers = json_decode($get_comm_details[0]["members"], true);
    $message = get_user_name_id(session("client_user_id")) . " Just Joined '" . substr($get_comm_details[0]["cname"], 0, 25) . "'";
    $link = SITE_URL . "profile/" . session("client_user_id") . "/";
    $type = "Community Member Added";
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));
    foreach ($followers as $follow) {
        if ($follow != session("client_user_id")) {
            $CI->notification->ticker_insert(session("client_user_id"), $follow, addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
        }
    }

    if ($get_comm_details[0]['added_by'] != session("client_user_id")) {
        $message = get_user_name_id(session("client_user_id")) . " has joined your Community '" . $get_comm_details[0]['cname'] . "'.";
        $CI->notification->notification_insert(session("client_user_id"), $get_comm_details[0]['added_by'], addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
    }
}

function insert_like_notification() {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();

    $CI->load->model('client/client_forums_model', 'forum');
    $CI->load->model('client/client_notification_model', 'notification');
    $get_topic_details = $CI->forum->get_discussion_details($post_params["id"]);



    $follow = $get_topic_details[0]["d_by"];
    $message = get_user_name_id(session("client_user_id")) . " has liked your Discussion";
    $link = SITE_URL . "discussion/" . $post_params["id"] . "/";
    $type = "Discussion Liked";
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));

    $CI->notification->notification_insert(session("client_user_id"), $follow, addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
}

function insert_comment_notification() {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();

    $CI->load->model('client/client_forums_model', 'forum');
    $CI->load->model('client/client_notification_model', 'notification');
    $get_topic_details = $CI->forum->get_discussion_details($post_params["id"]);



    $follow = $get_topic_details[0]["d_by"];
    $comments = json_decode($get_topic_details[0]["comments"], true);
    $message = get_user_name_id(session("client_user_id")) . " has commented on your Discussion";
    $link = SITE_URL . "discussion/" . $post_params["id"] . "/";
    $type = "Discussion Commented";
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));

    if ($follow != session('client_user_id')) {
        $CI->notification->notification_insert(session("client_user_id"), $follow, addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
    }
    $uids = array();
    foreach ($comments as $comment) {
        $message = get_user_name_id(session("client_user_id")) . " has commented on a Discussion that you've commented on";
        if ($comment["uid"] !== $follow && $comment["uid"] != session("client_user_id") && !in_array($comment["uid"], $uids)) {
            $CI->notification->notification_insert(session("client_user_id"), $comment["uid"], addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
            array_push($uids, $comment["uid"]);
        }
    }
}

function insert_school_like_notification() {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();

    $CI->load->model('client/client_forums_model', 'forum');
    $CI->load->model('client/client_notification_model', 'notification');
    $get_topic_details = $CI->forum->get_wall_discussion_details($post_params["id"]);



    $follow = $get_topic_details[0]["added_by"];
    $message = get_user_name_id(session("client_user_id")) . " has liked your Discussion";
    $link = SITE_URL . "college/" . clean_string($get_topic_details[0]['school_name']) . "/" . $post_params["sid"] . "/";
    $type = "School Discussion Liked";
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));

    $CI->notification->notification_insert(session("client_user_id"), $follow, addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
}

function insert_school_comment_notification() {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();

    $CI->load->model('client/client_forums_model', 'forum');
    $CI->load->model('client/client_notification_model', 'notification');
    $get_topic_details = $CI->forum->get_wall_discussion_details($post_params["id"]);



    $follow = $get_topic_details[0]["added_by"];
    $comments = json_decode($get_topic_details[0]["comments"], true);
    $message = get_user_name_id(session("client_user_id")) . " has commented on your Discussion";
    $link = SITE_URL . "college/" . clean_string($get_topic_details[0]['school_name']) . "/" . $post_params["sid"] . "/";
    $type = "School Discussion Commented";
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));

    if ($follow != session('client_user_id')) {
        $CI->notification->notification_insert(session("client_user_id"), $follow, addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
    }
    $uids = array();
    foreach ($comments as $comment) {
        $message = get_user_name_id(session("client_user_id")) . " has commented on a Discussion that you've commented on";
        if ($comment["uid"] !== $follow && $comment["uid"] != session("client_user_id") && !in_array($comment["uid"], $uids)) {
            $CI->notification->notification_insert(session("client_user_id"), $comment["uid"], addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
            array_push($uids, $comment["uid"]);
        }
    }
}

function insert_comm_like_notification() {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();

    $CI->load->model('client/client_communities_model', 'forum');
    $CI->load->model('client/client_notification_model', 'notification');
    $get_topic_details = $CI->forum->get_wall_discussion_details($post_params["id"]);



    $follow = $get_topic_details[0]["d_by"];
    $message = get_user_name_id(session("client_user_id")) . " has liked your Discussion";
    $link = SITE_URL . "communities/" . urlclean($get_topic_details[0]["cname"]) . "/" . $get_topic_details[0]["id"] . "/";
    $type = "Community Discussion Liked";
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));

    $CI->notification->notification_insert(session("client_user_id"), $follow, addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
}

function insert_comm_comment_notification() {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();

    $CI->load->model('client/client_communities_model', 'forum');
    $CI->load->model('client/client_notification_model', 'notification');
    $get_topic_details = $CI->forum->get_wall_discussion_details($post_params["id"]);



    $follow = $get_topic_details[0]["d_by"];
    $comments = json_decode($get_topic_details[0]["comments"], true);
    $message = get_user_name_id(session("client_user_id")) . " has commented on your Discussion";
    $link = SITE_URL . "communities/" . urlclean($get_topic_details[0]["cname"]) . "/" . $get_topic_details[0]["id"] . "/";
    $type = "Community Discussion Commented";
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));

    if ($follow != session('client_user_id')) {
        $CI->notification->notification_insert(session("client_user_id"), $follow, addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
    }
    $uids = array();
    foreach ($comments as $comment) {
        $message = get_user_name_id(session("client_user_id")) . " has commented on a Discussion that you've commented on";
        if ($comment["uid"] !== $follow && $comment["uid"] != session("client_user_id") && !in_array($comment["uid"], $uids)) {
            $CI->notification->notification_insert(session("client_user_id"), $comment["uid"], addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
            array_push($uids, $comment["uid"]);
        }
    }
}

function insert_comm_join_notification() {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();


    $CI->load->model('client/client_notification_model', 'notification');

    $id = $post_params["cid"];
    $emails = array_unique(explode(",", $post_params["sinput"]));
    $cname = $post_params["cname"];
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));
    $message = get_user_name_id(session("client_user_id")) . " has invited you to join the community '$cname'";
    $link = SITE_URL . "communities/" . urlclean($cname) . "/" . $id . "/";
    $type = "Community Join Invite";
    array_pop($emails);
    foreach ($emails as $email) {
        if ($email !== "" && $email !== " ") {
            $CI->notification->notification_insert(session("client_user_id"), get_user_id_email($email), addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
        }
    }
}

function insert_comm_event_join_notification() {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();


    $CI->load->model('client/client_notification_model', 'notification');

    $id = $post_params["cid"];
    $ename = $post_params["ename"];
    $emails = array_unique(explode(",", $post_params["sinput"]));
    $cname = $post_params["cname"];
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));
    $message = get_user_name_id(session("client_user_id")) . " has invited you to join an event '$ename' in the community '$cname'";
    $link = SITE_URL . "communities/" . urlclean($cname) . "/" . $id . "/";
    $type = "Community Event Join Invite";
    array_pop($emails);
    foreach ($emails as $email) {
        if ($email !== "" && $email !== " ") {
            $CI->notification->notification_insert(session("client_user_id"), get_user_id_email($email), addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
        }
    }
}

function get_all_user_ids() {
    $CI = & get_instance();
    $get_users = $CI->db->query("select distinct id from user where id !=" . session("client_user_id"));
    return $get_users->result_array();
}

function get_user_id_email($email) {
    $CI = & get_instance();
    $email = trim($email);
    $get_user = $CI->db->query("select id from user where email='$email'");
    $id = $get_user->row_array();
    return $id['id'];
}

function get_user_name_id($id) {
    $CI = & get_instance();
    $get_user = $CI->db->query("select name from user where id='$id'");
    $name = $get_user->result_array();
    return $name[0]['name'];
}

function get_forum_name_id($id) {
    $CI = & get_instance();
    $get_user = $CI->db->query("select fname from master_forums where id='$id'");
    $name = $get_user->result_array();
    return $name[0]['fname'];
}


function get_school_name($id) {
    $CI = & get_instance();
    $get_user = $CI->db->query("select school_name from college_info where id='$id'");
    $name = $get_user->result_array();
    return $name[0]['school_name'];
}

function get_ticker_notifications() {
    $CI = & get_instance();
    $CI->load->model('client/client_notification_model', 'notification');
    $get_ticker = $CI->notification->get_ticker_notifications(session("client_user_id"));
    return $get_ticker;
}

function get_unread_notification_count(){
    $CI = & get_instance();
    $CI->load->model('client/client_notification_model', 'notification');
    $count = $CI->notification->get_unread_notification_count(session("client_user_id"));
    return $count;
}

function insert_user_follow_notification() {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();

    $CI->load->model('client/client_notification_model', 'notification');
 
    $follow = $post_params["student_id"];
    $message = get_user_name_id(session("client_user_id")) . " has started following you";
    $link = SITE_URL . "client/client_user/user_show_profile/" . session("client_user_id");
    $type = "User Follow";
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));

    $CI->notification->notification_insert(session("client_user_id"), $follow, addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
}



function insert_user_follow_school() {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();

    $CI->load->model('client/client_notification_model', 'notification');
 
    $follow = $post_params["student_id"];
    $message = get_user_name_id(session("client_user_id")) . " has started following you";
    $link = SITE_URL . "client/client_user/user_show_profile/" . session("client_user_id");
    $type = "User Follow";
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));

    $CI->notification->notification_insert(session("client_user_id"), $follow, addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
}

function insert_university_notification($school_name,$id) {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();

    $CI->load->model('client/client_notification_model', 'notification');
    

    $message = "$school_name is now on GMU";
    $link = SITE_URL . "college/".clean_string($school_name)."/$id";
    $type = "School Added";
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));
    $CI->notification->ticker_insert(0,0, addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));

}


function insert_university_event_notification($id) {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();

    $CI->load->model('client/client_notification_model', 'notification');
    

    $message = "New Events happening in ".(get_school_name($id));
    $link = SITE_URL . "college/".clean_string(get_school_name($id))."/$id";
    $type = "Event added in school";
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));
    $CI->notification->ticker_insert(0,0, addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));

}


function insert_university_update_notification($id) {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();

    $CI->load->model('client/client_notification_model', 'notification');
    

    $message = "Check what's happening in ".(get_school_name($id));
    $link = SITE_URL . "college/".clean_string(get_school_name($id))."/$id";
    $type = "Update to school page";
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));
    $CI->notification->ticker_insert(0,0, addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));

}




function insert_new_user_notification($name,$id) {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();

    $CI->load->model('client/client_notification_model', 'notification');
    

    $message = "$name joined GMU";
    $link = SITE_URL . "client/client_user/user_show_profile/$id";
    
    $type = "User Added";
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));
    $CI->notification->ticker_insert(0,0, addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));

}


function insert_user_review_notification($university_name,$university_id,$id) {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();
     $CI->load->model('client/client_user_model', 'user');
    $CI->load->model('client/client_notification_model', 'notification');
    $get_follow_details = $CI->user->user_followu(session("client_user_id"));
    $CI->load->model('client/client_follow_school_model', 'school');

    $foloow_school = $CI->school->user_following_school($id);
   
    $user_id = array();
    if(!empty($foloow_school))
    {
        
        foreach($foloow_school as $username) {
        $user_id[] = $username['student_id'];
      }
    }
    $followers1 = json_decode($get_follow_details[0]["user_following"], true);
    
    $followers  = array_unique(array_merge(($user_id),($followers1)));
    $message = get_user_name_id(session("client_user_id")) . "  just wrote a review for".get_school_name($university_id);
    $link = SITE_URL . "client/client_review_rating/review_full_details_view/$id";
   
    $type = "Review Added";
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));
    foreach ($followers as $follow) {
        if ($follow != session("client_user_id")) {
            $CI->notification->ticker_insert(session("client_user_id"), $follow, addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
        }
    }
}



function insert_user_follow_user($user_name) {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();

    $CI->load->model('client/client_user_model', 'user');
    $CI->load->model('client/client_notification_model', 'notification');
    $get_follow_details = $CI->user->user_followu(session("client_user_id"));
   

    $followers = json_decode($get_follow_details[0]["user_following"], true);
    
    $message = get_user_name_id(session("client_user_id"))." started following ".get_user_name_id($user_name);
    $link = SITE_URL . "client/client_user/user_show_profile/$user_name";
    $type = "Users followed user";
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));
    foreach ($followers as $follow) {
        if ($follow != session("client_user_id")) {
            $CI->notification->ticker_insert(session("client_user_id"), $follow, addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
        }
    }
}

function insert_follow_school($id) {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();

    $CI->load->model('client/client_user_model', 'user');
    $CI->load->model('client/client_notification_model', 'notification');
    $get_follow_details = $CI->user->user_followu(session("client_user_id"));
    $CI->load->model('client/client_follow_school_model', 'school');

    $foloow_school = $CI->school->user_following_school($id);
    $user_id = array();
    if(!empty($foloow_school))
    {
        
        foreach($foloow_school as $username) {
        $user_id[] = $username['student_id'];
      }
    }
    $followers1 = json_decode($get_follow_details[0]["user_following"], true);
    //display($followers1);
    $followers  = array_unique(array_merge(($user_id),($followers1)));
   //display($followers);
    $message = get_user_name_id(session("client_user_id"))." Started Following ".get_school_name($id);
    $link = SITE_URL . "college/".clean_string(get_school_name($id))."/$id";
    $type = "Users followed school";
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));
    foreach ($followers as $follow) {
        if ($follow != session("client_user_id")) {
            $CI->notification->ticker_insert(session("client_user_id"), $follow, addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
        }
    }
}



function insert_user_follow_community() {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();

     $CI->load->model('client/client_communities_model', 'comm');
 
    $CI->load->model('client/client_user_model', 'user');
    $CI->load->model('client/client_notification_model', 'notification');
    $get_follow_details = $CI->user->user_followu(session("client_user_id"));
    $get_comm_details = $CI->comm->get_community_details($post_params["cid"]);
   

    $followers = json_decode($get_follow_details[0]["user_following"], true);
    
    $message = get_user_name_id(session("client_user_id"))."  joined a community  ".substr($get_comm_details[0]["cname"], 0, 25) . "'";
    $link = SITE_URL . "client/client_user/user_show_profile/$user_name";
    $type = "Users followed community";
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));
    foreach ($followers as $follow) {
        if ($follow != session("client_user_id")) {
            $CI->notification->ticker_insert(session("client_user_id"), $follow, addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
        }
    }
}


function insert_user_discussion_notification() {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();

     $CI->load->model('client/client_user_model', 'user');
    $CI->load->model('client/client_forums_model', 'forum');
    $CI->load->model('client/client_notification_model', 'notification');
    $get_topic_details = $CI->forum->get_topic_details($post_params["id"]);
     $get_follow_details = $CI->user->user_followu(session("client_user_id"));
   

    $followers = json_decode($get_follow_details[0]["user_following"], true);
    


    //$followers =  $CI->user->user_followu(session("client_user_id"));
    $message = "New Discussion has been initiated by " . get_user_name_id(session("client_user_id")) . " in '" . substr($get_topic_details[0]["topic"], 0, 25) . "'";
    $link = SITE_URL . "discussion/" . session("discussion_insert_id") . "/";
    $type = "Followee Discussion Added";
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));
    foreach ($followers as $follow) {
        if ($follow != session("client_user_id")) {
            $CI->notification->ticker_insert(session("client_user_id"), $follow, addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
        }
    }
}


function insert_user_newtopic_notification($id) {
    $CI = & get_instance();
    $class_name = $CI->router->fetch_class();
    $function_name = $CI->router->fetch_method();
    $uri_params = $CI->uri->segment_array();
    $get_params = $CI->input->get();
    $post_params = $CI->input->post();

     $CI->load->model('client/client_user_model', 'user');
    $CI->load->model('client/client_forums_model', 'forum');
    $CI->load->model('client/client_notification_model', 'notification');
    //$get_topic_details = $CI->forum->get_topic_details($post_params["id"]);
     $get_follow_details = $CI->user->user_followu(session("client_user_id"));
   

    $followers = json_decode($get_follow_details[0]["user_following"], true);
    


    //$followers =  $CI->user->user_followu(session("client_user_id"));
    $message = get_user_name_id(session("client_user_id"))."  started a new Topic in  ".get_forum_name_id($id);
    $link = SITE_URL . "discussion/" . session("discussion_insert_id") . "/";
    $type = "Followee New Topic Added";
    $params = json_encode(array($class_name, $function_name, $get_params, $post_params, $uri_params));
    foreach ($followers as $follow) {
        if ($follow != session("client_user_id")) {
            $CI->notification->ticker_insert(session("client_user_id"), $follow, addslashes($message), $link, $type, $params, date("Y-m-d H:i:s"));
        }
    }
}

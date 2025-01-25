<?php
require_once 'config.php';
class db_class extends db_connect
{

	public function __construct()
	{
		$this->connect();
	}

	public function getConnection()
	{
		return $this->conn;
	}

	public function check_username_exists($username)
	{
		$stmt = $this->conn->prepare("SELECT COUNT(*) AS count FROM user WHERE username = ?");
		$stmt->bind_param("s", $username);
		$stmt->execute();

		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$stmt->close();

		return $row['count'] > 0;
	}

	public function check_username_exists_except_current($username, $user_id)
	{
		$stmt = $this->conn->prepare("SELECT COUNT(*) AS count FROM user WHERE username = ? AND user_id != ?");
		$stmt->bind_param("si", $username, $user_id);
		$stmt->execute();

		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$stmt->close();

		return $row['count'] > 0;
	}

	public function add_user($username, $password, $firstname, $lastname, $acc_type)
	{
		$query = $this->conn->prepare("INSERT INTO `user` (`username`, `password`, `firstname`, `lastname`, `acc_type`) VALUES(?, ?, ?, ?, ?)") or die($this->conn->error);
		$query->bind_param("sssss", $username, $password, $firstname, $lastname, $acc_type);

		if ($query->execute()) {
			$query->close();
			$this->conn->close();
			return true;
		}
	}

	public function update_user($user_id, $username, $password, $firstname, $lastname, $acc_type, $newUserPic)
	{
		$query = $this->conn->prepare("UPDATE `user` SET `username`=?, `password`=?, `firstname`=?, `lastname`=?, `acc_type`=?, `user_pic`=? WHERE `user_id`=?") or die($this->conn->error);
		$query->bind_param("ssssssi", $username, $password, $firstname, $lastname, $acc_type, $newUserPic, $user_id);

		if ($query->execute()) {
			$query->close();
			$this->conn->close();
			return true;
		}
	}

	public function update_current_user($user_id, $username, $password, $firstname, $lastname, $acc_type, $newUserPic)
	{
		$query = $this->conn->prepare("UPDATE `user` SET `username`=?, `password`=?, `firstname`=?, `lastname`=?, `acc_type`=?, `user_pic`=? WHERE `user_id`=?") or die($this->conn->error);
		$query->bind_param("ssssssi", $username, $password, $firstname, $lastname, $acc_type, $newUserPic, $user_id);

		if ($query->execute()) {
			$query->close();
			$this->conn->close();
			return true;
		}
	}

	public function login($username, $password)
	{
		$query = $this->conn->prepare("SELECT * FROM `user` WHERE `username`='$username' && `password`='$password'") or die($this->conn->error);
		if ($query->execute()) {

			$result = $query->get_result();

			$valid = $result->num_rows;

			$fetch = $result->fetch_array();

			return array(
				'user_id' => isset($fetch['user_id']) ? $fetch['user_id'] : 0,
				'count' => isset($valid) ? $valid : 0
			);
		}
	}

	public function user_acc($user_id)
	{
		$query = $this->conn->prepare("SELECT * FROM `user` WHERE `user_id`='$user_id'") or die($this->conn->error);
		if ($query->execute()) {
			$result = $query->get_result();

			$valid = $result->num_rows;

			$fetch = $result->fetch_array();

			return $fetch['firstname'] . " " . $fetch['lastname'];
		}
	}

	public function user_account($user_id)
	{
		$query = $this->conn->prepare("SELECT * FROM `user` WHERE `user_id` = ?") or die($this->conn->error);
		$query->bind_param('i', $user_id);
		if ($query->execute()) {
			$result = $query->get_result();
			if ($result->num_rows > 0) {
				return $result->fetch_assoc();
			}
		}
		return null;
	}

	function hide_pass($str)
	{
		$len = strlen($str);

		return str_repeat('*', $len);
	}

	public function display_user()
	{
		$query = $this->conn->prepare("SELECT * FROM `user`") or die($this->conn->error);
		if ($query->execute()) {
			$result = $query->get_result();
			return $result;
		}
	}

	public function delete_user($user_id)
	{
		$query = $this->conn->prepare("DELETE FROM `user` WHERE `user_id` = '$user_id'") or die($this->conn->error);
		if ($query->execute()) {
			$query->close();
			$this->conn->close();
			return true;
		}
	}

	public function save_ltype($ltype_name, $ltype_desc)
	{
		$query = $this->conn->prepare("INSERT INTO `project_type` (`ltype_name`, `ltype_desc`) VALUES(?, ?)") or die($this->conn->error);
		$query->bind_param("ss", $ltype_name, $ltype_desc);

		if ($query->execute()) {
			$query->close();
			$this->conn->close();
			return true;
		}
	}

	public function display_ltype()
	{
		$query = $this->conn->prepare("SELECT * FROM `project_type`") or die($this->conn->error);
		if ($query->execute()) {
			$result = $query->get_result();
			return $result;
		}
	}

	public function delete_ltype($ltype_id)
	{
		$query = $this->conn->prepare("DELETE FROM `project_type` WHERE `ltype_id` = '$ltype_id'") or die($this->conn->error);
		if ($query->execute()) {
			$query->close();
			$this->conn->close();
			return true;
		}
	}

	public function update_ltype($ltype_id, $ltype_name, $ltype_desc)
	{
		$query = $this->conn->prepare("UPDATE `project_type` SET `ltype_name`=?, `ltype_desc`=? WHERE `ltype_id`=?") or die($this->conn->error);
		$query->bind_param("ssi", $ltype_name, $ltype_desc, $ltype_id);

		if ($query->execute()) {
			$query->close();
			$this->conn->close();
			return true;
		}
	}

	public function save_lplan($lplan_month)
	{
		$query = $this->conn->prepare("INSERT INTO `project_plan` (`lplan_month`) VALUES(?)") or die($this->conn->error);
		$query->bind_param("s", $lplan_month);

		if ($query->execute()) {
			$query->close();
			$this->conn->close();
			return true;
		}
	}

	public function display_lplan()
	{
		$query = $this->conn->prepare("SELECT * FROM `project_plan` ORDER BY lplan_month ASC") or die($this->conn->error);
		if ($query->execute()) {
			$result = $query->get_result();
			return $result;
		}
	}
	public function delete_lplan($lplan_id)
	{
		$query = $this->conn->prepare("DELETE FROM `project_plan` WHERE `lplan_id` = '$lplan_id'") or die($this->conn->error);
		if ($query->execute()) {
			$query->close();
			$this->conn->close();
			return true;
		}
	}

	public function update_lplan($lplan_id, $lplan_month)
	{
		$query = $this->conn->prepare("UPDATE `project_plan` SET `lplan_month`=? WHERE `lplan_id`=?") or die($this->conn->error);
		$query->bind_param("ii", $lplan_month, $lplan_id);

		if ($query->execute()) {
			$query->close();
			$this->conn->close();
			return true;
		}
	}

	public function save_beneficiary($firmname, $firstname, $middlename, $lastname, $tin, $contact_no, $tel_no, $address, $email, $province, $sector, $category)
	{
		$query = $this->conn->prepare("INSERT INTO `beneficiary` (`firmname`, `firstname`, `middlename`, `lastname`, `tin`, `contact_no`, `tel_no`, `address`, `email`, `province`, `sector`, `category`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")
			or die($this->conn->error);

		$query->bind_param("ssssssssssss", $firmname, $firstname, $middlename, $lastname, $tin, $contact_no, $tel_no, $address, $email, $province, $sector, $category);

		if ($query->execute()) {
			$query->close();
			$this->conn->close();
			return true;
		}
	}

	public function display_beneficiary()
	{
		$query = $this->conn->prepare("SELECT * FROM `beneficiary`") or die($this->conn->error);
		if ($query->execute()) {
			$result = $query->get_result();
			return $result;
		}
	}

	public function delete_beneficiary($beneficiary_id)
	{
		$query = $this->conn->prepare("DELETE FROM `beneficiary` WHERE `beneficiary_id` = '$beneficiary_id'") or die($this->conn->error);
		if ($query->execute()) {
			$query->close();
			$this->conn->close();
			return true;
		}
	}

	public function update_beneficiary($beneficiary_id, $firmname, $firstname, $middlename, $lastname, $tin, $contact_no, $tel_no, $address, $email, $province, $sector, $category)
	{
		$query = $this->conn->prepare("UPDATE `beneficiary` SET `firmname`=?, `firstname`=?, `middlename`=?, `lastname`=?, `tin`=?, `contact_no`=?, `tel_no`=?, `address`=?, `email`=?, `province`=?, `sector`=?, `category`=? WHERE `beneficiary_id`=?")
			or die($this->conn->error);

		$query->bind_param("ssssssssssssi", $firmname, $firstname, $middlename, $lastname, $tin, $contact_no, $tel_no, $address, $email, $province, $sector, $category, $beneficiary_id);

		if ($query->execute()) {
			$query->close();
			$this->conn->close();
			return true;
		}
	}

	public function save_project($spin_no, $beneficiary, $ltype, $lplan, $project_amount, $purpose, $title, $date_created)
	{

		$query = $this->conn->prepare("INSERT INTO `project` (`spin_no`, `ltype_id`, `beneficiary_id`, `purpose`, `amount`, `lplan_id`, `title`, `date_created`) VALUES(?, ?, ?, ?, ?, ?, ?, ?)") or die($this->conn->error);
		$query->bind_param("siisiiss", $spin_no, $ltype, $beneficiary, $purpose, $project_amount, $lplan, $title, $date_created);

		if ($query->execute()) {
			$query->close();
			$this->conn->close();
			return true;
		}
		return false;
	}

	public function display_project()
	{
		$query = $this->conn->prepare("SELECT * FROM `project`
			INNER JOIN `beneficiary` ON project.beneficiary_id = beneficiary.beneficiary_id
			INNER JOIN `project_type` ON project.ltype_id = project_type.ltype_id
			INNER JOIN `project_plan` ON project.lplan_id = project_plan.lplan_id ORDER BY project.project_id ASC")
			or die($this->conn->error);

		if ($query->execute()) {
			$result = $query->get_result();
			return $result;
		}
	}

	public function display_project_request()
	{
		$query = $this->conn->prepare("SELECT * FROM `project`
    INNER JOIN `beneficiary` ON project.beneficiary_id = beneficiary.beneficiary_id
    INNER JOIN `project_type` ON project.ltype_id = project_type.ltype_id
    INNER JOIN `project_plan` ON project.lplan_id = project_plan.lplan_id
    WHERE project.status = 0
    ORDER BY project.project_id ASC") or die($this->conn->error);
		if ($query->execute()) {
			$result = $query->get_result();
			return $result;
		}
	}

	public function display_project_confirmed()
	{
		$query = $this->conn->prepare("SELECT * FROM `project`
    INNER JOIN `beneficiary` ON project.beneficiary_id = beneficiary.beneficiary_id
    INNER JOIN `project_type` ON project.ltype_id = project_type.ltype_id
    INNER JOIN `project_plan` ON project.lplan_id = project_plan.lplan_id
    WHERE project.status = 1
    ORDER BY project.project_id ASC") or die($this->conn->error);
		if ($query->execute()) {
			$result = $query->get_result();
			return $result;
		}
	}

	public function display_project_released()
	{
		$query = $this->conn->prepare("SELECT * FROM `project`
    INNER JOIN `beneficiary` ON project.beneficiary_id = beneficiary.beneficiary_id
    INNER JOIN `project_type` ON project.ltype_id = project_type.ltype_id
    INNER JOIN `project_plan` ON project.lplan_id = project_plan.lplan_id
    WHERE project.status = 2
    ORDER BY project.project_id ASC") or die($this->conn->error);
		if ($query->execute()) {
			$result = $query->get_result();
			return $result;
		}
	}

	public function display_project_completed()
	{
		$query = $this->conn->prepare("SELECT * FROM `project`
    INNER JOIN `beneficiary` ON project.beneficiary_id = beneficiary.beneficiary_id
    INNER JOIN `project_type` ON project.ltype_id = project_type.ltype_id
    INNER JOIN `project_plan` ON project.lplan_id = project_plan.lplan_id
    WHERE project.status = 3
    ORDER BY project.project_id ASC") or die($this->conn->error);
		if ($query->execute()) {
			$result = $query->get_result();
			return $result;
		}
	}

	public function display_project_denied()
	{
		$query = $this->conn->prepare("SELECT * FROM `project`
    INNER JOIN `beneficiary` ON project.beneficiary_id = beneficiary.beneficiary_id
    INNER JOIN `project_type` ON project.ltype_id = project_type.ltype_id
    INNER JOIN `project_plan` ON project.lplan_id = project_plan.lplan_id
    WHERE project.status = 4
    ORDER BY project.project_id ASC") or die($this->conn->error);
		if ($query->execute()) {
			$result = $query->get_result();
			return $result;
		}
	}

	public function display_project_checked()
	{
		$query = $this->conn->prepare("SELECT * FROM `project`
    INNER JOIN `beneficiary` ON project.beneficiary_id = beneficiary.beneficiary_id
    INNER JOIN `project_type` ON project.ltype_id = project_type.ltype_id
    INNER JOIN `project_plan` ON project.lplan_id = project_plan.lplan_id
    WHERE project.status = 5
    ORDER BY project.project_id ASC") or die($this->conn->error);
		if ($query->execute()) {
			$result = $query->get_result();
			return $result;
		}
	}

	public function delete_project($project_id)
	{
		$query = $this->conn->prepare("DELETE FROM `project` WHERE `project_id` = '$project_id'") or die($this->conn->error);
		if ($query->execute()) {
			$query->close();
			$this->conn->close();
			return true;
		}
	}

	public function update_project($project_id, $beneficiary, $ltype, $lplan, $project_amount, $purpose, $status, $date_released, $title, $spin_no)
	{
		$query = $this->conn->prepare("UPDATE `project` SET `ltype_id`=?, `beneficiary_id`=?, `purpose`=?, `amount`=?, `lplan_id`=?, `status`=?, `date_released`=?, `title`=?, `spin_no`=? WHERE `project_id`=?") or die($this->conn->error);
		$query->bind_param("iisdiisssi", $ltype, $beneficiary, $purpose, $project_amount, $lplan, $status, $date_released, $title, $spin_no, $project_id);

		if ($query->execute()) {
			$query->close();
			$this->conn->close();
			return true;
		}
	}

	public function check_project($project_id)
	{
		$query = $this->conn->prepare("SELECT * FROM `project` WHERE `project_id`='$project_id'") or die($this->conn->error);
		if ($query->execute()) {
			$result = $query->get_result();
			return $result;
		}
	}

	public function check_lplan($lplan)
	{
		$query = $this->conn->prepare("SELECT * FROM `project_plan` WHERE `lplan_id`='$lplan'") or die($this->conn->error);
		if ($query->execute()) {
			$result = $query->get_result();
			return $result;
		}
	}

	public function save_date_sched($project_id, $date_schedule)
	{
		$query = $this->conn->prepare("INSERT INTO `project_schedule` (`project_id`, `due_date`) VALUES(?, ?)") or die($this->conn->error);
		$query->bind_param("is", $project_id, $date_schedule);

		if ($query->execute()) {
			return true;
		}
	}

	public function display_refund()
	{
		$query = $this->conn->prepare("SELECT * FROM `refund`") or die($this->conn->error);
		if ($query->execute()) {
			$result = $query->get_result();
			return $result;
		}
	}

	public function save_refund($project_id, $payee, $refund, $or_number, $project_schedule)
	{
		$query = $this->conn->prepare("INSERT INTO `refund` (`project_id`, `payee`, `pay_amount` , `or_number`, `project_schedule`) VALUES(?, ?, ?, ?, ?)")
			or die($this->conn->error);
		$query->bind_param("isssi", $project_id, $payee, $refund, $or_number, $project_schedule);

		if ($query->execute()) {
			$query->close();
			return true;
		} else {
			return false;
		}
	}

	public function count_projects_by_status($status)
	{
		$query = "SELECT COUNT(*) AS count FROM project WHERE status = ?";
		$stmt = $this->conn->prepare($query);

		$stmt->bind_param("s", $status);

		$stmt->execute();
		$result = $stmt->get_result()->fetch_assoc();
		$stmt->close();

		return $result['count'];
	}

	public function generateORNumber($project_id)
	{

		$query = "SELECT due_date 
              FROM project_schedule 
              WHERE project_id = ? AND status = 'Unpaid' 
              ORDER BY due_date ASC 
              LIMIT 1";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i", $project_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();

		if (!$row) {

			return null;
		}

		$due_date = $row['due_date'];

		$date_part = date('n.j.y', strtotime($due_date));

		$new_number = rand(1000000, 9999999);

		$or_number = $new_number . '-' . $date_part;

		while (true) {
			$query = "SELECT COUNT(*) as count FROM refund WHERE or_number = ?";
			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("s", $or_number);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();

			if ($row['count'] == 0) {
				break;
			}

			$new_number = rand(1000000, 9999999);
			$or_number = $new_number . '-' . $date_part;
		}

		return $or_number;
	}

	public function get_attachments($project_id)
	{
		$query = $this->conn->prepare("SELECT * FROM attachments WHERE project_id = ?");
		$query->bind_param("i", $project_id);
		$query->execute();
		$result = $query->get_result();
		return $result->fetch_all(MYSQLI_ASSOC);
	}

	public function save_attachment($project_id, $file_name, $file_type)
	{
		$query = "INSERT INTO attachments (project_id, filename, file_type) VALUES (?, ?, ?)";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iss", $project_id, $file_name, $file_type);
		$stmt->execute();
		$stmt->close();
	}

	public function get_attachment_by_id($attachment_id)
	{
		$query = "SELECT * FROM attachments WHERE id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i", $attachment_id);
		$stmt->execute();
		return $stmt->get_result()->fetch_assoc();
	}

	public function delete_attachment($attachment_id)
	{
		$query = "DELETE FROM attachments WHERE id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i", $attachment_id);
		$stmt->execute();
		$stmt->close();
	}
}

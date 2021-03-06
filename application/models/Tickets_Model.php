<?php
class Tickets_Model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	/* Get Tickets */

	function get_tickets( $id ) {
		$this->db->select( '*,customers.type as type, customers.email as customeremail, customers.company as company,customers.namesurname as namesurname,departments.name as department,staff.staffname as staffmembername,staff.email as staffemail,contacts.name as contactname,contacts.surname as contactsurname,tickets.staff_id as stid,tickets.status_id as status_id, tickets.id as id' );
		$this->db->join( 'contacts', 'tickets.contact_id = contacts.id', 'left' );
		$this->db->join( 'customers', 'contacts.customer_id = customers.id', 'left' );
		$this->db->join( 'departments', 'tickets.department_id = departments.id', 'left' );
		$this->db->join( 'staff', 'tickets.staff_id = staff.id', 'left' );
		return $this->db->get_where( 'tickets', array( 'tickets.id' => $id ) )->row_array();
	}

	/* Get All Tickets */

	function get_all_tickets() {
		$this->db->select( '*,departments.name as department,staff.staffname as staffmembername,staff.staffavatar as staffavatar,contacts.name as contactname,contacts.surname as contactsurname, tickets.id as id, contacts.email as contactemail ' );
		$this->db->join( 'contacts', 'tickets.contact_id = contacts.id', 'left' );
		$this->db->join( 'departments', 'tickets.department_id = departments.id', 'left' );
		$this->db->join( 'staff', 'tickets.staff_id = staff.id', 'left' );
		$this->db->order_by( 'date desc, priority desc' );
		$this->db->order_by( "date", "desc" );
		return $this->db->get_where( 'tickets', array() )->result_array();
	}

	function get_all_tickets_by_customer( $id ) {
		$this->db->select( '*,departments.name as department,staff.staffname as staffmembername,staff.staffavatar as staffavatar,contacts.name as contactname,contacts.surname as contactsurname, tickets.id as id ' );
		$this->db->join( 'contacts', 'tickets.contact_id = contacts.id', 'left' );
		$this->db->join( 'departments', 'tickets.department_id = departments.id', 'left' );
		$this->db->join( 'staff', 'tickets.staff_id = staff.id', 'left' );
		$this->db->order_by( 'date desc, priority desc' );
		$this->db->order_by( "date", "desc" );
		return $this->db->get_where( 'tickets', array( 'contact_id' => $id ) )->result_array();
	}

	function get_all_open_tickets() {
		$this->db->select( '*,departments.name as department,staff.staffname as staffmembername,staff.staffavatar as staffavatar,contacts.name as contactname,contacts.surname as contactsurname, tickets.id as id ' );
		$this->db->join( 'contacts', 'tickets.contact_id = contacts.id', 'left' );
		$this->db->join( 'departments', 'tickets.department_id = departments.id', 'left' );
		$this->db->join( 'staff', 'tickets.staff_id = staff.id', 'left' );
		return $this->db->get_where( 'tickets', array( 'tickets.status_id' => 1 ) )->result_array();
	}

	function get_all_open_tickets_by_staff() {
		$this->db->select( '*,departments.name as department,staff.staffname as staffmembername,staff.staffavatar as staffavatar,contacts.name as contactname,contacts.surname as contactsurname, tickets.id as id ' );
		$this->db->join( 'contacts', 'tickets.contact_id = contacts.id', 'left' );
		$this->db->join( 'departments', 'tickets.department_id = departments.id', 'left' );
		$this->db->join( 'staff', 'tickets.staff_id = staff.id', 'left' );
		return $this->db->get_where( 'tickets', array( 'tickets.status_id' => 1, 'staff_id' => $this->session->usr_id ) )->result_array();
	}

	function get_all_inprogress_tickets() {
		$this->db->select( '*,departments.name as department,staff.staffname as staffmembername,staff.staffavatar as staffavatar,contacts.name as contactname,contacts.surname as contactsurname, tickets.id as id ' );
		$this->db->join( 'contacts', 'tickets.contact_id = contacts.id', 'left' );
		$this->db->join( 'departments', 'tickets.department_id = departments.id', 'left' );
		$this->db->join( 'staff', 'tickets.staff_id = staff.id', 'left' );
		return $this->db->get_where( 'tickets', array( 'tickets.status_id' => 2 ) )->result_array();
	}

	function get_all_answered_tickets() {
		$this->db->select( '*,departments.name as department,staff.staffname as staffmembername,staff.staffavatar as staffavatar,contacts.name as contactname,contacts.surname as contactsurname, tickets.id as id ' );
		$this->db->join( 'contacts', 'tickets.contact_id = contacts.id', 'left' );
		$this->db->join( 'departments', 'tickets.department_id = departments.id', 'left' );
		$this->db->join( 'staff', 'tickets.staff_id = staff.id', 'left' );
		return $this->db->get_where( 'tickets', array( 'tickets.status' => 3 ) )->result_array();
	}

	function get_all_closed_tickets() {
		$this->db->select( '*,departments.name as department,staff.staffname as staffmembername,staff.staffavatar as staffavatar,contacts.name as contactname,contacts.surname as contactsurname, tickets.id as id ' );
		$this->db->join( 'contacts', 'tickets.contact_id = contacts.id', 'left' );
		$this->db->join( 'departments', 'tickets.department_id = departments.id', 'left' );
		$this->db->join( 'staff', 'tickets.staff_id = staff.id', 'left' );
		return $this->db->get_where( 'tickets', array( 'tickets.status_id' => 4 ) )->result_array();
	}

	function add_tickets( $params ) {
		$this->db->insert( 'tickets', $params );
		$ticket = $this->db->insert_id();
		$appconfig = get_appconfig();
		$number = $appconfig['ticket_series'] ? $appconfig['ticket_series'] : $ticket;
		$ticket_number = $appconfig['ticket_prefix'].$number;
		$this->db->where('id', $ticket)->update( 'tickets', array('ticket_number' => $ticket_number ) );
		$url = base_url('tickets/ticket/'.$ticket);
		$this->db->insert( 'logs', array( 
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $this->session->usr_id . '"> ' . $this->session->staffname . '</a> ' . lang( 'added' ) . ' <a href="tickets/ticket/' . $ticket . '">' . get_number('tickets',$ticket,'ticket','ticket') . '</a>' ),
			'staff_id' => $this->session->usr_id
		) );
		$this->db->insert( 'notifications', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '' . $this->session->staffname . ' '. lang( 'created_a' ).' ' . lang( 'ticket' ) . ' ' . get_number('tickets',$ticket,'ticket','ticket') . '' ),
			'contact_id' => $params[ 'contact_id' ],
			'perres' => $this->session->staffavatar,
			'target' => '' . base_url( 'area/tickets/ticket/' . $ticket . '' ) . ''
		) );
		return $ticket;
	}

	function add_reply_contact( $params ) {
		$this->db->insert( 'ticketreplies', $params );
		return $this->db->insert_id();
	}

	function update_tickets( $id, $params ) {
		$appconfig = get_appconfig();
		$ticket_data = $this->get_tickets($id);
		if($ticket_data['ticket_number']==''){
			$number = $appconfig['ticket_series'] ? $appconfig['ticket_series'] : $id;
			$ticket_number = $appconfig['ticket_prefix'].$number;
			$this->db->where('id',$id)->update('tickets',array('ticket_number'=>$ticket_number));
			if(($appconfig['ticket_series']!='')){
				$ticket_number = $appconfig['ticket_series'];
				$ticket_number = $ticket_number + 1;
				$this->Settings_Model->increment_series('ticket_series',$ticket_number);
			}
		}
		$this->db->where( 'id', $id );
		$response = $this->db->update( 'tickets', $params );
	}

	function markas() {
		$response = $this->db->where( 'id', $_POST[ 'ticket_id' ] )->update( 'tickets', array( 'status_id' => $_POST[ 'status_id' ] ) );
	}

	function delete_tickets( $id, $number ) {
		$response = $this->db->delete( 'tickets', array( 'id' => $id ) );
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $this->session->usr_id . '"> ' . $this->session->staffname . '</a> ' . lang( 'deleted' ) . ' ' . $number . '' ),
			'staff_id' => $this->session->usr_id
		) );
	}

	function check_tickets_permission($id, $contact_id) {
		$data = $this->db->get_where( 'tickets', array( 'id' => $id, 'contact_id' => $contact_id ) )->num_rows();
		if ($data > 0) {
			return true;
		} else {
			return false;
		}
	}

	function weekly_ticket_stats() {
		$this->db->where( 'CAST(date as DATE) >= "' . date( 'Y-m-d', strtotime( 'monday this week', strtotime( 'last sunday' ) ) ) . '" AND CAST(date as DATE) <= "' . date( 'Y-m-d', strtotime( 'sunday this week', strtotime( 'last sunday' ) ) ) . '"' );
		$tickets = $this->db->get( 'tickets' )->result_array();
		$chart = array(
			'labels' => get_weekdays(),
			'datasets' => array(
				array(
					'label' => 'Weekly Ticket Report',
					'backgroundColor' => 'rgba(197, 61, 169, 0.5)',
					'borderColor' => '#c53da9',
					'borderWidth' => 1,
					'tension' => false,
					'data' => array(
						0,
						0,
						0,
						0,
						0,
						0,
						0
					)
				)
			)
		);
		foreach ( $tickets as $ticket ) {
			$ticket_day = date( 'l', strtotime( $ticket[ 'date' ] ) );
			$i = 0;
			foreach ( get_weekdays_original() as $day ) {
				if ( $ticket_day == $day ) {
					$chart[ 'datasets' ][ 0 ][ 'data' ][ $i ]++;
				}
				$i++;
			}
		}
		return $chart;
	}

	function get_all_tickets_by_privileges($staff_id='') {
		$this->db->select( '*,departments.name as department,staff.staffname as staffmembername,staff.staffavatar as staffavatar,contacts.name as contactname,contacts.surname as contactsurname, tickets.id as id, contacts.email as contactemail ' );
		$this->db->join( 'contacts', 'tickets.contact_id = contacts.id', 'left' );
		$this->db->join( 'departments', 'tickets.department_id = departments.id', 'left' );
		$this->db->join( 'staff', 'tickets.staff_id = staff.id', 'left' );
		$this->db->order_by( 'date desc, priority desc' );
		$this->db->order_by( "date", "desc" );
		if($staff_id) {
			return $this->db->get_where( 'tickets', array('tickets.staff_id' => $staff_id) )->result_array();
		} else {
			return $this->db->get_where( 'tickets', array() )->result_array();
		}
	}

	function get_ticket_by_privileges( $id, $staff_id='' ) {
		$this->db->select( '*,customers.type as type, customers.email as customeremail, customers.company as company,customers.namesurname as namesurname,departments.name as department,staff.staffname as staffmembername,staff.email as staffemail,contacts.name as contactname,contacts.surname as contactsurname,tickets.staff_id as stid,tickets.status_id as status_id, tickets.id as id' );
		$this->db->join( 'contacts', 'tickets.contact_id = contacts.id', 'left' );
		$this->db->join( 'customers', 'contacts.customer_id = customers.id', 'left' );
		$this->db->join( 'departments', 'tickets.department_id = departments.id', 'left' );
		$this->db->join( 'staff', 'tickets.staff_id = staff.id', 'left' );
		if($staff_id) {
			return $this->db->get_where( 'tickets', array('tickets.staff_id' => $staff_id, 'tickets.id' => $id ) )->row_array();
		} else {
			return $this->db->get_where( 'tickets', array('tickets.id' => $id ) )->row_array();
		}
	}
}
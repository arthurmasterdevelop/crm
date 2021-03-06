<?php
class Search_Model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function search_data($input) {
        $input = trim($input);
        $isAdmin = $this->isAdmin();
        $limit = 5;
        $result = [];

        $staff_search = $this->searchStaffs($input, $limit);
        if (count($staff_search['result']) > 0) {
            $result[] = $staff_search;
        }

        $contacts_search = $this->searchCustomers($input, $limit);
        if (count($contacts_search['result']) > 0) {
            $result[] = $contacts_search;
        }

        $tickets_search = $this->searchTickets($input, $limit);
        if (count($tickets_search['result']) > 0) {
            $result[] = $tickets_search;
        }

        $leads_search = $this->searchLeads($input, $limit);
        if (count($leads_search['result']) > 0) {
            $result[] = $leads_search;
        }

        $proposal_search = $this->searchProposals($input, $limit);
        if (count($proposal_search['result']) > 0) {
            $result[] = $proposal_search;
        }

        $invoices_search = $this->searchInvoices($input, $limit);
        if (count($invoices_search['result']) > 0) {
            $result[] = $invoices_search;
        }

        $expenses_search = $this->searchExpenses($input, $limit);
        if (count($expenses_search['result']) > 0) {
            $result[] = $expenses_search;
        }

        $projects_search = $this->searchProjects($input, $limit);
        if (count($projects_search['result']) > 0) {
            $result[] = $projects_search;
        }

        $products_search = $this->searchProducts($input, $limit);
        if (count($products_search['result']) > 0) {
            $result[] = $products_search;
        }

        $orders_search = $this->searchOrders($input, $limit);
        if (count($orders_search['result']) > 0) {
            $result[] = $orders_search;
        }

        // $staff_search = $this->_search_accounts($input, $limit);
        // if (count($staff_search['result']) > 0) {
        //     $result[] = $staff_search;
        // }

        $tasks_search = $this->searchTasks($input, $limit);
        if (count($tasks_search['result']) > 0) {
            $result[] = $tasks_search;
        }

        $vendors_search = $this->searchVendors($input, $limit);
        if (count($vendors_search['result']) > 0) {
            $result[] = $vendors_search;
        }

        $purchases_search = $this->searchPurchases($input, $limit);
        if (count($purchases_search['result']) > 0) {
            $result[] = $purchases_search;
        }

        $deposits_search = $this->searchDeposits($input, $limit);
        if (count($deposits_search['result']) > 0) {
            $result[] = $deposits_search;
        }

        return $result;
    }

    function searchStaffs($q, $limit) {
        $result = [
            'result' => [],
            'type' => 'staff',
        ];
        $isAdmin = $this->isAdmin();
        $user_id = $this->session->userdata( 'usr_id' );
        $has_permission_view_inovices = $this->has_permission('staff');

        if ($isAdmin && $has_permission_view_inovices) {
            $this->db->select( 'staffname as name, staff.id as staff_id, email, staff.staff_number' );
            $this->db->from('staff');
            $this->db->join( 'departments', 'staff.department_id = departments.id', 'left' );
            $this->db->where('(
                staff.id LIKE "' . $q . '%"
                OR staff_number LIKE "%' . $q . '%"
                OR email LIKE "%' . $q . '%"
                OR staffname LIKE "%' . $q . '%"
                OR address LIKE "%' . $q . '%"
                OR departments.name LIKE "%' . $q . '%"
                )');
            $this->db->order_by('staff.id', 'desc');
            $this->db->limit($limit);
            $result['result'] = $this->db->get()->result_array();
        }
        return $result;
    }

    function searchTasks($q, $limit) {
        $result = [
            'result' => [],
            'type' => 'tasks',
        ];
        $isAdmin = $this->isAdmin();
        $user_id = $this->session->userdata( 'usr_id' );
        $has_permission_view_tasks = $this->has_permission('tasks');

        if ($has_permission_view_tasks) {
            $this->db->select( 'staff.staffname as staff, tasks.id, tasks.name, tasks.task_number' );
            $this->db->from('tasks');
            $this->db->join( 'staff', 'tasks.assigned = staff.id', 'left' );
            
            $this->db->where('(
                tasks.id LIKE "' . $q . '%"
                OR tasks.task_number LIKE "%' . $q . '%"
                OR staff.email LIKE "%' . $q . '%"
                OR staff.staffname LIKE "%' . $q . '%"
                OR tasks.name LIKE "%' . $q . '%"
                OR tasks.description LIKE "%' . $q . '%"
                )');
            $this->db->order_by('tasks.id', 'desc');
            $this->db->limit($limit);
            $result['result'] = $this->db->get()->result_array();
        }
        return $result;
    }

    function searchOrders($q, $limit) {
        $result = [
            'result' => [],
            'type' => 'orders',
        ];
        $isAdmin = $this->isAdmin();
        $user_id = $this->session->userdata( 'usr_id' );
        $has_permission_view_orders = $this->has_permission('orders');

        if ($has_permission_view_orders) {
            $this->db->select( 'staff.staffname as staff, orders.id, orders.subject, customers.namesurname as name, customers.company as company,orders.order_number' );
            $this->db->from('orders');
            $this->db->join( 'staff', 'orders.assigned = staff.id', 'left' );
            $this->db->join( 'customers', "orders.relation = customers.id AND relation_type = 'customer'", 'left' );

            $this->db->where('(
                orders.id LIKE "' . $q . '%"
                OR staff.email LIKE "%' . $q . '%"
                OR staff.staffname LIKE "%' . $q . '%"
                OR orders.subject LIKE "%' . $q . '%"
                OR orders.content LIKE "%' . $q . '%"
                OR customers.zipcode LIKE "%' . $q . '%"
                OR customers.state LIKE "%' . $q . '%"
                OR customers.city LIKE "%' . $q . '%"
                OR customers.address LIKE "%' . $q . '%"
                OR customers.email LIKE "%' . $q . '%"
                OR customers.phone LIKE "%' . $q . '%"
                OR customers.namesurname LIKE "%' . $q . '%"
                OR orders.order_number LIKE "%' . $q . '%"
                )');
            $this->db->order_by('orders.id', 'desc');
            $this->db->limit($limit);
            $result['result'] = $this->db->get()->result_array();
        }
        return $result;
    }

    function searchTickets($q, $limit) {
        $result = [
            'result' => [],
            'type' => 'tickets',
        ];
        $isAdmin = $this->isAdmin();
        $user_id = $this->session->userdata( 'usr_id' );
        $has_permission_view_tickets = $this->has_permission('tickets');

        if ($has_permission_view_tickets) {
            $this->db->select( 'tickets.id, tickets.subject, tickets.message, tickets.ticket_number' );
            $this->db->from('tickets');
            $this->db->join( 'departments', 'tickets.department_id = departments.id', 'left' );
            $this->db->join( 'staff', 'tickets.staff_id = staff.id', 'left' );
            $this->db->where('(
                tickets.id LIKE "' . $q . '%"
                OR staff.staffname LIKE "%' . $q . '%"
                OR staff.email LIKE "%' . $q . '%"
                OR departments.name LIKE "%' . $q . '%"
                OR tickets.subject LIKE "%' . $q . '%"
                OR tickets.message LIKE "%' . $q . '%"
                OR tickets.ticket_number LIKE "%' . $q . '%"
                )');
            $this->db->order_by( 'tickets.date desc, tickets.priority desc' );
            $this->db->limit($limit);
            $result['result'] = $this->db->get()->result_array();
        }
        return $result;
    }

    function searchCustomers($q, $limit) {
        $result = [
            'result' => [],
            'type' => 'customers',
        ];
        $isAdmin = $this->isAdmin();
        $user_id = $this->session->userdata( 'usr_id' );
        $has_permission_view_customers = $this->has_permission('customers');

        if ($has_permission_view_customers) {
            $this->db->select( 'customers.zipcode, customers.customer_number,customers.state, customers.city, customers.address, customers.email, customers.phone, customers.namesurname as name, customers.company, customers.id' );
            $this->db->from('customers');
            $this->db->join( 'customergroups', 'customers.groupid = customergroups.id', 'left' );
            if (!$isAdmin) {
                $this->db->where("customers.staff_id = '$user_id'");
            }
            $this->db->where('(
                customers.id LIKE "' . $q . '%"
                OR customers.zipcode LIKE "%' . $q . '%"
                OR customers.state LIKE "%' . $q . '%"
                OR customers.city LIKE "%' . $q . '%"
                OR customers.address LIKE "%' . $q . '%"
                OR customers.email LIKE "%' . $q . '%"
                OR customers.phone LIKE "%' . $q . '%"
                OR customers.company LIKE "%' . $q . '%"
                OR customers.namesurname LIKE "%' . $q . '%"
                OR customers.customer_number LIKE "%' .$q . '%" 
                OR customergroups.name LIKE "%' . $q . '%"
                )');
            $this->db->order_by('customers.id', 'desc');
            $this->db->limit($limit);
            $result['result'] = $this->db->get()->result_array();
        }
        return $result;
    }

    function searchLeads($q, $limit) {
        $result = [
            'result' => [],
            'type' => 'leads',
        ];
        $isAdmin = $this->isAdmin();
        $user_id = $this->session->userdata( 'usr_id' );
        $has_permission_view_leads = $this->has_permission('leads');

        if ($has_permission_view_leads) {
            $this->db->select( 'leads.company, leads.name, leads.title, leads.email, leads.id, leads.lead_number' );
            $this->db->from('leads');
            $this->db->join( 'leadsstatus', 'leads.status = leadsstatus.id', 'left' );
            $this->db->join( 'leadssources', 'leads.source = leadssources.id', 'left' );
            $this->db->join( 'staff', 'leads.assigned_id = staff.id', 'left' );
            if (!$isAdmin) {
                $this->db->where("public = 1 OR assigned_id = '$user_id' OR staff_id = '$user_id'");
            }
            $this->db->where('(
                leads.id LIKE "' . $q . '%"
                OR staff.email LIKE "%' . $q . '%"
                OR staff.staffname LIKE "%' . $q . '%"
                OR leads.name LIKE "%' . $q . '%"
                OR leads.title LIKE "%' . $q . '%"
                OR leads.zip LIKE "%' . $q . '%"
                OR leads.state LIKE "%' . $q . '%"
                OR leads.city LIKE "%' . $q . '%"
                OR leads.address LIKE "%' . $q . '%"
                OR leads.email LIKE "%' . $q . '%"
                OR leads.phone LIKE "%' . $q . '%"
                OR leads.description LIKE "%' . $q . '%"
                OR leads.company LIKE "%' . $q . '%"
                OR leadsstatus.name LIKE "%' . $q . '%"
                OR leadssources.name LIKE "%' . $q . '%"
                OR leads.lead_number LIKE "%' . $q . '%"
                )');
            $this->db->order_by('leads.id', 'desc');
            $this->db->limit($limit);
            $result['result'] = $this->db->get()->result_array();
        }
        return $result;
    }

    function searchProducts($q, $limit) {
        $result = [
            'result' => [],
            'type' => 'products',
        ];
        $isAdmin = $this->isAdmin();
        $user_id = $this->session->userdata( 'usr_id' );
        $has_permission_view_products = $this->has_permission('products');

        if ($has_permission_view_products) {
            $this->db->select('products.id as id, products.productname as name, products.description, products.product_number');
            $this->db->from('products');
            $this->db->join( 'productcategories', 'products.categoryid = productcategories.id', 'left' );
            $this->db->where('(
                products.id LIKE "' . $q . '%"
                OR products.productname LIKE "%' . $q . '%"
                OR products.description LIKE "%' . $q . '%"
                OR products.code LIKE "%' . $q . '%"
                OR productcategories.name LIKE "%' . $q . '%"
                OR products.product_number LIKE "%' . $q . '%"
                )');
            $this->db->order_by('products.id', 'desc');
            $this->db->limit($limit);
            $result['result'] = $this->db->get()->result_array();
        }
        return $result;
    }

    function searchExpenses($q, $limit) {
        $result = [
            'result' => [],
            'type' => 'expenses',
        ];
        $isAdmin = $this->isAdmin();
        $user_id = $this->session->userdata( 'usr_id' );
        $has_permission_view_expenses = $this->has_permission('expenses');

        if ($has_permission_view_expenses) {
            $this->db->select( 'expenses.title, expenses.id, expenses.expense_number' );
            $this->db->from('expenses');
            $this->db->join( 'customers', 'expenses.customer_id = customers.id', 'left' );
            $this->db->join( 'expensecat', 'expenses.category_id = expensecat.id', 'left' );
            $this->db->join( 'staff', 'expenses.staff_id = staff.id', 'left' );
            if (!$this->session->userdata('other')) {
                if (!$isAdmin) {
                    $this->db->where("expenses.staff_id = '$user_id'");
                }
            }
            $this->db->where('(
                expenses.id LIKE "' . $q . '%"
                OR staff.email LIKE "%' . $q . '%"
                OR staff.staffname LIKE "%' . $q . '%"
                OR expenses.description LIKE "%' . $q . '%"
                OR expenses.title LIKE "%' . $q . '%"
                OR customers.zipcode LIKE "%' . $q . '%"
                OR customers.state LIKE "%' . $q . '%"
                OR customers.city LIKE "%' . $q . '%"
                OR customers.address LIKE "%' . $q . '%"
                OR customers.email LIKE "%' . $q . '%"
                OR customers.phone LIKE "%' . $q . '%"
                OR customers.namesurname LIKE "%' . $q . '%"
                OR expensecat.name LIKE "%' . $q . '%"
                OR expenses.expense_number LIKE "%' . $q . '%"
                )');
            $this->db->order_by('expenses.id', 'desc');
            $this->db->limit($limit);
            $result['result'] = $this->db->get()->result_array();
        }
        return $result;
    }

    function searchProposals($q, $limit) {
        $result = [
            'result' => [],
            'type' => 'proposals',
        ];
        $isAdmin = $this->isAdmin();
        $user_id = $this->session->userdata( 'usr_id' );
        $has_permission_view_proposals = $this->has_permission('proposals');

        if ($has_permission_view_proposals) {
            $this->db->select( 'staff.staffname as staffmembername, staff.email as staffemail, proposals.id as proposal_id, proposals.content, proposals.assigned as staffId, proposals.subject, customers.zipcode, customers.state, customers.city, customers.address, customers.email, customers.phone, customers.namesurname, proposals.total, proposals.proposal_number' );
            $this->db->from('proposals');
            $this->db->join( 'staff', 'proposals.assigned = staff.id', 'left' );
            $this->db->join( "customers", "proposals.relation = customers.id AND proposals.relation_type = 'customer'", "left" );
            if (!$isAdmin) {
                $this->db->where("proposals.assigned = '$user_id'");
            }
            $this->db->where('(
                proposals.id LIKE "' . $q . '%"
                OR staff.email LIKE "%' . $q . '%"
                OR staff.staffname LIKE "%' . $q . '%"
                OR proposals.content LIKE "%' . $q . '%"
                OR proposals.subject LIKE "%' . $q . '%"
                OR customers.zipcode LIKE "%' . $q . '%"
                OR customers.state LIKE "%' . $q . '%"
                OR customers.city LIKE "%' . $q . '%"
                OR customers.address LIKE "%' . $q . '%"
                OR customers.email LIKE "%' . $q . '%"
                OR customers.phone LIKE "%' . $q . '%"
                OR customers.namesurname LIKE "%' . $q . '%"
                OR proposals.proposal_number LIKE "%' . $q . '%"
                )');
            $this->db->order_by('proposals.id', 'desc');
            $this->db->limit($limit);
            $result['result'] = $this->db->get()->result_array();
        }
        return $result;
    }

    function searchProjects($q, $limit) {
        $result = [
            'result' => [],
            'type' => 'projects',
        ];
        $isAdmin = $this->isAdmin();
        $user_id = $this->session->userdata( 'usr_id' );
        $has_permission_view_projects = $this->has_permission('projects');

        if ($has_permission_view_projects) {
            $this->db->select( 'projects.name, projects.status_id as status, projects.id, projects.project_number' );
            $this->db->from('projects');
            $this->db->join( 'customers', 'projects.customer_id = customers.id', 'left' );
            $this->db->where('(
                projects.id LIKE "' . $q . '%"
                OR projects.name LIKE "%' . $q . '%"
                OR projects.description LIKE "%' . $q . '%"
                OR customers.zipcode LIKE "%' . $q . '%"
                OR customers.state LIKE "%' . $q . '%"
                OR customers.city LIKE "%' . $q . '%"
                OR customers.address LIKE "%' . $q . '%"
                OR customers.email LIKE "%' . $q . '%"
                OR customers.phone LIKE "%' . $q . '%"
                OR customers.namesurname LIKE "%' . $q . '%"
                OR projects.project_number LIKE "%' . $q . '%"
                )');
            $this->db->order_by('projects.id', 'desc');
            $this->db->limit($limit);
            $result['result'] = $this->db->get()->result_array();
        }
        return $result;
    }

    function searchInvoices($q, $limit) {
        $result = [
            'result' => [],
            'type' => 'invoices',
        ];
        $isAdmin = $this->isAdmin();
        $user_id = $this->session->userdata( 'usr_id' );
        $has_permission_view_inovices = $this->has_permission('invoices');

        if ($has_permission_view_inovices) {
            $this->db->select( 'invoices.invoice_number,staff.staffname as staffmembername, staff.email as staffemail, invoices.id as invoice_id, invoices.staff_id as staffId, customers.zipcode, customers.state, customers.city, customers.address, customers.email, customers.phone, customers.namesurname, customers.company,invoices.total' );
            $this->db->from('invoices');
            $this->db->join( 'customers', 'invoices.customer_id = customers.id', 'left' );
            $this->db->join( 'staff', 'invoices.staff_id = staff.id', 'left' );
            if (!$this->session->userdata('other')) {
                if (!$isAdmin) {
                    $this->db->where("invoices.staff_id = '$user_id'");
                }
            }
            $this->db->where('(
                invoices.id LIKE "' . $q . '%"
                OR staff.email LIKE "%' . $q . '%"
                OR staff.staffname LIKE "%' . $q . '%"
                OR customers.zipcode LIKE "%' . $q . '%"
                OR customers.state LIKE "%' . $q . '%"
                OR customers.city LIKE "%' . $q . '%"
                OR customers.address LIKE "%' . $q . '%"
                OR customers.email LIKE "%' . $q . '%"
                OR customers.phone LIKE "%' . $q . '%"
                OR customers.namesurname LIKE "%' . $q . '%"
                OR customers.company LIKE "%' . $q . '%"
                OR invoices.invoice_number LIKE "%' . $q . '%"
                )');
            $this->db->order_by('invoices.id', 'desc');
            $this->db->limit($limit);
            $result['result'] = $this->db->get()->result_array();
        }
        return $result;
    }

    function has_permission( $path ) {
        $relation = $this->session->usr_id;
        $this->db->select( '*,permissions.key as permission_key');
        $this->db->join( 'permissions', 'privileges.permission_id = permissions.id', 'left' );
        $rows = $this->db->get_where( 'privileges', array( 'permissions.key' => $path, 'relation' => $relation, 'relation_type' => 'staff') )->num_rows();
        if ($rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    function isAdmin() {
        $id = $this->session->usr_id;
        $this->db->select( '*');
        $rows = $this->db->get_where( 'staff', array( 'admin' => 1, 'id' => $id ) )->num_rows();
        if ($rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    function searchVendors($q, $limit) {
        $result = [
            'result' => [],
            'type' => 'vendors',
        ];
        $isAdmin = $this->isAdmin();
        $user_id = $this->session->userdata( 'usr_id' );
        $has_permission_view_vendors = $this->has_permission('vendors');

        if ($has_permission_view_vendors) {
            $this->db->select( 'vendors.zipcode, vendors.vendor_number,vendors.state, vendors.city, vendors.address, vendors.email, vendors.phone, vendors.company, vendors.id' );
            $this->db->from('vendors');
            $this->db->join( 'vendors_groups', 'vendors.groupid = vendors_groups.id', 'left' );
            if (!$isAdmin) {
                $this->db->where("vendors.staff_id = '$user_id'");
            }
            $this->db->where('(
                vendors.id LIKE "' . $q . '%"
                OR vendors.zipcode LIKE "%' . $q . '%"
                OR vendors.state LIKE "%' . $q . '%"
                OR vendors.city LIKE "%' . $q . '%"
                OR vendors.address LIKE "%' . $q . '%"
                OR vendors.email LIKE "%' . $q . '%"
                OR vendors.phone LIKE "%' . $q . '%"
                OR vendors.company LIKE "%' . $q . '%"
                OR vendors.vendor_number LIKE "%' .$q . '%" 
                OR vendors_groups.name LIKE "%' . $q . '%"
                )');
            $this->db->order_by('vendors.id', 'desc');
            $this->db->limit($limit);
            $result['result'] = $this->db->get()->result_array();
        }
        return $result;
    }

    function searchPurchases($q, $limit) {
        $result = [
            'result' => [],
            'type' => 'purchases',
        ];
        $isAdmin = $this->isAdmin();
        $user_id = $this->session->userdata( 'usr_id' );
        $has_permission_view_purchases = $this->has_permission('purchases');

        if ($has_permission_view_purchases) {
            $this->db->select( 'purchases.purchase_number, purchases.id as purchase_id, vendors.zipcode, vendors.state, vendors.city, vendors.address, vendors.email, vendors.phone, vendors.company, purchases.total' );
            $this->db->from('purchases');
            $this->db->join( 'vendors', 'purchases.vendor_id = vendors.id', 'left' );
            if (!$this->session->userdata('other')) {
                if (!$isAdmin) {
                    $this->db->where("purchases.staff_id = '$user_id'");
                }
            }
            $this->db->where('(
                purchases.id LIKE "' . $q . '%"
                OR vendors.zipcode LIKE "%' . $q . '%"
                OR vendors.state LIKE "%' . $q . '%"
                OR vendors.city LIKE "%' . $q . '%"
                OR vendors.address LIKE "%' . $q . '%"
                OR vendors.email LIKE "%' . $q . '%"
                OR vendors.phone LIKE "%' . $q . '%"
                OR vendors.company LIKE "%' . $q . '%"
                OR purchases.purchase_number LIKE "%' . $q . '%"
                )');
            $this->db->order_by('purchases.id', 'desc');
            $this->db->limit($limit);
            $result['result'] = $this->db->get()->result_array();
        }
        return $result;
    }

    function searchDeposits($q, $limit) {
        $result = [
            'result' => [],
            'type' => 'deposits',
        ];
        $isAdmin = $this->isAdmin();
        $user_id = $this->session->userdata( 'usr_id' );
        $has_permission_view_deposits = $this->has_permission('deposits');

        if ($has_permission_view_deposits) {
            $this->db->select( 'deposits.title, deposits.id, deposits.deposit_number' );
            $this->db->from('deposits');
            $this->db->join( 'customers', 'deposits.customer_id = customers.id', 'left' );
            $this->db->join( 'depositcat', 'deposits.category_id = depositcat.id', 'left' );
            $this->db->join( 'staff', 'deposits.staff_id = staff.id', 'left' );
                if (!$isAdmin) {
                    $this->db->where("deposits.staff_id = '$user_id'");
                }
            $this->db->where('(
                deposits.id LIKE "' . $q . '%"
                OR staff.email LIKE "%' . $q . '%"
                OR staff.staffname LIKE "%' . $q . '%"
                OR deposits.description LIKE "%' . $q . '%"
                OR deposits.title LIKE "%' . $q . '%"
                OR customers.zipcode LIKE "%' . $q . '%"
                OR customers.state LIKE "%' . $q . '%"
                OR customers.city LIKE "%' . $q . '%"
                OR customers.address LIKE "%' . $q . '%"
                OR customers.email LIKE "%' . $q . '%"
                OR customers.phone LIKE "%' . $q . '%"
                OR customers.namesurname LIKE "%' . $q . '%"
                OR depositcat.name LIKE "%' . $q . '%"
                OR deposits.deposit_number LIKE "%' . $q . '%"
                )');
            $this->db->order_by('deposits.id', 'desc');
            $this->db->limit($limit);
            $result['result'] = $this->db->get()->result_array();
        }
        return $result;
    }
}
?>

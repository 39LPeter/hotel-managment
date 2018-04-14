<?php

class CustomerHandler
{
    public function getAllCustomer()
    {
        try {
            $dao = new CustomerDAO();
            return $dao->getAll();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function getSingleRow($email)
    {
        try {
            $dao = new CustomerDAO();
            return $dao->getCustomerByEmail($email);
        } catch (Exception $e) {
            return "Error. " . $e;
        }
    }

    // return full customer info based on email
    // usually you use getByEmail but this requires looping again
    public function getCustomerObj($email) {
        $c = new Customer();
        $dao = new CustomerDAO();
        $k = $dao->getCustomerByEmail($email);
        foreach ($k as $v) {
            $c->setId($v->getId());
            $c->setEmail($v->getEmail());
            $c->setPassword($v->getPassword());
            $c->setPhone($v->getPhone());
            $c->setFullName($v->getFullName());
        }
        return $c;
    }

    public function getUsername($_email)
    {
        $_fullName = null;
        foreach ($this->getSingleRow($_email) as $obj) {
            $_fullName = $obj->getFullName();
        }
        if ($_fullName != null) {
            return $_fullName;
        } else {
            $positionOfAt = strpos($_email, "@");
            return substr($_email, 0, $positionOfAt);
        }
    }

    public function insertCustomer(Customer $customer)
    {
        try {
            if (!$this->isEmailExists($customer->getEmail())) {
                $dao = new CustomerDAO();
                $dao->insert($customer);
            }
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    public function updateCustomer(Customer $customer)
    {
        try {
            if ($this->isEmailExists($customer->getEmail())) {
                $dao = new CustomerDAO();
                $dao->update($customer);
            }
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    public function isEmailExists($email) {
        return count($this->getSingleRow($email)) > 0;
    }

    public function deleteCustomer(Customer $customer)
    {
        try {
            if ($this->isEmailExists($customer->getEmail())) {
                $dao = new CustomerDAO();
                $dao->delete($customer);
            }
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    public function isPasswordMatchWithEmail($password, Customer $customer)
    {
        $hash = null;
        foreach ($this->getSingleRow($customer->getEmail()) as $obj) {
            $hash = $obj->getPassword();
        }
        return (password_verify($password, $hash));
    }

}
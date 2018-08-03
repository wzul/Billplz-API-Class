<?php

namespace Billplz;

class API
{
    private $connect;

    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    public function setConnect($connect)
    {
        $this->connect = $connect;
    }

    public function getCollectionIndex(array $parameter = array())
    {
        $collection_index = $this->connect->getCollectionIndex($parameter);
        if ($collection_index[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->url = $this->connect::STAGING_URL;
            return $this->getCollectionIndex($parameter);
        }
        return $collection_index;
    }

    public function createCollection(string $parameter, array $optional = array())
    {
        $create_collection = $this->connect->createCollection($parameter, $optional);
        if ($create_collection[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->url = $this->connect::STAGING_URL;
            return $this->createCollection($parameter, $optional);
        }
        return $create_collection;
    }

    public function getCollection(string $parameter)
    {
        $get_collection = $this->connect->getCollection($parameter);
        if ($get_collection[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->url = $this->connect::STAGING_URL;
            return $this->getCollection($parameter);
        }
        return $get_collection;
    }

    public function createOpenCollection(array $parameter, array $optional = array())
    {
        $parameter['title'] = substr($parameter['title'], 0, 49);
        $parameter['description'] = substr($parameter['description'], 0, 199);

        if (intval($parameter['amount']) > 999999999) {
            throw new \Exception("Amount Invalid. Too big");
        }

        $create_open_collection = $this->connect->createOpenCollection($parameter, $optional);
        if ($create_open_collection[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->url = $this->connect::STAGING_URL;
            return $this->createOpenCollection($parameter, $optional);
        }
        return $create_open_collection;
    }

    public function getOpenCollection(string $parameter)
    {
        $get_open_collection = $this->connect->getOpenCollection($parameter);
        if ($get_open_collection[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->url = $this->connect::STAGING_URL;
            return $this->getOpenCollection($parameter);
        }
        return $get_open_collection;
    }

    public function getOpenCollectionIndex(array $parameter = array())
    {
        $get_open_collection_index = $this->connect->getOpenCollectionIndex($parameter);
        if ($get_open_collection_index[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->url = $this->connect::STAGING_URL;
            return $this->getOpenCollectionIndex($parameter);
        }
        return $get_open_collection_index;
    }

    public function createMPICollection(string $parameter)
    {
        $create_mpi_collection = $this->connect->createMPICollection($parameter);
        if ($create_mpi_collection[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->url = $this->connect::STAGING_URL;
            return $this->createMPICollection($parameter);
        }
        return $create_mpi_collection;
    }

    public function getMPICollection(string $parameter)
    {
        $get_mpi_collection = $this->connect->getMPICollection($parameter);
        if ($get_mpi_collection[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->url = $this->connect::STAGING_URL;
            return $this->getMPICollection($parameter);
        }
        return $get_mpi_collection;
    }

    public function createMPI(array $parameter, array $optional = array())
    {
        $create_mpi = $this->connect->createMPI($parameter, $optional);
        if ($create_mpi[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->url = $this->connect::STAGING_URL;
            return $this->createMPI($parameter, $optional);
        }
        return $create_mpi;
    }

    public function getMPI(string $parameter)
    {
        $get_mpi = $this->connect->getMPI($parameter);
        if ($get_mpi[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->url = $this->connect::STAGING_URL;
            return $this->getMPI($parameter);
        }
        return $get_mpi;
    }

    public function deactivateCollection(string $parameter)
    {
        $deactivate_collection = $this->connect->deactivateCollection($parameter);
        if ($deactivate_collection[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->url = $this->connect::STAGING_URL;
            return $this->deactivateCollection($parameter);
        }
        return $deactivate_collection;
    }

    public function activateCollection(string $parameter)
    {
        $activate_collection = $this->connect->deactivateCollection($parameter, 'activate');
        if ($activate_collection[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->url = $this->connect::STAGING_URL;
            return $this->activateCollection($parameter);
        }
        return $activate_collection;
    }

    public function createBill(array $parameter, array $optional = array(), string $sendCopy = '')
    {
        /* Email or Mobile must be set */
        if (empty($parameter['email']) && empty($parameter['mobile'])) {
            throw new \Exception("Email or Mobile must be set!");
        }

        /* Manipulate Deliver features to allow Email/SMS Only copy */
        if ($sendCopy === '0') {
            $optioonal['deliver'] = 'false';
        } elseif ($sendCopy === '1' && !empty($parameter['email'])) {
            $optional['deliver'] = 'true';
            unset($parameter['mobile']);
        } elseif ($sendCopy === '2' && !empty($parameter['mobile'])) {
            $optional['deliver'] = 'true';
            unset($parameter['email']);
        } elseif ($sendCopy === '3') {
            $optional['deliver'] = 'true';
        }

        /* Validate Mobile Number first */
        if (!empty($parameter['mobile'])) {
            /* Strip all unwanted character */
            $parameter['mobile'] = preg_replace('/[^0-9]/', '', $parameter['mobile']);

            /* Add '6' if applicable */
            $parameter['mobile'] = $parameter['mobile'][0] === '0' ? '6'.$parameter['mobile'] : $parameter['mobile'];

            /* If the number doesn't have valid formatting, reject it */
            /* The ONLY valid format '<1 Number>' + <10 Numbers> or '<1 Number>' + <11 Numbers> */
            /* Example: '60141234567' or '601412345678' */
            if (!preg_match('/^[0-9]{11,12}$/', $parameter['mobile'], $m)) {
                $parameter['mobile'] = '';
            }
        }

        /* Create Bills */
        $bill = $this->connect->createBill($parameter, $optional);
        if ($bill[0] === 200) {
            return $bill;
        }

        if ($bill[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->url = $this->connect::STAGING_URL;
            return $this->createBill($parameter, $optional, $sendCopy);
        }

        /* Check if Failed caused by wrong Collection ID */
        $collection = $this->toArray($this->getCollection($parameter['collection_id']));

        /* If doesn't exists or belong to another merchant */
        /* + In-case the collection id is an empty string */
        if ($collection[0] === 404 || $collection[0] === 401 || empty($parameter['collection_id'])) {
            /* Get All Active & Inactive Collection List */
            $collectionIndexActive = $this->toArray($this->getCollectionIndex(array('page'=>'1', 'status'=>'active')));
            $collectionIndexInactive = $this->toArray($this->getCollectionIndex(array('page'=>'1', 'status'=>'inactive')));

            /* If Active Collection not available but Inactive Collection is available */
            if (empty($collectionIndexActive[1]['collections']) && !empty($collectionIndexInactive[1]['collections'])) {
                /* Use inactive collection */
                $parameter['collection_id'] = $collectionIndexInactive[1]['collections'][0]['id'];
            }

            /* If there is Active Collection */
            elseif (!empty($collectionIndexActive[1]['collections'])) {
                $parameter['collection_id'] = $collectionIndexActive[1]['collections'][0]['id'];
            }

            /* If there is no Active and Inactive Collection */
            else {
                $collection = $this->toArray($this->createCollection('Payment for Purchase'));
                $parameter['collection_id'] = $collection[1]['id'];
            }
        } else {
            return $bill;
        }

        /* Create Bills */
        return $this->connect->createBill($parameter, $optional);
    }

    public function deleteBill(string $parameter)
    {
        $delete_bill = $this->connect->deleteBill($parameter);
        if ($delete_bill[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->url = $this->connect::STAGING_URL;
            return $this->deleteBill($parameter);
        }
        return $delete_bill;
    }

    public function getBill(string $parameter)
    {
        $get_bill = $this->connect->getBill($parameter);
        if ($get_bill[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->url = $this->connect::STAGING_URL;
            return $this->getBill($parameter);
        }
        return $get_bill;
    }

    public function bankAccountCheck(string $parameter)
    {
        $bank_account_check = $this->connect->bankAccountCheck($parameter);
        if ($bank_account_check[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->url = $this->connect::STAGING_URL;
            return $this->bankAccountCheck($parameter);
        }
        return $bank_account_check;
    }

    public function getTransactionIndex(string $id, array $parameter = array('page'=>'1'))
    {
        $get_transaction_index = $this->connect->getTransactionIndex($id, $parameter);
        if ($get_transaction_index[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->url = $this->connect::STAGING_URL;
            return $this->getTransactionIndex($parameter);
        }
        return $get_transaction_index;
    }

    public function getPaymentMethodIndex(string $parameter)
    {
        $get_payment_method_index = $this->connect->getPaymentMethodIndex($parameter);
        if ($get_payment_method_index[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->url = $this->connect::STAGING_URL;
            return $this->getPaymentMethodIndex($parameter);
        }
        return $get_payment_method_index;
    }

    public function updatePaymentMethod(array $parameter)
    {
        $update_payment_method = $this->connect->updatePaymentMethod($parameter);
        if ($update_payment_method[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->url = $this->connect::STAGING_URL;
            return $this->updatePaymentMethod($parameter);
        }
        return $update_payment_method;
    }

    public function getBankAccountIndex(array $parameter = array('account_numbers'=>['0','1']))
    {
        $get_bank_account_index = $this->connect->getBankAccountIndex($parameter);
        if ($get_bank_account_index[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->url = $this->connect::STAGING_URL;
            return $this->getBankAccountIndex($parameter);
        }
        return $get_bank_account_index;
    }

    public function getBankAccount(string $parameter)
    {
        $get_bank_account = $this->connect->getBankAccount($parameter);
        if ($get_bank_account[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->url = $this->connect::STAGING_URL;
            return $this->getBankAccount($parameter);
        }
        return $get_bank_account;
    }

    public function createBankAccount(array $parameter)
    {
        $create_bank_account = $this->connect->createBankAccount($parameter);
        if ($create_bank_account[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->url = $this->connect::STAGING_URL;
            return $this->createBankAccount($parameter);
        }
        return $create_bank_account;
    }

    public function bypassBillplzPage(string $bill)
    {
        $bills = \json_decode($bill, true);
        if ($bills['reference_1_label']!=='Bank Code') {
            return \json_encode($bill);
        }

        $fpxBanks = $this->toArray($this->getFpxBanks());
        if ($fpxBanks[0] !== 200) {
            return \json_encode($bill);
        }

        $found = false;
        foreach ($fpxBanks[1]['banks'] as $bank) {
            if ($bank['name'] === $bills['reference_1']) {
                if ($bank['active']) {
                    $found = true;
                    break;
                }
                return \json_encode($bill);
            }
        }

        if ($found) {
            $bills['url'].='?auto_submit=true';
        }

        return json_encode($bills);
    }

    public function getFpxBanks()
    {
        $get_fpx_banks = $this->connect->getFpxBanks();
        if ($get_fpx_banks[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->url = $this->connect::STAGING_URL;
            return $this->getFpxBanks();
        }
        return $get_fpx_banks;
    }

    public function toArray(array $json)
    {
        return $this->connect->toArray($json);
    }
}

<?php

namespace Billplz;

class API
{
    private $connect;

    public function __construct(\Billplz\Connect $connect)
    {
        $this->connect = $connect;
    }

    public function setConnect(\Billplz\Connect $connect)
    {
        $this->connect = $connect;
    }

    public function getCollectionIndex(array $parameter = array())
    {
        return $this->connect->getCollectionIndex($parameter);
    }

    public function createCollection($parameter)
    {
        if (\is_array($parameter)) {
            return $this->connect->createCollectionArray($parameter);
        }
        if (\is_string($parameter)) {
            return $this->connect->createCollection($parameter);
        }

        throw new Exception('Create Collection Error!');
    }

    public function getCollection($parameter)
    {
        if (\is_array($parameter)) {
            return $this->connect->getCollectionArray($parameter);
        }
        if (\is_string($parameter)) {
            return $this->connect->getCollection($parameter);
        }

        throw new Exception('Get Collection Error!');
    }

    public function createOpenCollection(array $parameter, array $optional = array())
    {
        return $this->connect->createOpenCollection($parameter, $optional);
    }

    public function getOpenCollection($parameter)
    {
        if (\is_array($parameter)) {
            return $this->connect->getOpenCollectionArray($parameter);
        }
        if (\is_string($parameter)) {
            return $this->connect->getOpenCollection($parameter);
        }

        throw new Exception('Get Open Collection Error!');
    }

    public function getOpenCollectionIndex(array $parameter = array())
    {
        return $this->connect->getOpenCollectionIndex($parameter);
    }

    public function createMPICollection($parameter)
    {
        if (\is_array($parameter)) {
            return $this->connect->createMPICollectionArray($parameter);
        }
        if (\is_string($parameter)) {
            return $this->connect->createMPICollection($parameter);
        }

        throw new Exception('Create MPI Collection Error!');
    }

    public function getMPICollection()
    {
        if (\is_array($parameter)) {
            return $this->connect->getMPICollectionArray($parameter);
        }
        if (\is_string($parameter)) {
            return $this->connect->getMPICollection($parameter);
        }

        throw new Exception('Get MPI Collection Error!');
    }

    public function createMPI(array $parameter, array $optional = array())
    {
        return $this->connect->createMPI($parameter, $optional);
    }

    public function getMPI($parameter)
    {
        if (\is_array($parameter)) {
            return $this->connect->getMPIArray($parameter);
        }
        if (\is_string($parameter)) {
            return $this->connect->getMPI($parameter);
        }

        throw new Exception('Get MPI Error!');
    }

    public function deactivateCollection($parameter)
    {
        if (\is_array($parameter)) {
            return $this->connect->deactivateColletionArray($parameter);
        }
        if (\is_string($parameter)) {
            return $this->connect->deactivateCollection($parameter);
        }

        throw new Exception('Deactivate Collection Error!');
    }

    public function activateCollection($parameter)
    {
        if (\is_array($parameter)) {
            return $this->connect->deactivateColletionArray($parameter, 'activate');
        }
        if (\is_string($parameter)) {
            return $this->connect->deactivateCollection($parameter, 'activate');
        }

        throw new Exception('Activate Collection Error!');
    }

    public function createBill(array $parameter, array $optional = array())
    {
        $bill = $this->connect->createBill($parameter, $optional);
        if ($bill[0] === 200) {
            return $bill;
        }
        $collection = $this->toArray($this->getCollectionIndex(array('page'=>'1', 'status'=>'active')));
        if ($collection[0] !== 200) {
            throw new Exception('Failed to create Bill');
        }

        $parameter['collection_id'] = $collection[1]['collections'][0]['id'];

        if (empty($parameter['collection_id'])) {
            $collection = $this->toArray($this->getCollectionIndex(array('page'=>'1', 'status'=>'inactive')));
            $parameter['collection_id'] = $collection[1]['collections'][0]['id'];
        }

        if (empty($parameter['collection_id'])) {
            $collection = $this->toArray($this->createCollection('Payment for Purchase'));
            $parameter['collection_id'] = $collection[1]['id'];
        }

        return $this->connect->createBill($parameter, $optional);
    }

    public function deleteBill($parameter)
    {
        if (\is_array($parameter)) {
            return $this->connect->deleteBillArray($parameter);
        }
        if (\is_string($parameter)) {
            return $this->connect->deleteBill($parameter);
        }

        throw new Exception('Delete Bill Error!');
    }

    public function bankAccountCheck($parameter)
    {
        if (\is_array($parameter)) {
            return $this->connect->bankAccountCheckArray($parameter);
        }
        if (\is_string($parameter)) {
            return $this->connect->bankAccountCheck($parameter);
        }

        throw new Exception('Registration Check by Account Number Error!');
    }

    public function getTransactionIndex(string $id, array $parameter = array('page'=>'1'))
    {
        return $this->connect->getTransactionIndex($id, $parameter);
    }

    public function getPaymentMethodIndex($parameter)
    {
        if (\is_array($parameter)) {
            return $this->connect->getPaymentMethodIndexArray($parameter);
        }
        if (\is_string($parameter)) {
            return $this->connect->getPaymentMethodIndex($parameter);
        }

        throw new Exception('Get Payment Method Index Error!');
    }

    public function updatePaymentMethod(array $parameter)
    {
        return $this->connect->updatePaymentMethod($parameter);
    }

    public function getBankAccountIndex(array $parameter = array('account_numbers'=>['0','1']))
    {
        return $this->connect->getBankAccountIndex($parameter);
    }

    public function getBankAccount($parameter)
    {
        if (\is_array($parameter)) {
            return $this->connect->getBankAccountArray($parameter);
        }
        if (\is_string($parameter)) {
            return $this->connect->getBankAccount($parameter);
        }

        throw new Exception('Get Bank Account Error!');
    }

    public function createBankAccount(array $parameter)
    {
        return $this->connect->createBankAccount($parameter);
    }

    public function bypassBillplzPage(string $bill)
    {
        $bills = json_decode($bill, true);
        if ($bills['reference_1_label']!=='Bank Code') {
            return $bill;
        }

        $fpxBanks = $this->getFpxBanks();
        if ($fpxBanks[0] !== 200) {
            return $bill;
        }

        $bills['url'].='auto_submit=true';

        return json_encode($bills);
    }

    public function getFpxBanks()
    {
        return $this->connect->getFpxBanks();
    }

    public function toArray(array $json)
    {
        return $this->connect->toArray($json);
    }
}

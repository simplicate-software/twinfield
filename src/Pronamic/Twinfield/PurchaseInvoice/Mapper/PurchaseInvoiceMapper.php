<?php

namespace Pronamic\Twinfield\PurchaseInvoice\Mapper;

use Pronamic\Twinfield\Currency;
use Pronamic\Twinfield\DimensionType\LineDimensionType;
use Pronamic\Twinfield\PurchaseInvoice\PurchaseInvoice;
use Pronamic\Twinfield\PurchaseInvoice\PurchaseInvoiceLine;
use Pronamic\Twinfield\PurchaseInvoice\PurchaseInvoiceTransaction;
use Pronamic\Twinfield\User\User;

/**
 * Class PurchaseInvoiceMapper
 *
 * Maps XML-formatted response to PurchaseInvoice objects.
 *
 * @package Pronamic\Twinfield\PurchaseInvoice\Mapper
 * @author Emile Bons <emile@emilebons.nl>
 */
class PurchaseInvoiceMapper
{

    /**
     * @param array $rows
     *
     * @return PurchaseInvoiceTransaction[]
     */
    public static function mapFromTransactions(array $rows = []) {
        $return = [];

        $purchaseInvoiceTransactionTags = [
            'fin.trs.head.yearperiod'      => 'setPeriod',
            'fin.trs.head.number'          => 'setNumber',
            'fin.trs.head.status'          => 'setStatus',
            'fin.trs.head.date'            => 'setDate',
            'fin.trs.line.dim1'            => 'setDim1', // line type="detail" id="2", <dim1 dimtype="PNL">line.dim1</dim1>
            'fin.trs.line.dim2'            => 'setDim2', // line type="detail" id="2", <dim2 dimtype="KPL">line.dim2</dim2>
            'fin.trs.line.dim3'            => 'setDim3', // line type="detail" id="2", <dim3 dimtype="PRJ">line.dim3</dim3>
            'fin.trs.head.curcode'         => 'setCurCode', // header <currency ...>EUR</currency>
            'fin.trs.line.valuesigned'     => 'setValueSigned', // line type="detail" id="2", <value>line.valuesigned</dim3>
            'fin.trs.line.basevaluesigned' => 'setBaseValueSigned', // line type="detail" id="2", <basevalue>line.basevaluesigned</dim3>
            'fin.trs.line.invnumber'       => 'setInvoiceNumber',
            'fin.trs.head.inpdate'         => 'setInputDate',
            'fin.trs.line.description'     => 'setDescription', // line type="detail" id="2", <description>line.description</description>
            'fin.trs.head.browseregime'    => 'setRegime',
        ];

        foreach($rows as $row) {

            $purchaseInvoiceTransaction = new PurchaseInvoiceTransaction();
            foreach($purchaseInvoiceTransactionTags as $key => $method) {
                if(isset($row[$key])) {
                    $purchaseInvoiceTransaction->{$method}($row[$key]);
                }
            }
            if(isset($row['fin.trs.head.curcode'])) {
                $currency = new Currency();
                $currency->setCode($row['fin.trs.head.curcode']);
                $purchaseInvoiceTransaction->setCurrency($currency);
            }

            $return[] = $purchaseInvoiceTransaction;
        }

        return $return;
    }

    /**
     * @param string $response
     *
     * @return PurchaseInvoice
     */
    public static function map($response)
    {
        $responseDOM = new \DOMDocument();
        $responseDOM->loadXML($response);
        $purchaseInvoiceTags = [
            'date'             => 'setDate',
            'duedate'          => 'setDueDate',
            'inputdate'        => 'setInputDate',
            'invoicenumber'    => 'setInvoiceNumber',
            'modificationdate' => 'setModificationDate',
            'number'           => 'setNumber',
            'origin'           => 'setOrigin',
            'originreference'  => 'setOriginReference',
            'period'           => 'setPeriod',
            'regime'           => 'setRegime',
            'freetext1'        => 'setFreetext1',
            'freetext2'        => 'setFreetext2',
            'freetext3'        => 'setFreetext3',
        ];

        $purchaseInvoiceLineTags = [
            'debitcredit'   => 'setDebitCredit',
            'basevalue'     => 'setBaseValue',
            'rate'          => 'setRate',
            'value'         => 'setValue',
            'description'   => 'setDescription',
            'matchstatus'   => 'setMatchStatus',
            'matchlevel'    => 'setMatchLevel',
            'relation'      => 'setRelation',
            'basevalueopen' => 'setBaseValueOpen',
        ];
        $dimTags = [
            'name'          => 'setName',
            'shortname'     => 'setShortName',
            'dimensiontype' => 'setDimensionTypeCode',
        ];
        $purchaseInvoice = new PurchaseInvoice();
        $transactionTag = $responseDOM->getElementsByTagName('transaction')->item(0);
        if(!empty($transactionTag)) {
            $purchaseInvoice->setStatus($transactionTag->attributes->getNamedItem('location')->textContent);
        }

        foreach ($purchaseInvoiceTags as $tag => $method) {
            $_tag = $responseDOM->getElementsByTagName($tag)->item(0);
            if(isset($_tag) && isset($_tag->textContent)) {
                $purchaseInvoice->$method($_tag->textContent);
            }
        }

        $currencyTag = $responseDOM->getElementsByTagName('currency')->item(0);
        if(isset($currencyTag)) {
            $currency = new Currency();
            $currency->setCode($currencyTag->textContent);
            $currency->setName($currencyTag->attributes->getNamedItem('name')->textContent);
            $currency->setShortName($currencyTag->attributes->getNamedItem('shortname')->textContent);
            $purchaseInvoice->setCurrency($currency);
        }

        $userTag = $responseDOM->getElementsByTagName('user')->item(0);
        if(isset($userTag)) {
            $user = new User();
            $user->setCode($userTag->textContent);
            $user->setName($userTag->attributes->getNamedItem('name')->textContent);
            $user->setShortName($userTag->attributes->getNamedItem('shortname')->textContent);
            $purchaseInvoice->setUser($user);
        }

        $linesTag = $responseDOM->getElementsByTagName('lines')->item(0);
        /** @var \DOMElement $lineTag */
        foreach($linesTag->getElementsByTagName('line') as $lineTag) {
            $line = new PurchaseInvoiceLine();
            $line->readOnly = true;

            if ($lineTag->attributes->getNamedItem('id')) {
                $line->setId($lineTag->attributes->getNamedItem('id')->textContent);
            }

            if ($lineTag->attributes->getNamedItem('type')) {
                $line->setType($lineTag->attributes->getNamedItem('type')->textContent);
            }

            foreach($purchaseInvoiceLineTags as $tag => $method) {
                $_tag = $lineTag->getElementsByTagName($tag)->item(0);
                if(isset($_tag) && isset($_tag->textContent)) {
                    $line->{$method}($_tag->textContent);
                }
            }
            foreach(['dim1', 'dim2', 'dim3'] as $dimTag) {
                $lineDimTag = $lineTag->getElementsByTagName($dimTag)->item(0);
                if(!isset($lineDimTag) || !isset($lineDimTag->textContent)) {
                    continue;
                }
                $dim = new LineDimensionType();
                foreach($dimTags as $tag => $method) {
                    $dim->setCode($lineDimTag->textContent);
                    $_attr = $lineDimTag->attributes->getNamedItem($tag);
                    if(isset($_attr)) {
                        $dim->{$method}($_attr->textContent);
                    }
                }
                $_method = "set" . ucfirst($dimTag);
                $line->{$_method}($dim);
            }
            $purchaseInvoice->addLine($line);
        }

        return $purchaseInvoice;
    }
}
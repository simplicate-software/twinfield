<?php

namespace Pronamic\Twinfield\PurchaseInvoice;

use Pronamic\Twinfield\Factory\ProcessXmlFactory;
use Pronamic\Twinfield\PurchaseInvoice\Mapper\PurchaseInvoiceMapper;

/**
 * InvoiceFactory
 *
 * A facade factory to make interaction with the twinfield service easier
 * when trying to retrieve or set information about Purchase Invoices.
 *
 * Each method has detailed explanation over what is required, and what
 * happens.
 *
 * If you require more complex interactions or a heavier amount of control
 * over the requests to/from then look inside the methods or see the
 * advanced guide details the required usages.
 *
 * @package Pronamic\Twinfield
 * @subpackage PurchaseInvoice
 * @author Emile Bons <emile@emilebons.nl>
 */
class PurchaseInvoiceFactory extends ProcessXmlFactory {

    /**
     * @param string                            $office
     * @param null|PurchaseInvoiceFactoryParams $factoryParams
     *
     * @return false|PurchaseInvoiceTransaction[]
     */
    public function listAll($office, $factoryParams = null) {
        if($this->getLogin()->process()) {
            $browseCode = self::BROWSE_CODE_TRANSACTION_LIST;
            $browseData = $this->browseData($office, $browseCode);
            $xmlBrowseDefinition = simplexml_load_string($browseData->ProcessXmlStringResult);

            // Only show transactions that have a project linked to them
            $onlyWithProjects = true;
            $sort              = [];
            $filterStatus      = false;
            if($factoryParams instanceof PurchaseInvoiceFactoryParams) {
                $startYearPeriod        = $factoryParams->getStartYearPeriod();
                $endYearPeriod          = $factoryParams->getEndYearPeriod();
                $onlyWithProjects       = $factoryParams->isOnlyWithProjects();
                $sort                   = $factoryParams->getSort();
                $filterStatus           = $factoryParams->getFilterStatus();
                $startDate              = $factoryParams->getStartDate();
                $endDate                = $factoryParams->getEndDate();
            }
            $startYearPeriod = empty($startYearPeriod) ? date("Y") - 2 . "/01" : $startYearPeriod;
            $endYearPeriod =   empty($endYearPeriod)   ? date("Y") + 2 . "/12" : $endYearPeriod;

            foreach($xmlBrowseDefinition->columns->column as $column) {
                switch($column->field) {
                    case 'fin.trs.head.yearperiod':
                        $column->operator     = 'equal';
                        if(($startYearPeriod && !$endYearPeriod) || (!$startYearPeriod && $endYearPeriod)) {
                            $column->from     = ($startYearPeriod ? $startYearPeriod : $endYearPeriod);
                        } elseif($startYearPeriod && $endYearPeriod) {
                            $column->operator = 'between';
                            $column->from     = $startYearPeriod;
                            $column->to       = $endYearPeriod;
                        } else {
                            $column->operator = 'none';
                        }
                        break;
                    case 'fin.trs.head.code':
                        $column->operator     = 'equal';
                        $column->from         = 'INK';
                        break;
                    case 'fin.trs.head.status':
                        if($filterStatus) {
                            $column->operator = 'equal';
                            $column->from     = $filterStatus;
                        }
                        break;
                    case 'fin.trs.head.date':
                        if (isset($startDate) || isset($endDate)) {
                            $column->operator = 'between';
                            $column->from     = isset($startDate) ? $startDate : "";
                            $column->to       = isset($endDate) ? $endDate : "";
                        } else {
                            $column->operator = 'none';
                        }
                        break;
                    case 'fin.trs.line.dim3':
                        if($onlyWithProjects) {
                            $column->operator = 'between';
                            $column->from     = '~';                // tilde
                            $column->to       = 'ZZZZZZZZZZZZZZZZ'; // 16 x Z
                        }
                        break;
                }
            }

            // @TODO: Find a way to make ->columns->appendChild() not append an empty element
            $officeColumn = "<column><field>fin.trs.head.office</field><operator>equal</operator><from>{$office}</from></column>";
            $xmlString = $xmlBrowseDefinition->columns->asXML();
            $xmlString = str_replace("</column></columns>", "</column>{$officeColumn}</columns>", $xmlString);

            // @TODO: Find a way to make sorts go through SimpleXMLElement appending
            if(!empty($sort)) {
                $prepend = '<sort>';
                foreach($sort as $fieldSort) {
                    $prepend .= '<field order="' . $fieldSort['order'] . '">' . $fieldSort['field'] . '</field>';
                }
                $prepend .= '</sort>';

                /**
                 * To be sent XML starts with (<columns code="200">)<column ...
                 *  Replace that with (<columns code="200">)<sort>...
                 */
                $xmlStartsWith = "<columns code=\"{$browseCode}\">";
                $xmlString = str_replace($xmlStartsWith, "{$xmlStartsWith}{$prepend}", $xmlString);
            }

            $result = $this->processBrowseData($xmlString);
            $transactions = PurchaseInvoiceMapper::mapFromTransactions($result['rows']);

            return $transactions;
        }

        return false;
    }

    /**
     * Returns a specific purchase invoice by the code, invoice
     * number given.
     *
     * If the response was successful it will return a
     * \Pronamic\Twinfield\PurchaseInvoice\PurchaseInvoice instance, made by the
     * \Pronamic\Twinfield\PurchaseInvoice\Mapper\PurchaseInvoiceMapper class.
     *
     * @access public
     * @param string $code
     * @param int $invoiceNumber
     * @param string $office
     * @return PurchaseInvoice
     */
    public function get($code, $invoiceNumber, $office)
    {
        if ($this->getLogin()->process()) {
            $response = $this->readTransactionData($office, $code, $invoiceNumber);
            return PurchaseInvoiceMapper::map($response->ProcessXmlStringResult);
        }
    }
}

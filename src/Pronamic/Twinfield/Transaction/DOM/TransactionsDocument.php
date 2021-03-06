<?php

namespace Pronamic\Twinfield\Transaction\DOM;

use Pronamic\Twinfield\Transaction\Transaction;

/**
 * TransactionsDocument class.
 *
 * @author Dylan Schoenmakers <dylan@opifer.nl>
 */
class TransactionsDocument extends \DOMDocument
{
    /**
     * Holds the <transactions> element
     * that all additional elements should be a child of.
     *
     * @var \DOMElement
     */
    private $transactionsElement;

    /**
     * Creates the <transasctions> element and adds it to the property
     * transactionsElement.
     */
    public function __construct()
    {
        parent::__construct();

        $this->transactionsElement = $this->createElement('transactions');
        $this->appendChild($this->transactionsElement);
    }

    /**
     * Turns a passed Transaction class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the transaction
     * to this DOMDocument instance for submission usage.
     *
     * @param \Pronamic\Twinfield\Transaction\Transaction $transaction
     */
    public function addTransaction(Transaction $transaction)
    {
        // Transaction
        $transactionElement = $this->createElement('transaction');
        $transactionElement->setAttribute('destiny', $transaction->getDestiny());
        $this->transactionsElement->appendChild($transactionElement);

        // Header
        $headerElement = $this->createElement('header');
        $transactionElement->appendChild($headerElement);

        $officeElement = $this->createElement('office', $transaction->getOffice());
        $headerElement->appendChild($officeElement);
        $codeElement = $this->createElement('code', $transaction->getCode());
        $headerElement->appendChild($codeElement);
        $dateElement = $this->createElement('date', $transaction->getDate());
        $headerElement->appendChild($dateElement);

        if ($transaction->getPeriod() !== null) {
            $periodElement = $this->createElement('period', $transaction->getPeriod());
            $headerElement->appendChild($periodElement);
        }
        if ($transaction->getInvoiceNumber() !== null) {
            $invoiceNumberElement = $this->createElement('invoicenumber', $transaction->getInvoiceNumber());
            $invoiceNumberElement->setAttribute('raisewarning', 'false'); // overwrite
            $headerElement->appendChild($invoiceNumberElement);
        }
        if ($transaction->getDueDate() !== null) {
            $dueDateElement = $this->createElement('duedate', $transaction->getDueDate());
            $headerElement->appendChild($dueDateElement);
        }
        if ($transaction->getFreetext1() !== null) {
            $freetext1Element = $this->createElement('freetext1', $transaction->getFreetext1());
            $headerElement->appendChild($freetext1Element);
        }
        if ($transaction->getFreetext2() !== null) {
            $freetext2Element = $this->createElement('freetext2', $transaction->getFreetext2());
            $headerElement->appendChild($freetext2Element);
        }
        if ($transaction->getFreetext3() !== null) {
            $freetext3Element = $this->createElement('freetext3', $transaction->getFreetext3());
            $headerElement->appendChild($freetext3Element);
        }

        $linesElement = $this->createElement('lines');
        $transactionElement->appendChild($linesElement);

        // Lines
        foreach ($transaction->getLines() as $transactionLine) { /* @var $transactionLine \Pronamic\Twinfield\Transaction\TransactionLine */
            $lineElement = $this->createElement('line');
            $lineElement->setAttribute('type', $transactionLine->getType());
            $lineElement->setAttribute('id', $transactionLine->getID());
            $linesElement->appendChild($lineElement);

            $dim1Element = $this->createElement('dim1', $transactionLine->getDim1());
            $dim2Element = $this->createElement('dim2', $transactionLine->getDim2());
            $value = $transactionLine->getValue();
            $value = number_format($value, 2, '.', '');
            $valueElement = $this->createElement('value', $value);
            $debitCreditElement = $this->createElement('debitcredit', $transactionLine->getDebitCredit());

            if ($transactionLine->getType() != 'total' && $transactionLine->getVatCode() !== null) {
                $vatCodeElement = $this->createElement('vatcode', $transactionLine->getVatCode());
                $lineElement->appendChild($vatCodeElement);
            }

            $descriptionNode = $this->createTextNode(substr($transactionLine->getDescription(), 0, 40));
            $descriptionElement = $this->createElement('description');
            $descriptionElement->appendChild($descriptionNode);

            $lineElement->appendChild($dim1Element);
            $lineElement->appendChild($dim2Element);
            $lineElement->appendChild($valueElement);
            $lineElement->appendChild($debitCreditElement);

            $invoicenumber = $transactionLine->getInvoicenumber();
            if (!empty($invoicenumber)) {
                $invoicenumberElement = $this->createElement('invoicenumber', $invoicenumber);
                $lineElement->appendChild($invoicenumberElement);
            }

            $performanceType = $transactionLine->getPerformanceType();
            if (!empty($performanceType)) {
                $perfElement = $this->createElement('performancetype', $performanceType);
                $lineElement->appendChild($perfElement);
            }

            $currencyDate = $transactionLine->getCurrencyDate();
            if (!empty($currencyDate)) {
                $currencyDateElement = $this->createElement('currencydate', $currencyDate);
                $lineElement->appendChild($currencyDateElement);
            }

            $vatValue = $transactionLine->getVatValue();
            if (!empty($vatValue)) {
                $vatElement = $this->createElement('vatvalue', $vatValue);
                $lineElement->appendChild($vatElement);
            }

            $lineElement->appendChild($descriptionElement);
        }
    }
}

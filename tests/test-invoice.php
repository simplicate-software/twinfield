<?php

use Pronamic\Twinfield\Match\DOM\MatchDocument;
use Pronamic\Twinfield\Match\Match;
use Pronamic\Twinfield\Match\MatchLine;

class InvoiceTest extends PHPUnit_Framework_TestCase {
    
    public function testInvoiceMapperRespondsWithInvoiceEntity() {
        
        $responseXML = '<?xml version="1.0"?>
            <salesinvoice result="1">
                <header>
                    <office>11024</office>
                    <invoicetype>FACTUUR</invoicetype>
                    <invoicenumber>5</invoicenumber>
                    <invoicedate>20120831</invoicedate>
                    <duedate>20120930</duedate>
                    <bank>BNK</bank>
                    <invoiceaddressnumber>1</invoiceaddressnumber>
                    <deliveraddressnumber>1</deliveraddressnumber>
                    <customer>1000</customer>
                    <period>2012/8</period>
                    <currency>EUR</currency>
                    <status>concept</status>
                    <paymentmethod>cash</paymentmethod>
                    <headertext/>
                    <footertext/>
                </header>
                <lines>
                    <line id="1">
                        <article>4</article>
                        <subarticle>118</subarticle>
                        <quantity>1</quantity>
                        <units>1</units>
                        <allowdiscountorpremium>true</allowdiscountorpremium>
                        <description>CoalesceFunctioningOnImpatienceTShirt</description>
                        <valueexcl>15.00</valueexcl>
                        <vatvalue>0.00</vatvalue>
                        <valueinc>15.00</valueinc>
                        <unitspriceexcl>15.00</unitspriceexcl>
                        <freetext1/>
                        <freetext2/>
                        <freetext3/>
                        <dim1>8020</dim1>
                        <vatcode name="BTW 0%" shortname="V 0%" type="sales">VN</vatcode>
                    </line>
                </lines>
                <vatlines>
                    <vatline>
                        <vatcode name="BTW 0%">VN</vatcode>
                        <vatvalue>0.00</vatvalue>
                        <performancetype/><performancedate/>
                    </vatline>
                </vatlines>
                <totals>
                    <valueinc>15.00</valueinc>
                    <valueexcl>15.00</valueexcl>
                </totals>
            </salesinvoice>';
        
        $responseDocument = new DOMDocument();
        $responseDocument->loadXML($responseXML);
        
        $invoice_response = new \Pronamic\Twinfield\Response\Response($responseDocument);
        $invoice = Pronamic\Twinfield\Invoice\Mapper\InvoiceMapper::map($invoice_response);
        
        $this->assertTrue($invoice instanceof \Pronamic\Twinfield\Invoice\Invoice);
        
    }

    /**
     * @test
     */
    public function matchRecordGeneratesProperXml() {
        $match = new Match();
        $match->setOffice('001');
        $match->setCode('170'); // fixed
        $match->setDate('20160908');

        $i = 0;
        $line = new MatchLine();
        $line->setTransCode('INCASSO-OW');
        $line->setTransNumber('1000'); // Twinfield generated ID for memorial transaction
        $line->setTransLine(2);
        $match->addLine($line);

        $line = new MatchLine();
        $line->setTransCode('VRK');
        $line->setTransNumber('20160001'); // Twinfield generated ID for invoice transaction
        $line->setTransLine(1);
        $line->setMatchValue(100);
        $match->addLine($line);

        $matchDocument = new MatchDocument();
        $matchDocument->addMatch($match);
        $generatedXml = $matchDocument;
        $expectedXml = new DOMDocument();
        $expectedXml->loadXml(
'<match>
   <set>
      <office>001</office>
      <matchcode>170</matchcode>
      <matchdate>20160908</matchdate>
      <lines>
         <line>
            <transcode>INCASSO-OW</transcode>
            <transnumber>1000</transnumber>
            <transline>2</transline>
         </line>
         <line>
            <transcode>VRK</transcode>
            <transnumber>20160001</transnumber>
            <transline>1</transline>
            <matchvalue>100.00</matchvalue>
         </line>
      </lines>
   </set>
</match>');
        $this->assertEqualXMLStructure($expectedXml->documentElement, $generatedXml->documentElement);
        return $match;
    }
}
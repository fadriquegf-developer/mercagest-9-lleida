<?php

namespace Database\Seeders;

use App\Models\BicConversion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BicCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bic_codes = [
            
            [
                "bank_code" => '2100',
                "bank_title" => 'CAIXABANK',
                "bank_bic" => 'CAIXESBBXXX',
            ],
            [
                "bank_code" => '0049',
                "bank_title" => 'BANCO SANTANDER, S.A.',
                "bank_bic" => 'BSCHESMMXXX',
            ],
            [
                "bank_code" => '0019',
                "bank_title" => 'DEUTSCHE BANK, S.A.E.',
                "bank_bic" => 'DEUTESBBASS',
            ],
            [
                "bank_code" => '0065',
                "bank_title" => 'BARCLAYS BANK, S.A.',
                "bank_bic" => 'BARCESMMXXX',
            ],
            [
                "bank_code" => '0075',
                "bank_title" => 'BANCO POPULAR ESPAÑOL, S.A.',
                "bank_bic" => 'POPUESMMXXX',
            ],
            [
                "bank_code" => '0081',
                "bank_title" => 'BANCO DE SABADELL, S.A.',
                "bank_bic" => 'BSABESBBXXX',
            ],
            [
                "bank_code" => '0133',
                "bank_title" => 'NUEVO MICRO BANK, S.A.',
                "bank_bic" => 'MIKBESB1XXX',
            ],
            [
                "bank_code" => '0128',
                "bank_title" => 'BANKINTER',
                "bank_bic" => 'BKBKESMMXXX',
            ],
            [
                "bank_code" => '0182',
                "bank_title" => 'BANCO BILBAO VIZCAYA ARGENTARIA, S.A.',
                "bank_bic" => 'BBVAESMMXXX',
            ],
            [
                "bank_code" => '0186',
                "bank_title" => 'BANCO MEDIOLANUM, S.A.',
                "bank_bic" => 'BFIVESBBXXX',
            ],
            [
                "bank_code" => '0216',
                "bank_title" => 'TARGOBANK, S.A.',
                "bank_bic" => 'POHIESMMXXX',
            ],
            [
                "bank_code" => '0227',
                "bank_title" => 'UNOE BANK, S.A.',
                "bank_bic" => 'UNOEESM1XXX',
            ],
            [
                "bank_code" => '0487',
                "bank_title" => 'BANCO MARE NOSTRUM, S.A.',
                "bank_bic" => 'GBMNESMMXXX',
            ],
            [
                "bank_code" => '1465',
                "bank_title" => 'ING DIRECT',
                "bank_bic" => 'INGDESMMXXX',
            ],
            [
                "bank_code" => '1491',
                "bank_title" => 'TRIODOS BANK, N.V., S.E.',
                "bank_bic" => 'TRIOESMMXXX',
            ],
            [
                "bank_code" => '2013',
                "bank_title" => 'CATALUNYA BANC',
                "bank_bic" => 'CESCESBBXXX',
            ],
            [
                "bank_code" => '2038',
                "bank_title" => 'BANKIA, S.A.',
                "bank_bic" => 'CAHMESMMXXX',
            ],
            [
                "bank_code" => '2048',
                "bank_title" => 'LIBERBANK, S.A.',
                "bank_bic" => 'CECAESMM048',
            ],
            [
                "bank_code" => '2080',
                "bank_title" => 'NCG BANCO, S.A.',
                "bank_bic" => 'CAGLESMMVIG',
            ],
            [
                "bank_code" => '2085',
                "bank_title" => 'IBERCAJA BANCO, S.A.',
                "bank_bic" => 'CAZRES2ZXXX',
            ],
            [
                "bank_code" => '2086',
                "bank_title" => 'BANCO GRUPO CAJATRES, S.A.',
                "bank_bic" => 'CECAESMM086',
            ],
            [
                "bank_code" => '3058',
                "bank_title" => 'CAJAMAR',
                "bank_bic" => 'CCRIES2AXXX',
            ],
            [
                "bank_code" => '3060',
                "bank_title" => 'CAJAVIVA',
                "bank_bic" => 'BCOEESMM060',
            ],
            [
                "bank_code" => '3140',
                "bank_title" => 'CAJA RURAL DE GUISSONA S.C. DE CRÉDITO',
                "bank_bic" => 'BCOEESMM140',
            ],
            [
                "bank_code" => '3174',
                "bank_title" => 'CAIXA VINAROS',
                "bank_bic" => 'BCOEESMM174',
            ],
            [
                "bank_code" => '3191',
                "bank_title" => 'BANTIERRA',
                "bank_bic" => 'BCOEESMM191',
            ],
            [
                "bank_code" => '0156',
                "bank_title" => 'THE ROYAL BANK OF SCOTLAND PLC',
                "bank_bic" => 'ABNAESMMXXX',
            ],
            [
                "bank_code" => '3524',
                "bank_title" => 'AHORRO CORPORACIÓN FINANCIERA, S.A.',
                "bank_bic" => 'AHCFESMMXXX',
            ],
            [
                "bank_code" => '0188',
                "bank_title" => 'BANCO ALCALA, S.A.',
                "bank_bic" => 'ALCLESMMXXX',
            ],
            [
                "bank_code" => '0136',
                "bank_title" => 'ARESBANK, S.A.',
                "bank_bic" => 'AREBESMMXXX',
            ],
            [
                "bank_code" => '0078',
                "bank_title" => 'BANCA PUEYO, S.A.',
                "bank_bic" => 'BAPUES22XXX',
            ],
            [
                "bank_code" => '2095',
                "bank_title" => 'KUTXABANK, S.A.',
                "bank_bic" => 'BASKES2BXXX',
            ],
            [
                "bank_code" => '0190',
                "bank_title" => 'BANCO BPI, S.A. SUCURSAL EN ESPAÑA',
                "bank_bic" => 'BBPIESMMXXX',
            ],
            [
                "bank_code" => '0168',
                "bank_title" => 'ING BELGIUM, S.A. SUCURSAL EN ESPAÑA',
                "bank_bic" => 'BBRUESMXXXX',
            ],
            [
                "bank_code" => '3081',
                "bank_title" => 'CAJA RURAL DE CASTILLA LA MANCHA, S.C.C.',
                "bank_bic" => 'BCOEESMM081',
            ],
            [
                "bank_code" => '0198',
                "bank_title" => 'BANCO COOPERATIVO ESPAÑOL, S.A.',
                "bank_bic" => 'BCOEESMMXXX',
            ],
            [
                "bank_code" => '0131',
                "bank_title" => 'BANCO ESPIRITO SANTO, S.A.',
                "bank_bic" => 'BESMESMMXXX',
            ],
            [
                "bank_code" => '0030',
                "bank_title" => 'BANCO SANTANDER, S.A.',
                "bank_bic" => 'BSCHESMMXXX',
            ],
            [
                "bank_code" => '0239',
                "bank_title" => 'EVO BANCO S.A.',
                "bank_bic" => 'EVOBESMMXXX',
            ],
            [
                "bank_code" => '0059',
                "bank_title" => 'BANCO DE MADRID, S.A.',
                "bank_bic" => 'MADRESMMXXX',
            ],
            [
                "bank_code" => '2103',
                "bank_title" => 'UNICAJA BANCO, S.A.',
                "bank_bic" => 'UCJAES2MXXX',
            ],
            [
                "bank_code" => '0237',
                "bank_title" => 'CAJASUR BANCO, S.A.',
                "bank_bic" => 'CSURES2CXXX',
            ],
            [
                "bank_code" => '0238',
                "bank_title" => 'BANCO PASTOR, S.A.',
                "bank_bic" => 'PSTRESMMXXX',
            ],
            [
                "bank_code" => '0236',
                "bank_title" => 'SABADELL SOLBANK, S.A.',
                "bank_bic" => 'LOYIESMMXXX',
            ],
            [
                "bank_code" => '1474',
                "bank_title" => 'CITIBANK INTERNATIONAL PLC, SUC.ESPAÑA',
                "bank_bic" => 'CITIESMXXXX',
            ],
            [
                "bank_code" => '0122',
                "bank_title" => 'CITIBANK ESPAÑA, S.A.',
                "bank_bic" => 'CITIES2XXXX',
            ],
            [
                "bank_code" => '0061',
                "bank_title" => 'BANCA MARCH, S.A.',
                "bank_bic" => 'BMARES2MXXX',
            ],
            [
                "bank_code" => '1460',
                "bank_title" => 'CREDIT SUISSE AG, SUCURSAL EN ESPAÑA',
                "bank_bic" => 'CRESESMMXXX',
            ],
            [
                "bank_code" => '0233',
                "bank_title" => 'POPULAR BANCA PRIVADA, S.A.',
                "bank_bic" => 'POPIESMMXXX',
            ],
            [
                "bank_code" => '0154',
                "bank_title" => 'CREDIT AGRICOLE COR. AND INVESTMENT BANK',
                "bank_bic" => 'BSUIESMMXXX',
            ],
            [
                "bank_code" => '2056',
                "bank_title" => "COLONYA - CAIXA D'ESTALVIS DE POLLENSA",
                "bank_bic" => 'CECAESMM056',
            ],
            [
                "bank_code" => '0058',
                "bank_title" => 'BNP PARIBAS ESPAÑA, S.A.',
                "bank_bic" => 'BNPAESMMXXX',
            ],
            [
                "bank_code" => '0046',
                "bank_title" => 'BANCO GALLEGO, S.A.',
                "bank_bic" => 'GALEES2GXXX',
            ],
            [
                "bank_code" => '2000',
                "bank_title" => 'CECABANK, S.A.',
                "bank_bic" => 'CECAESMMXXX',
            ],
            [
                "bank_code" => '2104',
                "bank_title" => 'BANCO CEISS',
                "bank_bic" => 'CSPAES2L108',
            ],
            [
                "bank_code" => '2108',
                "bank_title" => 'BANCO CEISS',
                "bank_bic" => 'CSPAES2L108',
            ],
            [
                "bank_code" => '0073',
                "bank_title" => 'OPEN BANK, S.A.',
                "bank_bic" => 'OPENESMMXXX',
            ],
            [
                "bank_code" => '3183',
                "bank_title" => 'CAJA DE ARQUITECTOS S.C.C.',
                "bank_bic" => 'CASDESBBXXX',
            ],
            [
                "bank_code" => '3035',
                "bank_title" => 'CAJA LABORAL POPULAR C.C.',
                "bank_bic" => 'CLPEES2MXXX',
            ],
            [
                "bank_code" => '3025',
                "bank_title" => 'CAIXA CREDIT DELS ENGINYERS SDAD.COOP.C.',
                "bank_bic" => 'CDENESBBXXX',
            ],
            [
                "bank_code" => '3008',
                "bank_title" => 'CAJA RURAL DE NAVARRA',
                "bank_bic" => 'BCOEESMM008',
            ],
            [
                "bank_code" => '0130',
                "bank_title" => 'BANCO CAIXA GERAL, S.A.',
                "bank_bic" => 'CGDIESMMXXX',
            ],
            [
                "bank_code" => '1550',
                "bank_title" => 'BANCA POPOLARE ETICA SOCIEDAD PER AZIONI',
                "bank_bic" => 'ETICAS21XXX',
            ],
            [
                "bank_code" => '3111',
                "bank_title" => 'CAIXA RURAL LA VALL SAN ISIDRO',
                "bank_bic" => 'BCOEESMM111',
            ],
            [
                "bank_code" => '1490',
                "bank_title" => 'SELF TRADE BANK',
                "bank_bic" => 'SELFESMMXXX',
            ],
            [
                "bank_code" => '3190',
                "bank_title" => 'GLOBALCAJA',
                "bank_bic" => 'BCOEESMM190',
            ],
            [
                "bank_code" => '3187',
                "bank_title" => 'CAJA RURAL DEL SUR',
                "bank_bic" => 'BCOEESMM187',
            ],
            [
                "bank_code" => '6713',
                "bank_title" => 'PREPAID FINANCIAL SERVICES',
                "bank_bic" => 'PFSSESM1XXX',
            ],
            [
                "bank_code" => '3159',
                "bank_title" => 'CAIXA POPULAR',
                "bank_bic" => 'BCOEESMM159',
            ]
        ];

        foreach ($bic_codes as $bic_code){
            BicConversion::create($bic_code);
        }
    }
}

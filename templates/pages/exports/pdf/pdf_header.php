<?php

require 'pdf/fpdf.php';

class PDF extends FPDF
{

    const DPI = 100;
    const MM_IN_INCH = 25.4;
    const A4_HEIGHT = 57;
    const A4_WIDTH = 210;
    const MAX_WIDTH = 120;
    const MAX_HEIGHT = 100;

    /* Start of Printing lib functions */

    protected $javascript;
    protected $n_js;

    function IncludeJS($script, $isUTF8 = false)
    {
        if (!$isUTF8)
            $script = utf8_encode($script);
        $this->javascript = $script;
    }

    function _putjavascript()
    {
        $this->_newobj();
        $this->n_js = $this->n;
        $this->_put('<<');
        $this->_put('/Names [(EmbeddedJS) ' . ($this->n + 1) . ' 0 R]');
        $this->_put('>>');
        $this->_put('endobj');
        $this->_newobj();
        $this->_put('<<');
        $this->_put('/S /JavaScript');
        $this->_put('/JS ' . $this->_textstring($this->javascript));
        $this->_put('>>');
        $this->_put('endobj');
    }

    function AutoPrint($printer = '')
    {
        // Open the print dialog
        if ($printer) {
            $printer = str_replace('\\', '\\\\', $printer);
            $script = "var pp = getPrintParams();";
            $script .= "pp.interactive = pp.constants.interactionLevel.full;";
            $script .= "pp.printerName = '$printer'";
            $script .= "print(pp);";
        } else
            $script = 'print(true);';
        $this->IncludeJS($script);
    }

    function _putresources()
    {
        parent::_putresources();
        if (!empty($this->javascript)) {
            $this->_putjavascript();
        }
    }

    function _putcatalog()
    {
        parent::_putcatalog();
        if (!empty($this->javascript)) {
            $this->_put('/Names <</JavaScript ' . ($this->n_js) . ' 0 R>>');
        }
    }

    /* End of Printing lib functions */

    function pixelsToMM($val)
    {
        return $val * self::MM_IN_INCH / self::DPI;
    }

    function resizeToFit($imgFilename)
    {
        list($width, $height) = getimagesize($imgFilename);
        $widthScale = self::MAX_WIDTH / $width;
        $heightScale = self::MAX_HEIGHT / $height;
        $scale = min($widthScale, $heightScale);
        return array(
            round($this->pixelsToMM($scale * $width)),
            round($this->pixelsToMM($scale * $height))
        );
    }

    function centreImage($img)
    {
        list($width, $height) = $this->resizeToFit($img);
        // you will probably want to swap the width/height
        // around depending on the page's orientation

        $this->Image(
            $img,
            (self::A4_WIDTH - $width) / 2,
            (self::A4_HEIGHT - $height) / 2,
            $width,
            $height
        );
    }

    function Header()
    {
    }

    function Footer()
    {
        global $no_footer;
        if (!$no_footer) {
            $this->SetY(-15);
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}  [' . "Printed  on " . date("l jS \of F Y h:i:s A") . " ]", 0, 0, 'R');
            $this->Ln(5);
        } else {
            $this->SetY(-10);
            $this->SetFont('Courier', 'I', 2);
            $this->Cell(0, 10, " Printed  on " . date("l jS \of F Y"), 0, 0, 'R');
            $this->Ln(5);
        }
    }

    function createHeader($xAxis)
    {
        $this->SetTextColor(0, 0, 225);
        $this->SetFont("Arial", "B", 16);
        //        $queryData = DB::getInstance()->query("SELECT * FROM hotel_setting LIMIT 1");
        //        foreach ($queryData->results() as $hotel_data) {
        //            $xAxis = ($xAxis == "") ? 185 : $xAxis;
        //            try {
        //                $this->Image("images/logo/{$hotel_data->Logo}", $xAxis, 10, 20, 20);
        //            } catch (Exception $ex) {
        //                
        //            }
        //            $this->Cell(0, 5, strtoupper($hotel_data->Hotel_Name), 0, 1, "L");
        //            $this->SetFont("Arial", "B", 10);
        //            $this->Cell(0, 5, "Contact: $hotel_data->Telephone", 0, 1, "L");
        //            $this->Cell(0, 5, "Address: $hotel_data->Address", 0, 1, "L");
        //            $email_data = ($hotel_data->Email != "") ? " Email: $hotel_data->Email" : "";
        //            $fax_data = ($hotel_data->Fax != "") ? " Fax: $hotel_data->Fax" : "";
        //            if ($email_data || $fax_data) {
        //                $this->Cell(0, 5, "$email_data $fax_data", 0, 1, "L");
        //            }
        //        }
    }

    function createReceiptHeader($organisation_id)
    {
        $this->SetFont("Courier", "B", 5);
        try {
            $this->Image(COMPANY_LOGO, 14, 1, 8, 4);
            $this->Cell(0, 3.5, "", 0, 1);
        } catch (Exception $ex) {
        }
        $this->Cell(0, 2.5, strtoupper(COMPANY_NAME), 0, 1, "C");
        $this->SetFont("Courier", "B", 3);
        $this->Cell(0, 2, "LOCATION: " . COMPANY_LOCATION, 0, 1, "C");
    }

    function SetDash($black = null, $white = null)
    {
        if ($black !== null)
            $s = sprintf('[%.3F %.3F] 0 d', $black * $this->k, $white * $this->k);
        else
            $s = '[] 0 d';
        $this->_out($s);
    }

    function Code39($xpos, $ypos, $code, $text, $baseline = 0.5, $height = 5)
    {

        $wide = $baseline;
        $narrow = $baseline / 3;
        $gap = $narrow;

        $barChar['0'] = 'nnnwwnwnn';
        $barChar['1'] = 'wnnwnnnnw';
        $barChar['2'] = 'nnwwnnnnw';
        $barChar['3'] = 'wnwwnnnnn';
        $barChar['4'] = 'nnnwwnnnw';
        $barChar['5'] = 'wnnwwnnnn';
        $barChar['6'] = 'nnwwwnnnn';
        $barChar['7'] = 'nnnwnnwnw';
        $barChar['8'] = 'wnnwnnwnn';
        $barChar['9'] = 'nnwwnnwnn';
        $barChar['A'] = 'wnnnnwnnw';
        $barChar['B'] = 'nnwnnwnnw';
        $barChar['C'] = 'wnwnnwnnn';
        $barChar['D'] = 'nnnnwwnnw';
        $barChar['E'] = 'wnnnwwnnn';
        $barChar['F'] = 'nnwnwwnnn';
        $barChar['G'] = 'nnnnnwwnw';
        $barChar['H'] = 'wnnnnwwnn';
        $barChar['I'] = 'nnwnnwwnn';
        $barChar['J'] = 'nnnnwwwnn';
        $barChar['K'] = 'wnnnnnnww';
        $barChar['L'] = 'nnwnnnnww';
        $barChar['M'] = 'wnwnnnnwn';
        $barChar['N'] = 'nnnnwnnww';
        $barChar['O'] = 'wnnnwnnwn';
        $barChar['P'] = 'nnwnwnnwn';
        $barChar['Q'] = 'nnnnnnwww';
        $barChar['R'] = 'wnnnnnwwn';
        $barChar['S'] = 'nnwnnnwwn';
        $barChar['T'] = 'nnnnwnwwn';
        $barChar['U'] = 'wwnnnnnnw';
        $barChar['V'] = 'nwwnnnnnw';
        $barChar['W'] = 'wwwnnnnnn';
        $barChar['X'] = 'nwnnwnnnw';
        $barChar['Y'] = 'wwnnwnnnn';
        $barChar['Z'] = 'nwwnwnnnn';
        $barChar['-'] = 'nwnnnnwnw';
        $barChar['.'] = 'wwnnnnwnn';
        $barChar[' '] = 'nwwnnnwnn';
        $barChar['*'] = 'nwnnwnwnn';
        $barChar['$'] = 'nwnwnwnnn';
        $barChar['/'] = 'nwnwnnnwn';
        $barChar['+'] = 'nwnnnwnwn';
        $barChar['%'] = 'nnnwnwnwn';

        $this->SetFont('Arial', '', 3);
        $this->Text($xpos, $ypos + $height + 1, $text);
        $this->SetFillColor(0);

        $code = '*' . strtoupper($code) . '*';
        for ($i = 0; $i < strlen($code); $i++) {
            $char = $code[$i];
            if (!isset($barChar[$char])) {
                $this->Error('Invalid character in barcode: ' . $char);
            }
            $seq = $barChar[$char];
            for ($bar = 0; $bar < 9; $bar++) {
                if ($seq[$bar] == 'n') {
                    $lineWidth = $narrow;
                } else {
                    $lineWidth = $wide;
                }
                if ($bar % 2 == 0) {
                    $this->Rect($xpos, $ypos, $lineWidth, $height, 'F');
                }
                $xpos += $lineWidth;
            }
            $xpos += $gap;
        }
    }

    function AddPageNew()
    {
        global $y, $baris, $default_y, $nopage;
        $this->AddPage('P', 'struck');
        $this->AliasNbPages();
        $y = $default_y;
        $baris = 1;
        $nopage++;
    }
    var $widths;
    var $aligns;


    function SetWidths($w)
    {
        //Set the array of column widths
        $this->widths = $w;
    }

    function SetAligns($a)
    {
        //Set the array of column alignments
        $this->aligns = $a;
    }

    function Row($data)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 5 * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            //Print the text
            $this->MultiCell($w, 5, $data[$i], 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt)
    {
        //Computes the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }
}

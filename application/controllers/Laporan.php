<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
    {
        parent::__construct();
        $this->load->model(array('model_laporan'));
        $this->load->library(array());
    }


	public function index()
	{
		$this->load->view('laporan/index');
	}

	public function cetak()
	{
		$this->generate_report();
	}

	private function generate_report()
	{
		//data untuk menampilkan jenjang pendidikan
		$data_pendidikan = $this->model_laporan->get_pendidikan();
		$array_pendidikan = array();
		foreach ($data_pendidikan as $key => $jp) 
		{
            $array_pendidikan[$jp['id_pendidikan']][] = $jp;
            $jenjang_pendidikan[$jp['id_pendidikan']] = $jp['jenjang_pendidikan'];
        }


        //data untuk menampilkan bulan dan total laka
        $data_laka_bulan = $this->model_laporan->get_laka_bulan();
        $array_total_laka = array();
        foreach ($data_laka_bulan as $dlb) {
        	$array_total_laka[$dlb['group_bulan']][] = $dlb;
            $bulan_laka[$dlb['group_bulan']] = $dlb['nama_bulan'];
            $total_laka[$dlb['group_bulan']] = $dlb['total_laka'];
        }


        //data untuk menampilkan korban berdasarkan pendidikan
        $data_laka_pendidikan = $this->model_laporan->get_laka_pendidikan();
        $array_pendidikan_laka = array();
        $isi_korban = array();

        foreach ($data_laka_pendidikan as $dlp) {
            $array_pendidikan_laka[$dlp['group_bulan']] = $dlp;
            $isi_korban[$dlp['group_bulan']][$dlp['id_pendidikan']]= $dlp['jumlah_laka'];
        }

		require_once APPPATH . 'third_party/php_excel/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';

		$excel = new PHPExcel();

		$excel->getProperties()->setCreator('Cakrawaladigital.com')
                                ->setLastModifiedBy(ucwords(strtolower('Cakrawaladigital.com')))
                                ->setTitle("Rekap Laka ".ucwords(strtolower('Rekap Laka')))
                                ->setSubject("Rekap Laka")
                                ->setDescription("Laporan Laka ".ucwords(strtolower('Rekap')))
                                ->setKeywords("Rekap Laka");


        //style cell header tabel
        $style_col = array(
                            'font' => array('bold' => true), // Set font nya jadi bold
                            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, // Set text jadi di tengah secara vertical (middle)
                                'wrap' => TRUE // Set wrap text
                            ),
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => "cccccc")
                            ),
                            'borders' => array(
                                'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array('argb' => '000000'))
                            )
                        );
        //style cell
        $style_row = array(
         'borders' => array(
             'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
             'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
             'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
             'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
         )
        );

        $style_footer = array(
			'font' => array('bold' => true), // Set font nya jadi bold
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, // Set text jadi ditengah secara horizontal (center)
				'wrap' => TRUE // Set wrap text
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => "cccccc")
			),
			'borders' => array(
			 'allborders' => array(
				 'style' => PHPExcel_Style_Border::BORDER_THIN,
							   'color' => array('argb' => '000000')
			 )
		 )
		);

        // Buat header tabel nya pada baris ke 3
        $excel->getActiveSheet()->mergeCells('A3:A4');
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO");
        $excel->getActiveSheet()->mergeCells('B3:B4');
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "PERIODE");
        $excel->getActiveSheet()->mergeCells('C3:C4');
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "JML LAKA");
        $excel->getActiveSheet()->getStyle('A3:A4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3:B4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3:C4')->applyFromArray($style_col);

        // ukuran width kolom ABC
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); 
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);

        $col = 3; $i = 3; $cols = 2; $merge=0;
        $excel->getActiveSheet()->mergeCellsByColumnAndRow($col, $i, $col+(count($data_pendidikan))-1,$i);
        $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $i, 'Pendidikan Korban');
        $excel->getActiveSheet()->getStyleByColumnAndRow($col, $i,$col+count($data_pendidikan)-1, $i)->applyFromArray($style_col);

        foreach ($array_pendidikan as $row => $val) 
        {
            $tot = count($val);
            $excel->getActiveSheet()->mergeCellsByColumnAndRow($col, 4, $col+$tot-1, 4);
            $excel->getActiveSheet()->setCellValueByColumnAndRow($col, 4, $jenjang_pendidikan[$row])->getColumnDimensionByColumn($col, 4, $col+$tot-1, 4)->setWidth('20');
            $excel->getActiveSheet()->getStyleByColumnAndRow($col, 4, $col+$tot-1, 4)->applyFromArray($style_col);
            
            $col += 1;
            $merge = $merge + $tot;
        }


		$excel->getActiveSheet()->mergeCellsByColumnAndRow($merge+3, 3, $merge+3, 4);
		$excel->getActiveSheet()->setCellValueByColumnAndRow($merge+3, 3, 'KET')->getColumnDimensionByColumn($merge+3)->setWidth('20');
		$excel->getActiveSheet()->getStyleByColumnAndRow($merge+3, 3, $merge+3, 4)->applyFromArray($style_col);

        $no=0; $line=4; $i=3; $total_kasus=0;
        foreach($array_total_laka as $key => $showbulanlaka) 
        {
        	$no++;
        	$total_kasus = $total_kasus + $total_laka[$key];
        	//set nomor urut
        	$excel->getActiveSheet()->mergeCellsByColumnAndRow(0, $line+$no, 0, $line+$no);
            $excel->getActiveSheet()->setCellValueByColumnAndRow(0, $line+$no, $no);
            $excel->getActiveSheet()->getStyleByColumnAndRow(0, $line+$no, 0, $line+$no)->applyFromArray($style_row);

            //set bulan
            $excel->getActiveSheet()->mergeCellsByColumnAndRow(1, $line+$no, 1, $line+$no);
            $excel->getActiveSheet()->setCellValueByColumnAndRow(1, $line+$no, $bulan_laka[$key]);
            $excel->getActiveSheet()->getStyleByColumnAndRow(1, $line+$no, 1, $line+$no)->applyFromArray($style_row);

            //set jumlah laka
            $excel->getActiveSheet()->mergeCellsByColumnAndRow(2, $line+$no, 2, $line+$no);
            $excel->getActiveSheet()->setCellValueByColumnAndRow(2, $line+$no, $total_laka[$key]);
            $excel->getActiveSheet()->getStyleByColumnAndRow(2, $line+$no, 2, $line+$no)->applyFromArray($style_row);

            $cols = 3;
            $tot_korban_perbulan = 0;
            foreach ($array_pendidikan as $row => $val) 
	        {
	        	foreach ($val as $rows => $a) 
	        	{

		            $jumlah_korban = isset($isi_korban[$key][$row]) ? $isi_korban[$key][$row] : 0;
		            $tot_korban_perbulan = $tot_korban_perbulan + $jumlah_korban;

		            $excel->getActiveSheet()->mergeCellsByColumnAndRow($cols, $line+$no, $cols, $line+$no);
					$excel->getActiveSheet()->setCellValueByColumnAndRow($cols, $line+$no, $jumlah_korban)->getColumnDimensionByColumn($cols)->setWidth('20');
					$excel->getActiveSheet()->getStyleByColumnAndRow($cols, $line+$no, $cols, $line+$no)->applyFromArray($style_row);
		            
		            $cols += 1;
		            $total[$row][$a['id_pendidikan']][] = $jumlah_korban;
		        }
	        }
	        $excel->getActiveSheet()->mergeCellsByColumnAndRow($cols, $line+$no, $cols, $line+$no);
			$excel->getActiveSheet()->setCellValueByColumnAndRow($cols, $line+$no, $tot_korban_perbulan)->getColumnDimensionByColumn($cols)->setWidth('20');
			$excel->getActiveSheet()->getStyleByColumnAndRow($cols, $line+$no, $cols, $line+$no)->applyFromArray($style_row);

        }

        $excel->getActiveSheet()->mergeCellsByColumnAndRow(0, $line+count($array_total_laka)+1, 1, $line+count($array_total_laka)+1);
		$excel->getActiveSheet()->setCellValueByColumnAndRow(0, $line+count($array_total_laka)+1, 'JUMLAH');
		$excel->getActiveSheet()->getStyleByColumnAndRow(0, $line+count($array_total_laka)+1, 1, $line+count($array_total_laka)+1)->applyFromArray($style_col);

		$cols = 3;
		$tot_all = 0;
		foreach ($array_pendidikan as $t => $val) {
			foreach ($val as $rows => $a) {

				$sum = array_sum($total[$t][$a['id_pendidikan']]);
				$tot_all = $tot_all + $sum;
				$excel->getActiveSheet()->mergeCellsByColumnAndRow($cols, $line+count($array_total_laka)+1, $cols, $line+count($array_total_laka)+1);
				$excel->getActiveSheet()->setCellValueByColumnAndRow($cols, $line+count($array_total_laka)+1, $sum)->getColumnDimensionByColumn($cols)->setWidth('20');
				$excel->getActiveSheet()->getStyleByColumnAndRow($cols, $line+count($array_total_laka)+1, $cols, $line+count($array_total_laka)+1)->applyFromArray($style_footer);
				$cols += 1;
			}
		}

		$excel->getActiveSheet()->mergeCellsByColumnAndRow(2, $line+count($array_total_laka)+1, 2, $line+count($array_total_laka)+1);
		$excel->getActiveSheet()->setCellValueByColumnAndRow(2, $line+count($array_total_laka)+1, $total_kasus)->getColumnDimensionByColumn(2)->setWidth('20');
		$excel->getActiveSheet()->getStyleByColumnAndRow(2, $line+count($array_total_laka)+1, 2, $line+count($array_total_laka)+1)->applyFromArray($style_footer);
		

		$excel->getActiveSheet()->mergeCellsByColumnAndRow($cols, $line+count($array_total_laka)+1, $cols, $line+count($array_total_laka)+1);
		$excel->getActiveSheet()->setCellValueByColumnAndRow($cols, $line+count($array_total_laka)+1, $tot_all)->getColumnDimensionByColumn($cols)->setWidth('20');
		$excel->getActiveSheet()->getStyleByColumnAndRow($cols, $line+count($array_total_laka)+1, $cols, $line+count($array_total_laka)+1)->applyFromArray($style_footer);


        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle('Korban Laka');
        $excel->setActiveSheetIndex(0);

        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="rekap_laka.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
	}
}

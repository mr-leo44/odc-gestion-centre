<?php




namespace App\Http\Controllers;




use DateTime;
use App\Models\Activite;
use App\Models\Presence;
use App\Models\Calendrier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;




class CalendrierController extends Controller
{
    public function export()
{
       
$activites = Activite::all();
$categorieId = 2;
$categorId = 1;
$categorieActivites = Activite::where('categorie_id', $categorieId)->get();
$categorActivites = Activite::where('categorie_id', $categorId)->get();
$mergedActivites = $activites->merge($categorieActivites);




$candidats = Presence::leftJoin('candidats as ca', 'ca.id', '=', 'presences.candidat_id')
    ->leftJoin('odcusers as us', 'us.id', '=', 'ca.odcuser_id')
    ->leftJoin('activites as ac', 'ac.id', '=', 'ca.activite_id')
    ->leftJoin('categories  as cat', 'cat.id', '=', 'ac.categorie_id')
    ->whereNotNull('ac.title')
    ->orderBy('ac.start_date', 'asc')
    ->select([
        'presences.candidat_id',
        'us.gender',
        'ac.end_date',
        'ac.title',
        'ac.start_date',
        'cat.name'
    ])
    ->get();




$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('B1', 'STAGES');
$sheet->setCellValue('B2', 'Stage Orange Summer Challenge');
$sheet->setCellValue('B3', 'Stage en Reconversion Profesionnelle');
$sheet->setCellValue('B4', "Stage PFE (Projet de Fin d'Etudes)");
$sheet->setCellValue('B5', "AWS re/Start");
$sheet->setCellValue('F1', 'FABLAB SOLIDAIRE');
$sheet->setCellValue('F2', 'OpenLab');
$sheet->setCellValue('F3', "Atelier RoboKID (Ecole Numérique))");
$sheet->setCellValue('F4', "Workshop FabLab");
$sheet->setCellValue('I1', 'CIBLE : EXTERNE ODC (GRAND PUBLIC)');
$sheet->setCellValue('I2', "Formations en ligne/ODC/Univérsités");
$sheet->setCellValue('I3', "ODC talks");
$sheet->setCellValue('I4', "Evénement");
$sheet->setCellValue('I5', "SuperCodeurs");




$sheet->setCellValue('A7', "S1 2024");
$sheet->setCellValue('D7', "Nom activité");
$sheet->setCellValue('E7', "Durée de la formation (jours)");
$sheet->setCellValue('F7', "Nbre d'heures (hr)");
$sheet->setCellValue('G7', "Cible (Grand public / nom université)");
$sheet->setCellValue('H7', "Nombre d'inscriptions");
$sheet->setCellValue('I7', "Nombre de participants");
$sheet->setCellValue('J7', "Nombre de filles");
$sheet->setCellValue('K7', "Nombre de garçons ");
$sheet->setCellValue('L7', "Emplois créés");
$sheet->setCellValue('M7', "Startups accompagnées");




/*$sheet->getRowDimension(62)->setRowHeight(75);




$row = 62;
$categoryRow = 62;








foreach ($activites as $activite) {
    $currentYear = date('Y');
    if (substr($activite->start_date, 0, 4) == $currentYear)  {
        $month = date('F', strtotime($activite->start_date));
        switch ($month) {
            case 'January':
                for ($i = 62; $i <= 92; $i++) {
                    $sheet->getStyle('A' . $i)->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('ead1dc');
                }


                for ($i = 62; $i <= 92; $i++) {
                    $sheet->getStyle('B' . $i)->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('eeeeee');
                }


                /* if ($activite->category == 1) {
                     $sheet->setCellValue('C' . $row, $activite->title)
                         ->getStyle('C' . $row)
                         ->getAlignment()
                         ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                     $sheet->setCellValue('D' . $row, $activite->start_date)
                         ->getStyle('D' . $row)
                         ->getAlignment()
                         ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                     $row++;
                 } else {
                     $sheet->setCellValue('G' . $categoryRow, $activite->title)
                         ->getStyle('G' . $categoryRow)
                         ->getAlignment()
                         ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                     $sheet->setCellValue('H' . $categoryRow, $activite->start_date)
                         ->getStyle('H' . $categoryRow)
                         ->getAlignment()
                         ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                     $categoryRow++;
                 }
                  // Fusionner les cellules
                  $sheet->mergeCells('A60:J60');
                  $sheet->mergeCells('A61:B61');
                 
                  // Définir le texte du mois dans la cellule A60
                  $sheet->setCellValue('A60', 'Janvier');
                 
                  // Définir le style de la cellule A60:J60
                  $sheet->getStyle('A60:J60')
                      ->getFill()
                      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                      ->getStartColor()
                      ->setARGB('FF000000');
                  $sheet->getStyle('A60:J60')
                      ->getFont()
                      ->getColor()
                      ->setARGB('FFFFFFFF');
                  $sheet->getStyle('A60:J60')
                      ->getAlignment()
                      ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                      ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                 
                  // Définir le style de la cellule A61:B61
                  $sheet->getStyle('A61:B61')
                      ->getFill()
                      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                      ->getStartColor()
                      ->setARGB('FF000000');
                  $sheet->getStyle('A61:B61')
                      ->getFont()
                      ->getColor()
                      ->setARGB('FFFFFFFF');
                  $sheet->getStyle('A61:B61')
                      ->getAlignment()
                      ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                      ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                      // Définir la bordure entre A60 et J92
                 $sheet->getStyle('C61:J61')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                 $sheet->getStyle('C61:J61')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
 
                 $sheet->getStyle('A60:J92')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                 $sheet->getStyle('A60:J92')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
 
                 $sheet->getStyle('K60:K92')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                 $sheet->getStyle('B61:B92')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                 $sheet->getStyle('C61:C92')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                 $sheet->getStyle('E61:E92')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                 $sheet->getStyle('G61:G92')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                 $sheet->getStyle('I61:I92')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
 
                 // Fusionner les cellules
                 $sheet->mergeCells('A60:J60');
                 $sheet->mergeCells('A61:B61');
                 $sheet->mergeCells('C61:D61');
                 $sheet->mergeCells('E61:F61');
                 $sheet->mergeCells('G61:H61');
                 $sheet->mergeCells('I61:J61');
 
                 // Définir le texte du mois dans la cellule A60
                 $sheet->setCellValue('A60', 'Janvier');
 
                 // Définir le texte "bonjour" dans les cellules fusionnées
                 $sheet->setCellValue('C61', 'Ecole du code');
                 $sheet->setCellValue('E61', 'Université/ODC CLub');
                 $sheet->setCellValue('G61', 'FabLab');
                 $sheet->setCellValue('I61', 'Orange Fab');
 
                 // Définir le style de la cellule A60:J60
                 $sheet->getStyle('A60:J60')
                     ->getFill()
                     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                     ->getStartColor()
                     ->setARGB('FF000000');
                 $sheet->getStyle('A60:J60')
                     ->getFont()
                     ->getColor()
                     ->setARGB('FFFFFFFF');
                 $sheet->getStyle('A60:J60')
                     ->getAlignment()
                     ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                     ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
 
                 // Définir le style de la cellule A61:B61
                 $sheet->getStyle('A61:B61')
                     ->getFill()
                     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                     ->getStartColor()
                     ->setARGB('FF000000');
                 $sheet->getStyle('A61:B61')
                     ->getFont()
                     ->getColor()
                     ->setARGB('FFFFFFFF');
                 $sheet->getStyle('A61:B61')
                     ->getAlignment()
                     ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                     ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
 
                // Définir le style du texte dans les cellules fusionnées
                 $sheet->getStyle('C61:D61')
                     ->getFont()
                     ->setBold(true)
                     ->setName('Arial')
                     ->setSize(13)
                     ->getColor()
                     ->setARGB('FF000000');
                 $sheet->getStyle('C61:D61')
                     ->getAlignment()
                     ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                     ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
 
                 $sheet->getStyle('E61:F61')
                     ->getFont()
                     ->setBold(true)
                     ->setName('Arial')
                     ->setSize(13)
                     ->getColor()
                     ->setARGB('FF000000');
                 $sheet->getStyle('E61:F61')
                     ->getAlignment()
                     ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                     ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
 
                 $sheet->getStyle('G61:H61')
                     ->getFont()
                     ->setBold(true)
                     ->setName('Arial')
                     ->setSize(13)
                     ->getColor()
                     ->setARGB('FF000000');
                 $sheet->getStyle('G61:H61')
                     ->getAlignment()
                     ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                     ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
 
                 $sheet->getStyle('I61:J61')
                     ->getFont()
                     ->setBold(true)
                     ->setName('Arial')
                     ->setSize(13)
                     ->getColor()
                     ->setARGB('FF000000');
                 $sheet->getStyle('I61:J61')
                     ->getAlignment()
                     ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                     ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
 
                break;
            case 'February':
                for ($i = 62; $i <= 92; $i++) {
                    $sheet->getStyle('L' . $i)->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('ead1dc');
                }


                for ($i = 62; $i <= 92; $i++) {
                    $sheet->getStyle('M' . $i)->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('eeeeee');
                }
               if ($activite->category == 1) {
                    $sheet->setCellValue('N' . $row, $activite->title)
                        ->getStyle('N' . $row)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->setCellValue('O' . $row, $activite->start_date)
                        ->getStyle('O' . $row)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $row++;
                } else {
                    $sheet->setCellValue('R' . $categoryRow, $activite->title)
                        ->getStyle('R' . $categoryRow)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->setCellValue('S' . $categoryRow, $activite->start_date)
                        ->getStyle('S' . $categoryRow)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $categoryRow++;
                }
                 // Fusionner les cellules
                 $sheet->mergeCells('L60:U60');
                 $sheet->mergeCells('L60:U60');
                 
                 // Définir le texte du mois dans la cellule A60
                 $sheet->setCellValue('L60', date('F', mktime(0, 0, 0, 2, 1, date('Y'))));
                 
                 // Définir le style de la cellule A60:J60
                 $sheet->getStyle('L60:U60')
                     ->getFill()
                     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                     ->getStartColor()
                     ->setARGB('FF000000');
                 $sheet->getStyle('L60:U60')
                     ->getFont()
                     ->getColor()
                     ->setARGB('FFFFFFFF');
                 $sheet->getStyle('L60:U60')
                     ->getAlignment()
                     ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                     ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                 
                 // Définir le style de la cellule A61:B61
                 $sheet->getStyle('L61:M61')
                     ->getFill()
                     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                     ->getStartColor()
                     ->setARGB('FF000000');
                 $sheet->getStyle('L61:M61')
                     ->getFont()
                     ->getColor()
                     ->setARGB('FFFFFFFF');
                 $sheet->getStyle('L61:M61')
                     ->getAlignment()
                     ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                     ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                     // Définir la bordure entre A60 et J92
                $sheet->getStyle('N61:U61')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('N61:U61')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);




                $sheet->getStyle('L60:U92')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('L60:U92')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);




                //$sheet->getStyle('V60:V92')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('M61:M92')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('N61:N92')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('P61:P92')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('R61:R92')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('T61:T92')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('V61:V92')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);








                // Fusionner les cellules
                $sheet->mergeCells('L60:V60');
                $sheet->mergeCells('N61:O61');
                $sheet->mergeCells('P61:Q61');
                $sheet->mergeCells('R61:S61');
                $sheet->mergeCells('T61:U61');




                // Définir le texte du mois dans la cellule A60
                $sheet->setCellValue('A60', 'Janvier');




                // Définir les Titres dans les cellules fusionnées
                $sheet->setCellValue('N61', 'Ecole du code');
                $sheet->setCellValue('P61', 'Université/ODC CLub');
                $sheet->setCellValue('R61', 'FabLab');
                $sheet->setCellValue('T61', 'Orange Fab');




                // Définir le style de la cellule
                $sheet->getStyle('L60:U60')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FF000000');
                $sheet->getStyle('L60:V60')
                    ->getFont()
                    ->getColor()
                    ->setARGB('FFFFFFFF');
                $sheet->getStyle('L60:V60')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);




               // Définir le style du texte
                $sheet->getStyle('N61:O61')
                    ->getFont()
                    ->setBold(true)
                    ->setName('Arial')
                    ->setSize(13)
                    ->getColor()
                    ->setARGB('FF000000');
                $sheet->getStyle('N61:O61')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);




                $sheet->getStyle('P61:Q61')
                    ->getFont()
                    ->setBold(true)
                    ->setName('Arial')
                    ->setSize(13)
                    ->getColor()
                    ->setARGB('FF000000');
                $sheet->getStyle('P61:Q61')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);




                $sheet->getStyle('R61:S61')
                    ->getFont()
                    ->setBold(true)
                    ->setName('Arial')
                    ->setSize(13)
                    ->getColor()
                    ->setARGB('FF000000');
                $sheet->getStyle('R61:S61')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);




                $sheet->getStyle('T61:U61')
                    ->getFont()
                    ->setBold(true)
                    ->setName('Arial')
                    ->setSize(13)
                    ->getColor()
                    ->setARGB('FF000000');
                $sheet->getStyle('T61:U61')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                break;
            case 'March':
                for ($i = 62; $i <= 92; $i++) {
                    $sheet->getStyle('W' . $i)->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('ead1dc');
                }


                for ($i = 62; $i <= 92; $i++) {
                    $sheet->getStyle('X' . $i)->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('eeeeee');
                }
              /*  if ($activite->category == 1) {
                    $sheet->setCellValue('Y' . $row, $activite->title)
                        ->getStyle('Y' . $row)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->setCellValue('Z' . $row, $activite->start_date)
                        ->getStyle('Z' . $row)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $row++;
                } else {
                    $sheet->setCellValue('AD' . $categoryRow, $activite->title)
                        ->getStyle('AD' . $categoryRow)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->setCellValue('AE' . $categoryRow, $activite->start_date)
                        ->getStyle('AE' . $categoryRow)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $categoryRow++;
                }
                break;
             // Fusionner les cellules
             $sheet->mergeCells('W60:AF60');
           
             // Définir le texte du mois dans la cellule
             $sheet->setCellValue('W60', date('F', mktime(0, 0, 0, 3, 1, date('Y'))));
             
             // Définir le style de la cellule A60:J60
             $sheet->getStyle('W60:AF60')
                 ->getFill()
                 ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                 ->getStartColor()
                 ->setARGB('FF000000');
             $sheet->getStyle('W60:AF60')
                 ->getFont()
                 ->getColor()
                 ->setARGB('FFFFFFFF');
             $sheet->getStyle('W60:AF60')
                 ->getAlignment()
                 ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                 ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
             
             // Définir le style de la cellule A61:B61
             $sheet->getStyle('W61:X61')
                 ->getFill()
                 ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                 ->getStartColor()
                 ->setARGB('FF000000');
             $sheet->getStyle('W61:X61')
                 ->getFont()
                 ->getColor()
                 ->setARGB('FFFFFFFF');
             $sheet->getStyle('W61:X61')
                 ->getAlignment()
                 ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                 ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                 // Définir la bordure entre A60 et J92
            $sheet->getStyle('Y61:AF61')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('Y61:AF61')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);




            $sheet->getStyle('W60:AF92')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('W60:AF92')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);




            $sheet->getStyle('X61:X92')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('Y61:Y92')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('Z61:Z92')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AA61:AA92')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AC61:AC92')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AE61:AE92')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AG60:AG92')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);




            // Fusionner les cellules
            $sheet->mergeCells('W60:AF60');
            $sheet->mergeCells('Y61:Z61');
            $sheet->mergeCells('AA61:AB61');
            $sheet->mergeCells('AC61:AD61');
            $sheet->mergeCells('AE61:AF61');


            // Définir les Titres dans les cellules fusionnées
            $sheet->setCellValue('Y61', 'Ecole du code');
            $sheet->setCellValue('AA61', 'Université/ODC CLub');
            $sheet->setCellValue('AC61', 'FabLab');
            $sheet->setCellValue('AE61', 'Orange Fab');




            // Définir le style de la cellule
            $sheet->getStyle('W60:AF60')
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('FF000000');
            $sheet->getStyle('W60:AF60')
                ->getFont()
                ->getColor()
                ->setARGB('FFFFFFFF');
            $sheet->getStyle('W60:AF60')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


           // Définir le style du texte
            $sheet->getStyle('Y61:Z61')
                ->getFont()
                ->setBold(true)
                ->setName('Arial')
                ->setSize(13)
                ->getColor()
                ->setARGB('FF000000');
            $sheet->getStyle('Y61:Z61')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);




            $sheet->getStyle('AA61:AB61')
                ->getFont()
                ->setBold(true)
                ->setName('Arial')
                ->setSize(13)
                ->getColor()
                ->setARGB('FF000000');
            $sheet->getStyle('AA61:AB61')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);




            $sheet->getStyle('AC61:AD61')
                ->getFont()
                ->setBold(true)
                ->setName('Arial')
                ->setSize(13)
                ->getColor()
                ->setARGB('FF000000');
            $sheet->getStyle('AC61:AD61')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);




            $sheet->getStyle('AE61:AF61')
                ->getFont()
                ->setBold(true)
                ->setName('Arial')
                ->setSize(13)
                ->getColor()
                ->setARGB('FF000000');
            $sheet->getStyle('AE61:AF61')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        }
    }
}*/


    $months = [
        'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
        'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
    ];


    $daysOfWeek = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];


    $sheet = $spreadsheet->getActiveSheet();
    $startRow = 60;
    $currentYear = date('Y');


    foreach ($months as $index => $month) {
        $startColumnIndex = $index * 12 + 1; // 10 colonnes pour chaque mois plus 2 supplémentaires pour l'espacement
        $currentColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex);


        // Fusionner et définir le titre du mois
        $sheet->mergeCells("{$currentColumn}{$startRow}:" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 9) . "{$startRow}");
        $sheet->setCellValue("{$currentColumn}{$startRow}", $month);
        $sheet->getStyle("{$currentColumn}{$startRow}:" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 9) . "{$startRow}")
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF000000');
        $sheet->getStyle("{$currentColumn}{$startRow}:" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 9) . "{$startRow}")
            ->getFont()
            ->getColor()
            ->setARGB('FFFFFFFF');
        $sheet->getStyle("{$currentColumn}{$startRow}:" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 9) . "{$startRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);


        // Définir des sous-titres
        $sheet->mergeCells("{$currentColumn}" . ($startRow + 1) . ":" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 1) . ($startRow + 1));
        $sheet->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 2) . ($startRow + 1) . ":" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 3) . ($startRow + 1));
        $sheet->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 4) . ($startRow + 1) . ":" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 5) . ($startRow + 1));
        $sheet->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 6) . ($startRow + 1) . ":" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 7) . ($startRow + 1));
        $sheet->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 8) . ($startRow + 1) . ":" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 9) . ($startRow + 1));


        $sheet->setCellValue("{$currentColumn}" . ($startRow + 1), ' ');
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 2) . ($startRow + 1), 'Ecole du code');
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 4) . ($startRow + 1), 'Université/ODC Club');
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 6) . ($startRow + 1), 'FabLab');
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 8) . ($startRow + 1), 'Orange Fab');


        $sheet->getStyle("{$currentColumn}" . ($startRow + 1) . ":" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 1) . ($startRow + 1))
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF000000');
        $sheet->getStyle("{$currentColumn}" . ($startRow + 1) . ":" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 1) . ($startRow + 1))
            ->getFont()
            ->getColor()
            ->setARGB('FFFFFFFF');
        $sheet->getStyle("{$currentColumn}" . ($startRow + 1) . ":" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 1) . ($startRow + 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);


        // Appliquer une mise en forme en gras aux en-têtes de section
        $styles = [2, 4, 6, 8];
        foreach ($styles as $colIndex) {
            $sheet->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + $colIndex) . ($startRow + 1) . ":" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + $colIndex + 1) . ($startRow + 1))
                ->getFont()
                ->setBold(true)
                ->setName('Arial')
                ->setSize(13)
                ->getColor()
                ->setARGB('FF000000');
            $sheet->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + $colIndex) . ($startRow + 1) . ":" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + $colIndex + 1) . ($startRow + 1))
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
        }


        // Définir les jours du mois avec un formatage en gras
        $date = new DateTime("{$currentYear}-" . ($index + 1) . "-01");
        $daysInMonth = $date->format('t');
        $lastDayRow = $startRow + 1 + $daysInMonth;


        foreach ($months as $index => $month) {
            $startColumnIndex = $index * 12 + 1; // 10 columns for each month plus 2 extra for spacing
            $currentColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex);
   
            // Fusionner et définir le titre du mois
            $sheet->mergeCells("{$currentColumn}{$startRow}:" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 9) . "{$startRow}");
            $sheet->setCellValue("{$currentColumn}{$startRow}", $month);
            $sheet->getStyle("{$currentColumn}{$startRow}:" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 9) . "{$startRow}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('FF000000');
            $sheet->getStyle("{$currentColumn}{$startRow}:" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 9) . "{$startRow}")
                ->getFont()
                ->getColor()
                ->setARGB('FFFFFFFF');
            $sheet->getStyle("{$currentColumn}{$startRow}:" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 9) . "{$startRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
   
            // Définir des sous-titres
            $sheet->mergeCells("{$currentColumn}" . ($startRow + 1) . ":" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 1) . ($startRow + 1));
            $sheet->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 2) . ($startRow + 1) . ":" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 3) . ($startRow + 1));
            $sheet->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 4) . ($startRow + 1) . ":" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 5) . ($startRow + 1));
            $sheet->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 6) . ($startRow + 1) . ":" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 7) . ($startRow + 1));
            $sheet->mergeCells(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 8) . ($startRow + 1) . ":" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 9) . ($startRow + 1));
   
            $sheet->setCellValue("{$currentColumn}" . ($startRow + 1), 'Date');
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 2) . ($startRow + 1), 'Ecole du code');
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 4) . ($startRow + 1), 'Université/ODC Club');
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 6) . ($startRow + 1), 'FabLab');
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 8) . ($startRow + 1), 'Orange Fab');
   
            $sheet->getStyle("{$currentColumn}" . ($startRow + 1) . ":" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 1) . ($startRow + 1))
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('FF000000');
            $sheet->getStyle("{$currentColumn}" . ($startRow + 1) . ":" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 1) . ($startRow + 1))
                ->getFont()
                ->getColor()
                ->setARGB('FFFFFFFF');
            $sheet->getStyle("{$currentColumn}" . ($startRow + 1) . ":" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 1) . ($startRow + 1))
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
   
            // Appliquer une mise en forme en gras aux en-têtes de sectio
            $styles = [2, 4, 6, 8];
            foreach ($styles as $colIndex) {
                $sheet->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + $colIndex) . ($startRow + 1) . ":" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + $colIndex + 1) . ($startRow + 1))
                    ->getFont()
                    ->setBold(true)
                    ->setName('Arial')
                    ->setSize(13)
                    ->getColor()
                    ->setARGB('FF000000');
                $sheet->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + $colIndex) . ($startRow + 1) . ":" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + $colIndex + 1) . ($startRow + 1))
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
            }
   
            // Définir les jours du mois avec un formatage en gras
            $date = new DateTime("{$currentYear}-" . ($index + 1) . "-01");
            $daysInMonth = $date->format('t');
            $lastDayRow = $startRow + 1 + $daysInMonth;
   
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date->setDate($currentYear, $index + 1, $day);
                $dayOfWeek = $daysOfWeek[$date->format('w')];
                $dateColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex);
                $dayColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 1);
                $sheet->setCellValue("{$dateColumn}" . ($day + $startRow + 1), $day); // seulement le jour
                $sheet->setCellValue("{$dayColumn}" . ($day + $startRow + 1), $dayOfWeek);
   
                // Appliquer une mise en forme grasse aux dates et aux jours de la semaine
                $sheet->getStyle("{$dateColumn}" . ($day + $startRow + 1) . ":" . "{$dayColumn}" . ($day + $startRow + 1))
                    ->getFont()
                    ->setBold(true);
            }
   
           
            // Ajoutez des bordures uniquement autour de la section du mois entier


            $sheet->getStyle("{$currentColumn}{$startRow}:" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 9) . "{$lastDayRow}")
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);


            for ($i = $startRow + 2; $i <= $lastDayRow; $i++) {
                $sheet->getStyle("{$currentColumn}" . $i)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('ead1dc');
                $sheet->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 1) . $i)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('eeeeee');
            }
        }
    }


   // Définir les styles titre
   $sheet->getStyle('B1')
   ->getFont()
   ->setBold(true)
   ->setColor(new Color(Color::COLOR_WHITE));
   $sheet->getStyle('B1')
   ->getFill()
   ->setFillType(Fill::FILL_SOLID)
   ->getStartColor()
   ->setARGB('000000');
   $sheet->getStyle('B1')
   ->getAlignment()
   ->setHorizontal(Alignment::HORIZONTAL_CENTER)
   ->setVertical(Alignment::VERTICAL_CENTER);
   $sheet->getStyle('B1:D1')
   ->getBorders()
   ->getAllBorders()
   ->setBorderStyle(Border::BORDER_THIN)
   ->setColor(new Color('000000'));
   $sheet->getStyle('B1')
   ->getFont()
   ->setName('Arial')
   ->setSize(9);




   ////
   $sheet->getStyle('B2')
   ->getFont()
   ->setBold(true)
   ->setColor(new Color(Color::COLOR_BLACK));
   $sheet->getStyle('B2')
   ->getFill()
   ->setFillType(Fill::FILL_SOLID)
   ->getStartColor()
   ->setARGB('ff00ff');
   $sheet->getStyle('B2')
   ->getAlignment()
   ->setHorizontal(Alignment::HORIZONTAL_CENTER)
   ->setVertical(Alignment::VERTICAL_CENTER);
   $sheet->getStyle('B2:D2')
   ->getBorders()
   ->getAllBorders()
   ->setBorderStyle(Border::BORDER_THIN)
   ->setColor(new Color('000000'));
   $sheet->getStyle('B2')
    ->getFont()
    ->setName('Arial')
    ->setSize(9);




   ////
   $sheet->getStyle('B3')
   ->getFont()
   ->setBold(true)
   ->setColor(new Color(Color::COLOR_BLACK));
   $sheet->getStyle('B3')
   ->getFill()
   ->setFillType(Fill::FILL_SOLID)
   ->getStartColor()
   ->setARGB('c27ba0');
   $sheet->getStyle('B3')
   ->getAlignment()
   ->setHorizontal(Alignment::HORIZONTAL_CENTER)
   ->setVertical(Alignment::VERTICAL_CENTER);
   $sheet->getStyle('B3:D3')
   ->getBorders()
   ->getAllBorders()
   ->setBorderStyle(Border::BORDER_THIN)
   ->setColor(new Color('000000'));
   $sheet->getStyle('B3')
    ->getFont()
    ->setName('Arial')
    ->setSize(9);




    ///////




    $sheet->getStyle('B4')
    ->getFont()
    ->setBold(true)
    ->setColor(new Color(Color::COLOR_BLACK));
    $sheet->getStyle('B4')
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('d5a6bd');
    $sheet->getStyle('B4')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
    ->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('B4:D4')
    ->getBorders()
    ->getAllBorders()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000000'));
    $sheet->getStyle('B4')
     ->getFont()
     ->setName('Arial')
     ->setSize(9);
   
    /////
    $sheet->getStyle('B5')
    ->getFont()
    ->setBold(true)
    ->setColor(new Color(Color::COLOR_BLACK));
    $sheet->getStyle('B5')
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('ead1dc');
    $sheet->getStyle('B5')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
    ->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('B5:D5')
    ->getBorders()
    ->getAllBorders()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000000'));
    $sheet->getStyle('B5')
     ->getFont()
     ->setName('Arial')
     ->setSize(9);




     //// style Fablab




     $sheet->getStyle('F1')
     ->getFont()
     ->setBold(true)
     ->setColor(new Color(Color::COLOR_WHITE));
     $sheet->getStyle('F1')
     ->getFill()
     ->setFillType(Fill::FILL_SOLID)
     ->getStartColor()
     ->setARGB('000000');
     $sheet->getStyle('F1')
     ->getAlignment()
     ->setHorizontal(Alignment::HORIZONTAL_CENTER)
     ->setVertical(Alignment::VERTICAL_CENTER);
     $sheet->getStyle('F1:G1')
     ->getBorders()
     ->getAllBorders()
     ->setBorderStyle(Border::BORDER_THIN)
     ->setColor(new Color('000000'));
     $sheet->getStyle('F1')
     ->getFont()
     ->setName('Arial')
     ->setSize(9);
     




     ///////////////////////
     
     $sheet->getStyle('F2')
     ->getFont()
     ->setBold(true)
     ->setColor(new Color(Color::COLOR_BLACK));
     $sheet->getStyle('F2')
     ->getFill()
     ->setFillType(Fill::FILL_SOLID)
     ->getStartColor()
     ->setARGB('ff6d01');
     $sheet->getStyle('F2')
     ->getAlignment()
     ->setHorizontal(Alignment::HORIZONTAL_CENTER)
     ->setVertical(Alignment::VERTICAL_CENTER);
     $sheet->getStyle('F2:G2')
     ->getBorders()
     ->getAllBorders()
     ->setBorderStyle(Border::BORDER_THIN)
     ->setColor(new Color('000000'));
     $sheet->getStyle('F2')
     ->getFont()
     ->setName('Arial')
     ->setSize(9);




    ////////////////////////
    $sheet->getStyle('F3')
     ->getFont()
     ->setBold(true)
     ->setColor(new Color(Color::COLOR_BLACK));
     $sheet->getStyle('F3')
     ->getFill()
     ->setFillType(Fill::FILL_SOLID)
     ->getStartColor()
     ->setARGB('ff9a4f');
     $sheet->getStyle('F3')
     ->getAlignment()
     ->setHorizontal(Alignment::HORIZONTAL_CENTER)
     ->setVertical(Alignment::VERTICAL_CENTER);
     $sheet->getStyle('F3:G3')
     ->getBorders()
     ->getAllBorders()
     ->setBorderStyle(Border::BORDER_THIN)
     ->setColor(new Color('000000'));
     $sheet->getStyle('F3')
     ->getFont()
     ->setName('Arial')
     ->setSize(9);




     /////////////////
     $sheet->getStyle('F4')
     ->getFont()
     ->setBold(true)
     ->setColor(new Color(Color::COLOR_BLACK));
     $sheet->getStyle('F4')
     ->getFill()
     ->setFillType(Fill::FILL_SOLID)
     ->getStartColor()
     ->setARGB('ff9a4f');
     $sheet->getStyle('F4')
     ->getAlignment()
     ->setHorizontal(Alignment::HORIZONTAL_CENTER)
     ->setVertical(Alignment::VERTICAL_CENTER);
     $sheet->getStyle('F4:G4')
     ->getBorders()
     ->getAllBorders()
     ->setBorderStyle(Border::BORDER_THIN)
     ->setColor(new Color('000000'));
     $sheet->getStyle('F4')
     ->getFont()
     ->setName('Arial')
     ->setSize(9);
     
    // CIBLE : EXTERNE ODC (GRAND PUBLIC)
    $sheet->getStyle('I1')
     ->getFont()
     ->setBold(true)
     ->setColor(new Color(Color::COLOR_WHITE));
     $sheet->getStyle('I1')
     ->getFill()
     ->setFillType(Fill::FILL_SOLID)
     ->getStartColor()
     ->setARGB('ff9a4f');
     $sheet->getStyle('I1')
     ->getAlignment()
     ->setHorizontal(Alignment::HORIZONTAL_CENTER)
     ->setVertical(Alignment::VERTICAL_CENTER);
     $sheet->getStyle('I1:M1')
     ->getBorders()
     ->getAllBorders()
     ->setBorderStyle(Border::BORDER_THIN)
     ->setColor(new Color('000000'));
     $sheet->getStyle('I1')
     ->getFont()
     ->setName('Arial')
     ->setSize(9);




     ///////////
     $sheet->getStyle('I2')
     ->getFont()
     ->setBold(true)
     ->setColor(new Color(Color::COLOR_WHITE));
     $sheet->getStyle('I2')
     ->getFill()
     ->setFillType(Fill::FILL_SOLID)
     ->getStartColor()
     ->setARGB('ffb400');
     $sheet->getStyle('I2')
     ->getAlignment()
     ->setHorizontal(Alignment::HORIZONTAL_CENTER)
     ->setVertical(Alignment::VERTICAL_CENTER);
     $sheet->getStyle('I2:M2')
     ->getBorders()
     ->getAllBorders()
     ->setBorderStyle(Border::BORDER_THIN)
     ->setColor(new Color('000000'));
     $sheet->getStyle('I2')
     ->getFont()
     ->setName('Arial')
     ->setSize(9);




     ///////////
     $sheet->getStyle('I3')
     ->getFont()
     ->setBold(true)
     ->setColor(new Color(Color::COLOR_WHITE));
     $sheet->getStyle('I3')
     ->getFill()
     ->setFillType(Fill::FILL_SOLID)
     ->getStartColor()
     ->setARGB('0a6e31');
     $sheet->getStyle('I3')
     ->getAlignment()
     ->setHorizontal(Alignment::HORIZONTAL_CENTER)
     ->setVertical(Alignment::VERTICAL_CENTER);
     $sheet->getStyle('I3:M3')
     ->getBorders()
     ->getAllBorders()
     ->setBorderStyle(Border::BORDER_THIN)
     ->setColor(new Color('000000'));
     $sheet->getStyle('I3')
     ->getFont()
     ->setName('Arial')
     ->setSize(9);




    ///////////
    $sheet->getStyle('I4')
    ->getFont()
    ->setBold(true)
    ->setColor(new Color(Color::COLOR_WHITE));
    $sheet->getStyle('I4')
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('ff7900');
    $sheet->getStyle('I4')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
    ->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('I4:M4')
    ->getBorders()
    ->getAllBorders()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000000'));
    $sheet->getStyle('I4')
    ->getFont()
    ->setName('Arial')
    ->setSize(9);




    ///////////
    $sheet->getStyle('I5')
    ->getFont()
    ->setBold(true)
    ->setColor(new Color(Color::COLOR_WHITE));
    $sheet->getStyle('I5')
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('085ebd');
    $sheet->getStyle('I5')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
    ->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('I5:M5')
    ->getBorders()
    ->getAllBorders()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000000'));
    $sheet->getStyle('I5')
    ->getFont()
    ->setName('Arial')
    ->setSize(9);




      ///////////////////////////////////////
      $sheet->getStyle('A7')
      ->getFont()
      ->setBold(true)
      ->setColor(new Color(Color::COLOR_BLACK));
      $sheet->getStyle('A7')
      ->getFill()
      ->setFillType(Fill::FILL_SOLID)
      ->getStartColor()
      ->setARGB('cccccc');
      $sheet->getStyle('A7')
      ->getAlignment()
      ->setHorizontal(Alignment::HORIZONTAL_CENTER)
      ->setVertical(Alignment::VERTICAL_CENTER);
      $sheet->getStyle('A7:C7')
      ->getBorders()
      ->getAllBorders()
      ->setBorderStyle(Border::BORDER_THIN)
      ->setColor(new Color('000000'));
      $sheet->getStyle('A7')
      ->getFont()
      ->setName('Arial')
      ->setSize(18);
      ///////////////////////
      $sheet->getStyle('D7')
      ->getFont()
      ->setBold(true)
      ->setColor(new Color(Color::COLOR_BLACK));
      $sheet->getStyle('D7')
      ->getFill()
      ->setFillType(Fill::FILL_SOLID)
      ->getStartColor()
      ->setARGB('cccccc');
      $sheet->getStyle('D7')
      ->getAlignment()
      ->setHorizontal(Alignment::HORIZONTAL_CENTER)
      ->setVertical(Alignment::VERTICAL_CENTER);
      $sheet->getStyle('D7')
      ->getBorders()
      ->getAllBorders()
      ->setBorderStyle(Border::BORDER_THIN)
      ->setColor(new Color('000000'));
      $sheet->getStyle('D7')
      ->getFont()
      ->setName('Arial')
      ->setSize(9);
      ///////////////////////
      $sheet->getStyle('E7')
      ->getFont()
      ->setBold(true)
      ->setColor(new Color(Color::COLOR_BLACK));
      $sheet->getStyle('E7')
      ->getFill()
      ->setFillType(Fill::FILL_SOLID)
      ->getStartColor()
      ->setARGB('cccccc');
      $sheet->getStyle('E7')
      ->getAlignment()
      ->setHorizontal(Alignment::HORIZONTAL_CENTER)
      ->setVertical(Alignment::VERTICAL_CENTER);
      $sheet->getStyle('E7')
      ->getBorders()
      ->getAllBorders()
      ->setBorderStyle(Border::BORDER_THIN)
      ->setColor(new Color('000000'));
      $sheet->getStyle('E7')
      ->getFont()
      ->setName('Arial')
      ->setSize(9);
       ///////////////////////
       $sheet->getStyle('F7')
       ->getFont()
       ->setBold(true)
       ->setColor(new Color(Color::COLOR_BLACK));
       $sheet->getStyle('F7')
       ->getFill()
       ->setFillType(Fill::FILL_SOLID)
       ->getStartColor()
       ->setARGB('cccccc');
       $sheet->getStyle('F7')
       ->getAlignment()
       ->setHorizontal(Alignment::HORIZONTAL_CENTER)
       ->setVertical(Alignment::VERTICAL_CENTER);
       $sheet->getStyle('F7')
       ->getBorders()
       ->getAllBorders()
       ->setBorderStyle(Border::BORDER_THIN)
       ->setColor(new Color('000000'));
       $sheet->getStyle('F7')
       ->getFont()
       ->setName('Arial')
       ->setSize(9);








// Ajuster la largeur des colonnes contenant les titres
$sheet->mergeCells('B1:D1')->getColumnDimension('B')->setWidth(20);
$sheet->mergeCells('B2:D2')->getColumnDimension('C')->setWidth(20);
$sheet->mergeCells('B3:D3')->getColumnDimension('D')->setWidth(20);
$sheet->mergeCells('B4:D4');
$sheet->mergeCells('B5:D5');


for ($column = 'N'; $column <= 'U'; $column++) {
    $sheet->getColumnDimension($column)->setWidth(15);
}


for ($column = 'Y'; $column <= 'AF'; $column++) {
    $sheet->getColumnDimension($column)->setWidth(15);
}


$columnWidths = [];
for ($column = 'B'; $column <= 'D'; $column++) {
    $columnWidths[$column] = $sheet->getColumnDimension($column)->getWidth();
}




// Réinitialiser la largeur de chaque colonne fusionnée
for ($column = 'B'; $column <= 'D'; $column++) {
    $sheet->getColumnDimension($column)->setWidth($columnWidths[$column]);
}




// Ajuster la largeur des colonnes Fablab
$sheet->mergeCells('F1:G1')->getColumnDimension('G')->setWidth(20);
$sheet->mergeCells('F2:G2')->getColumnDimension('F')->setWidth(15);
$sheet->mergeCells('F3:G3');
$sheet->mergeCells('F4:G4');




$columnWidths = [];
for ($column = 'F'; $column <= 'G'; $column++) {
    $columnWidths[$column] = $sheet->getColumnDimension($column)->getWidth();
}




// Réinitialiser la largeur de chaque colonne fusionnée
for ($column = 'F'; $column <= 'G'; $column++) {
    $sheet->getColumnDimension($column)->setWidth($columnWidths[$column]);
}




// Ajuster la largeur des colonnes CIBLE : EXTERNE
$sheet->mergeCells('I1:M1');
$sheet->mergeCells('I2:M2');
$sheet->mergeCells('I3:M3');
$sheet->mergeCells('I4:M4');
$sheet->mergeCells('I5:M5');
// Récupérer la largeur de chaque colonne
$columnWidths = [];
for ($column = 'I'; $column <= 'M'; $column++) {
    $columnWidths[$column] = $sheet->getColumnDimension($column)->getWidth();
}




// Réinitialiser la largeur de chaque colonne fusionnée
for ($column = 'I'; $column <= 'M'; $column++) {
    $sheet->getColumnDimension($column)->setWidth($columnWidths[$column]);
}




/////////////////////////////////////////////////
$sheet->mergeCells('A7:C7')->getRowDimension(1)
->setRowHeight(30);;








    $writer = new Xlsx($spreadsheet);
    $spreadsheet->getSheetByName('Feuille 1');
    $fileName = 'calendrier.xlsx';
    $tempFile = tempnam(sys_get_temp_dir(), $fileName);
    $writer->save($tempFile);
   


        //On renvoie le fichier Excel au navigateur


    return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
}
}




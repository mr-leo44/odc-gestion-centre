<?php

namespace App\Http\Controllers;

use App\Models\Odcuser;
use App\Models\Activite;
use App\Models\Candidat;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ImportControl extends Controller
{
    public function index()
    {
        $activites = Activite::all();
        return view(/*'components.activite-import'*/'import.import', ['activites' => $activites]);
    }

    public function import(Request $request)
    {
        // Valider le fichier uploadé
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls',
            'activite' => 'required|exists:activites,id', // Valide que l'activité sélectionnée existe dans la table des activités
        ]);

        // Récupérer l'ID de l'activité à partir du formulaire
        $activiteId = $request->activite;

        // Lire le fichier
        $file = $request->file('file');
        $fileContents = file($file->getPathname());
        // Supposons que le fichier CSV contient des en-têtes
        $header = str_getcsv(array_shift($fileContents));
        // Parcourir les lignes du fichier CSV
        foreach ($fileContents as $line) {
            $data = str_getcsv($line);
            $rowData = array_combine($header, $data);
            //dd($rowData);
            // Valider les données de chaque ligne
            try {
                $validatedData = $this->validateRowData($rowData);
                //dump($validatedData);
                // Chercher l'utilisateur par email 
                $odcuser = Odcuser::where('email', $validatedData['email'])->first();

                $validatedData['birthDay'] = '1970-02-05';
                $validatedData['password'] = 'kdjksjfkndjskjd5555';
                $validatedData['profession'] = 'etudiant';
                $validatedData['odc_country'] = "{'country':'congo'}";
                $validatedData['role'] = 'user';
                $validatedData['is_active'] = '1';
                $validatedData['_id'] = 'test';
                $validatedData['createdAt'] = date("Y-m-d h:i:s ");
                $validatedData['updatedAt'] = date("Y-m-d h:i:s ");
                $validatedData['status']= 0;

                if ($odcuser) {
                    // Si l'utilisateur existe déjà, on recupere simplement son id.
                    $odcuser->update($validatedData);
                } else {
                    // Sinon, créez un nouvel utilisateur
                    $odcuser = Odcuser::create($validatedData);
                }

                //dd($validatedData);

                //$odcuser = Odcuser::firstOrCreate($validatedData);

                // Ajouter l'utilisateur à la table 'candidat'
                $candidat=Candidat::create([
                    'odcuser_id' => $odcuser->id,
                    'activite_id' => $activiteId,
                    'status' => $validatedData['status']
                ]);

                $date =$rowData['date'];
                //on remplie la table presence
                $datemodif = explode('_', $date);+
                Presence::create([
                    'date' => $datemodif[1],
                    'candidat_id' => $candidat->id,
                ]);
                //dump($validatedData['statut']);
            } catch (\Illuminate\Validation\ValidationException $e) {
                Log::error('Validation failed for row: ', ['row' => $rowData, 'errors' => $e->errors()]);
                continue; // Skip invalid rows
            }
        }

        return redirect()->back()->with('success', 'Importation exécutée avec succès');
    }

    private function validateRowData($rowData)
    {
        // Définir les règles de validation spécifiques
        $validator = Validator::make($rowData, [
            'firstName' => '',
            'last_name' => '',
            'email' => '',
            'password' => '',
            'gender' => '',
            'birth_date' => '',
            'linkedin' => '',
            'profession' => '',
            'odc_country' => '',
            'role' => '',
            'is_active' => '',
            'hashtags' => '',
            'coding_school' => '',
            'fablab_solidaire' => '',
            'training' => '',
            'internship' => '',
            'event' => '',
            'subscribe' => '',
            'newsletters' => '',
            'topics' => '',
            'last_connection' => '',
            '_id' => '',
            'detail_profession' => '',
            'createdAt' => '',
            'updatedAt' => '',
            '__v' => '',
            'picture' => '',
            'user_cv' => '',
            'statut' => 'boolean'
        ]);

        // Si la validation échoue, lever une exception
        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return $validator->validated();
    }

    public function store(Request $request)
    {
        // Valider les données du formulaire
        $request->validate([
            'activite' => 'required',
        ]);

        // Faire quelque chose avec les données (par exemple, les stocker dans une base de données)

        return redirect()->back()->with('success', 'Importation exécutée avec succès');
    }



    //function pour import dans activite

    public function indexacti()
    {
        $activites = Activite::all();
        return view('components.activite-import', ['activites' => $activites]);
    }
    public function importInActivity(Request $request, Activite $activite){
                // Valider le fichier uploadé
                $request->validate([
                    'file' => 'required|file|mimes:csv,xlsx,xls',
                ]);
        
                // Lire le fichier
                $file = $request->file('file');
                $fileContents = file($file->getPathname());
                // Supposons que le fichier CSV contient des en-têtes
                $header = str_getcsv(array_shift($fileContents));

    }
}
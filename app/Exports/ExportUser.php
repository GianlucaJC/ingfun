<?php 
    namespace App\Exports; 
    use App\Models\candidati;
    use Maatwebsite\Excel\Concerns\FromCollection; 
  
    class ExportUser implements FromCollection { 
        public function collection() 
        { 
            return candidati::select('nome','cognome')->get(); 
        }
    }
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\candidati;
use App\Models\user;

class CheckDisableUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scadenza:account';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check user to disable after expire';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   

		/*
			Lista user da disabilitare dopo data scadenza contratto
		*/
		$today=date("Y-m-d");
		$check=candidati::select('id','id_user')
		->where('data_fine','=',$today)
		->get();
		//azzera su db user
		foreach($check as $elenco) {
			$id_user=$elenco->id_user;
			user::where('id', $id_user)->delete();
		}
		//azzera su db del personale (candidatis)
		$azzera=candidati::where('data_fine','=',$today)->update(['id_user' => null]);
		
    }
	
}

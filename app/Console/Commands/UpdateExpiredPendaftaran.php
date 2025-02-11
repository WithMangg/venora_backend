<?php

namespace App\Console\Commands;

use App\Models\Pendaftaran;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateExpiredPendaftaran extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-expired-pendaftaran';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status Pendaftaran telah expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Pendaftaran::where(function ($query) {
            $query->where('status', 'Terdaftar')
                  ->orWhere('status', 'Dalam Antrian');
        })
            ->where('tanggal_daftar', '<', Carbon::now())
            ->update(['status' => 'Gagal']);

        $this->info('Expired appointments have been updated.');
        return 0;
    }
}

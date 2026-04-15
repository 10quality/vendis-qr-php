<?php
declare(strict_types=1);
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use VendisQr\Configuration;
use VendisQr\VendisQrClient;
final class RefreshVendisQrToken extends Command
{
    /**
     * @var string Artisan command signature.
     */
    protected $signature = 'vendis-qr:refresh-token';
    /**
     * @var string Artisan command description.
     */
    protected $description = 'Refresh the yearly Vendis QR access token.';
    /**
     * Refreshes and stores the yearly Vendis QR access token.
     *
     * @return int Command exit code.
     */
    public function handle(): int
    {
        $token = (new VendisQrClient(Configuration::fromEnvironment()))->login();
        DB::table('settings')->updateOrInsert(['key' => 'vendis_qr_access_token'], ['value' => Crypt::encryptString($token->value()), 'updated_at' => now()]);
        return self::SUCCESS;
    }
}

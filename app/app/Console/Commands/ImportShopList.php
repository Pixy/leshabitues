<?php

namespace App\Console\Commands;

use App\Shop;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class ImportShopList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shops:import-list
        {--url : (Optionnal) Add --url to specify an URL to fetch shops. Otherwise, the default will be used.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a list of shops by one URL';

    /**
     * @var string URL to fetch shops
     */
    private $url = 'https://www.leshabitues.fr/testapi/shops';

    /**
     * Launches the process
     */
    public function handle()
    {
        if ($this->option('url') && filter_var($this->option('url'), FILTER_VALIDATE_URL)) {
            $this->url = $this->option('url');
        }

        // No need to use Guzzle here for this simple call
        $externalShopList = file_get_contents($this->url);
        $externalShopList = json_decode($externalShopList, true)['results'];

        // Get all shop_id, gonna fetch our Shops objects by the shop_id
        $shopIds = array_pluck($externalShopList, 'shop_id');

        // Get all shops in our DB at once, limit access to DB.
        $internalShops = Shop::where('shop_id', 'IN', $shopIds)->get()->toArray();

        // Fetch all external shops
        foreach ($externalShopList as $externalShop) {
            $newShop = new Shop;
            $newShop->fill($externalShop);
            $newShop->save();
        }
    }
}

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
     * @var array Array for reporting at the end of the process
     */
    private $report = [];

    /**
     * Launches the process
     */
    public function handle()
    {
        if ($this->option('url') && filter_var($this->option('url'), FILTER_VALIDATE_URL)) {
            $this->url = $this->option('url');
        }

        // Init the report
        $this->report = [
            'external_shops_fetched' => 0,
            'new_shop_created' => 0,
            'existing_shop_updated' => 0,
        ];

        // No need to use Guzzle here for this simple call
        $externalShopList = file_get_contents($this->url);
        $externalShopList = json_decode($externalShopList, true)['results'];
        $shopIds = array_pluck($externalShopList, 'id');
        $this->report['external_shops_fetched'] = count($shopIds);

        // Get all shops in our DB at once, limit access to DB.
        $internalShops = Shop::whereIn('id', $shopIds)->get();

        // Fetch all external shops
        foreach ($externalShopList as $externalShop) {
            // Check if we already have this shop in our store
            $existingShop = $internalShops->where('id', $externalShop['id'])->first();

            if ($existingShop) {
                $existingShop = $existingShop->toArray();
                // Compute hash for comparing with our shop
                $externalHash = computeHash($externalShop);

                // If values are not equals, we need to update our shop
                if ($externalHash !== $existingShop['hash']) {
                    $shopToUpdate = Shop::find($existingShop['id']);
                    // Prevent from updating the primary key, in the fill method
                    unset($externalShop['id']);

                    // Compute new hash for next import
                    $newHash = computeHash($externalShop);

                    // Then update the shop
                    $shopToUpdate->fill($externalShop);
                    $shopToUpdate->hash = $newHash;
                    $shopToUpdate->update();

                    ++$this->report['existing_shop_updated'];
                }
            } else {
                // Otherwise we create it
                $newShop = new Shop;
                $newShop->fill($externalShop);
                $newShop->save();

                ++$this->report['new_shop_created'];
            }
        }

        $this->getOutput()->success('Import is done');
        $this->getOutput()->table(array_keys($this->report), [array_values($this->report)]);
    }
}

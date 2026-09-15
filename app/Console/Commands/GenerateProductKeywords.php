<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductKeyword;
use Illuminate\Console\Command;

class GenerateProductKeywords extends Command
{
    protected $signature = 'keywords:generate {--product_id= : Only generate for a single product ID}
        {--apply-to-tags : Also merge the generated primary/secondary keywords into each product\'s tags field}
        {--force : Overwrite existing pending keywords for a product (verified keywords are always kept)}';

    protected $description = 'Generate a starting set of SEO keyword candidates (primary + secondary) for products, marked as pending until verified with real search-volume data';

    public function handle()
    {
        $query = Product::query();

        if ($productId = $this->option('product_id')) {
            $query->where('id', $productId);
        }

        $applyToTags = $this->option('apply-to-tags');
        $force = $this->option('force');

        $count = 0;

        $query->chunk(200, function ($products) use (&$count, $applyToTags, $force) {
            foreach ($products as $product) {
                $hasVerified = $product->keywords()->where('status', 'verified')->exists();

                if (!$force && $product->keywords()->exists()) {
                    continue;
                }

                if ($force && !$hasVerified) {
                    $product->keywords()->where('status', 'pending')->delete();
                }

                $candidates = generate_seo_keyword_candidates($product);

                if (empty($candidates['primary'])) {
                    continue;
                }

                ProductKeyword::updateOrCreate(
                    ['product_id' => $product->id, 'keyword' => $candidates['primary']],
                    ['is_primary' => true, 'type' => 'primary', 'intent' => 'commercial', 'status' => 'pending']
                );

                $typedGroups = [
                    'secondary' => $candidates['secondary'] ?? [],
                    'long_tail' => $candidates['long_tail'] ?? [],
                    'material' => $candidates['material'] ?? [],
                    'colour' => $candidates['colour'] ?? [],
                ];

                foreach ($typedGroups as $type => $keywords) {
                    foreach ($keywords as $keyword) {
                        if ($keyword === '') {
                            continue;
                        }
                        ProductKeyword::updateOrCreate(
                            ['product_id' => $product->id, 'keyword' => $keyword],
                            ['is_primary' => false, 'type' => $type, 'intent' => 'commercial', 'status' => 'pending']
                        );
                    }
                }

                if ($applyToTags) {
                    $existingTags = array_filter(array_map('trim', explode(',', (string) $product->tags)));
                    $newKeywords = array_merge([$candidates['primary']], $candidates['secondary']);

                    $merged = collect(array_merge($existingTags, $newKeywords))
                        ->map(fn ($t) => trim($t))
                        ->filter()
                        ->unique(fn ($t) => strtolower($t))
                        ->values()
                        ->all();

                    $product->tags = implode(',', $merged);
                    $product->save();
                }

                $count++;
            }
        });

        $this->info("Generated keyword candidates for {$count} product(s). All marked 'pending' until verified with real search data.");

        return self::SUCCESS;
    }
}

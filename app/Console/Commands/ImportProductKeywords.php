<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductKeyword;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportProductKeywords extends Command
{
    protected $signature = 'keywords:import {file : Path to the CSV file}
        {--apply-to-tags : Also append new keywords into each matched product\'s tags field (used as the meta keywords fallback)}';

    protected $description = 'Import a product keyword-research CSV (Product Name, Keyword, Monthly Volume, KD, Intent, Metric Status) into product_keywords';

    public function handle()
    {
        $path = $this->argument('file');

        if (!file_exists($path)) {
            $this->error("File not found: {$path}");
            return self::FAILURE;
        }

        $handle = fopen($path, 'r');
        $header = fgetcsv($handle);
        $header = array_map(fn ($h) => strtolower(trim($h)), $header);

        $colProduct = array_search('product name', $header);
        $colKeyword = array_search('keyword', $header);
        $colVolume = array_search('monthly volume', $header);
        $colKd = array_search('kd', $header);
        $colIntent = array_search('intent', $header);
        $colStatus = array_search('metric status', $header);

        if ($colProduct === false || $colKeyword === false) {
            $this->error('CSV must contain at least "Product Name" and "Keyword" columns.');
            fclose($handle);
            return self::FAILURE;
        }

        $productCache = [];
        $imported = 0;
        $skipped = 0;
        $taggedProducts = [];
        $applyToTags = $this->option('apply-to-tags');

        while (($row = fgetcsv($handle)) !== false) {
            $productName = trim($row[$colProduct] ?? '');
            $keyword = trim($row[$colKeyword] ?? '');

            if ($productName === '' || $keyword === '') {
                $skipped++;
                continue;
            }

            if (!array_key_exists($productName, $productCache)) {
                $productCache[$productName] = Product::where('name', $productName)
                    ->orWhereHas('product_translations', function ($q) use ($productName) {
                        $q->where('name', $productName);
                    })
                    ->first();
            }

            $product = $productCache[$productName];

            if (!$product) {
                $this->warn("No matching product for: \"{$productName}\" — skipped keyword \"{$keyword}\"");
                $skipped++;
                continue;
            }

            $volumeRaw = $colVolume !== false ? trim($row[$colVolume] ?? '') : '';
            $kdRaw = $colKd !== false ? trim($row[$colKd] ?? '') : '';
            $intentRaw = $colIntent !== false ? strtolower(trim($row[$colIntent] ?? '')) : '';
            $statusRaw = $colStatus !== false ? trim($row[$colStatus] ?? '') : '';

            $volume = is_numeric($volumeRaw) ? (int) $volumeRaw : null;
            $kd = is_numeric($kdRaw) ? (float) $kdRaw : null;
            $intent = in_array($intentRaw, ['informational', 'transactional', 'navigational', 'commercial']) ? $intentRaw : null;
            $status = Str::contains(strtolower($statusRaw), 'verified') ? 'verified' : 'pending';

            ProductKeyword::updateOrCreate(
                ['product_id' => $product->id, 'keyword' => $keyword],
                [
                    'monthly_volume' => $volume,
                    'kd' => $kd,
                    'intent' => $intent,
                    'status' => $status,
                ]
            );

            $imported++;

            if ($applyToTags) {
                $taggedProducts[$product->id] = $product;
            }
        }

        fclose($handle);

        if ($applyToTags && !empty($taggedProducts)) {
            foreach ($taggedProducts as $product) {
                $existingTags = array_filter(array_map('trim', explode(',', (string) $product->tags)));
                $newKeywords = $product->keywords()->pluck('keyword')->all();

                $merged = collect(array_merge($existingTags, $newKeywords))
                    ->map(fn ($t) => trim($t))
                    ->filter()
                    ->unique(fn ($t) => strtolower($t))
                    ->values()
                    ->all();

                $product->tags = implode(',', $merged);
                $product->save();
            }
            $this->info('Tags updated for ' . count($taggedProducts) . ' product(s).');
        }

        $this->info("Imported/updated {$imported} keyword row(s). Skipped {$skipped}.");

        return self::SUCCESS;
    }
}

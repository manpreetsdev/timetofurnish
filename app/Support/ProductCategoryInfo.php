<?php

namespace App\Support;

use App\Models\Category;
use App\Models\Product;

class ProductCategoryInfo
{
    public const SETTING_KEY = 'product_category_info_badges';

    public static function badges(): array
    {
        $raw = get_setting(self::SETTING_KEY, '[]');
        $decoded = json_decode($raw, true);

        if (!is_array($decoded)) {
            return [];
        }

        return array_map(function ($badge) {
            if (!is_array($badge)) {
                return $badge;
            }

            if (empty($badge['category_ids']) && !empty($badge['category_id'])) {
                $badge['category_ids'] = [(int) $badge['category_id']];
            }

            return $badge;
        }, $decoded);
    }

    public static function forProduct(Product $product): ?array
    {
        $badges = self::badges();

        if (empty($badges)) {
            return null;
        }

        $productCategoryIds = self::productCategoryIds($product);

        foreach ($badges as $badge) {
            if (!self::isEnabled($badge['enabled'] ?? false)) {
                continue;
            }

            $matchIds = self::ruleMatchIds($badge);

            if (empty($matchIds)) {
                continue;
            }

            if (!empty(array_intersect($productCategoryIds, $matchIds))) {
                $normalized = self::normalizeBadge($badge);
                if ($normalized) {
                    return $normalized;
                }
            }
        }

        return null;
    }

    public static function ruleCategoryIds(array $badge): array
    {
        $ids = [];

        if (!empty($badge['category_ids']) && is_array($badge['category_ids'])) {
            foreach ($badge['category_ids'] as $categoryId) {
                $categoryId = (int) $categoryId;
                if ($categoryId > 0) {
                    $ids[] = $categoryId;
                }
            }
        }

        if (empty($ids) && !empty($badge['category_id'])) {
            $ids[] = (int) $badge['category_id'];
        }

        return array_values(array_unique($ids));
    }

    public static function ruleMatchIds(array $badge): array
    {
        $matchIds = [];

        foreach (self::ruleCategoryIds($badge) as $categoryId) {
            $matchIds = array_merge($matchIds, self::categoryWithDescendants($categoryId));
        }

        return array_values(array_unique($matchIds));
    }

    public static function productCategoryIds(Product $product): array
    {
        $ids = [];

        if ($product->category_id) {
            $ids[] = (int) $product->category_id;
        }

        if ($product->relationLoaded('categories')) {
            foreach ($product->categories as $category) {
                $ids[] = (int) $category->id;
            }
        } else {
            foreach ($product->categories()->pluck('categories.id') as $categoryId) {
                $ids[] = (int) $categoryId;
            }
        }

        $allIds = [];
        foreach (array_unique($ids) as $id) {
            $allIds = array_merge($allIds, self::categoryWithAncestors($id));
        }

        return array_values(array_unique(array_filter($allIds)));
    }

    public static function categoryWithAncestors(int $categoryId): array
    {
        $ids = [$categoryId];
        $category = Category::find($categoryId);

        while ($category && $category->parent_id) {
            $ids[] = (int) $category->parent_id;
            $category = Category::find($category->parent_id);
        }

        return $ids;
    }

    public static function categoryWithDescendants(int $categoryId): array
    {
        $ids = [$categoryId];
        $children = Category::where('parent_id', $categoryId)->pluck('id')->toArray();

        foreach ($children as $childId) {
            $ids = array_merge($ids, self::categoryWithDescendants((int) $childId));
        }

        return $ids;
    }

    public static function normalizeBadge(array $badge): ?array
    {
        $type = $badge['type'] ?? 'text';

        if (!in_array($type, ['text', 'image'], true)) {
            $type = 'text';
        }

        $normalized = [
            'type' => $type,
            'text' => trim((string) ($badge['text'] ?? '')),
            'image_id' => $badge['image_id'] ?? null,
            'image_width' => trim((string) ($badge['image_width'] ?? '120px')) ?: '120px',
        ];

        if ($type === 'image' && empty($normalized['image_id'])) {
            return null;
        }

        if ($type === 'text' && trim(strip_tags($normalized['text'])) === '') {
            return null;
        }

        return $normalized;
    }

    public static function isEnabled($value): bool
    {
        if (is_array($value)) {
            return in_array('1', $value, true) || in_array(1, $value, true);
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN) || $value === '1' || $value === 1;
    }

    public static function sanitizeBadges(array $badges): array
    {
        $sanitized = [];

        foreach ($badges as $badge) {
            if (!is_array($badge)) {
                continue;
            }

            $categoryIds = self::ruleCategoryIds($badge);
            if (empty($categoryIds)) {
                continue;
            }

            $type = ($badge['type'] ?? 'text') === 'image' ? 'image' : 'text';
            $text = trim((string) ($badge['text'] ?? ''));
            $imageId = $badge['image_id'] ?? null;

            if ($type === 'text' && trim(strip_tags($text)) === '') {
                continue;
            }

            if ($type === 'image' && empty($imageId)) {
                continue;
            }

            $sanitized[] = [
                'category_ids' => $categoryIds,
                'enabled' => self::isEnabled($badge['enabled'] ?? false),
                'type' => $type,
                'text' => $text,
                'image_id' => $imageId,
                'image_width' => trim((string) ($badge['image_width'] ?? '120px')) ?: '120px',
            ];
        }

        return $sanitized;
    }
}

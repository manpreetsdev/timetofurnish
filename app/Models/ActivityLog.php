<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'description',
        'properties',
        'url',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSubjectLabelAttribute()
    {
        if (empty($this->subject_type)) {
            return '-';
        }

        return class_basename($this->subject_type);
    }

    public function getChangeSummaryAttribute()
    {
        $properties = $this->properties ?: [];

        if ($this->action === 'updated' && !empty($properties['changes']) && is_array($properties['changes'])) {
            $lines = [];

            foreach ($properties['changes'] as $field => $change) {
                $old = $change['old'] ?? null;
                $new = $change['new'] ?? null;

                $lines[] = $field . ': ' . $this->stringifyValue($old) . ' -> ' . $this->stringifyValue($new);
            }

            return $lines;
        }

        if ($this->action === 'created' && !empty($properties['attributes']) && is_array($properties['attributes'])) {
            $lines = [];

            foreach (array_slice($properties['attributes'], 0, 5, true) as $field => $value) {
                $lines[] = $field . ': ' . $this->stringifyValue($value);
            }

            return $lines;
        }

        if ($this->action === 'deleted') {
            return ['Record removed'];
        }

        return [];
    }

    public function detailRows(): array
    {
        return [
            ['label' => 'User', 'value' => $this->user ? $this->user->name : 'System / Guest'],
            ['label' => 'Action', 'value' => $this->formatLabel($this->action)],
            ['label' => 'Subject', 'value' => trim($this->subject_label . ($this->subject_id ? ' #' . $this->subject_id : ''))],
            ['label' => 'Time', 'value' => $this->created_at ? $this->created_at->format('d-m-Y H:i:s') : '-'],
            ['label' => 'IP Address', 'value' => $this->ip_address ?: '-'],
            ['label' => 'URL', 'value' => $this->url ?: '-'],
            ['label' => 'Description', 'value' => $this->description ?: '-'],
        ];
    }

    public function propertyRows(): array
    {
        $properties = $this->properties ?: [];

        if ($this->action === 'updated' && !empty($properties['changes']) && is_array($properties['changes'])) {
            $rows = [];
            foreach ($properties['changes'] as $field => $change) {
                $rows[] = [
                    'field' => $this->formatLabel($field),
                    'old' => $this->stringifyValue($change['old'] ?? null),
                    'new' => $this->stringifyValue($change['new'] ?? null),
                ];
            }
            return $rows;
        }

        if ($this->action === 'created' && !empty($properties['attributes']) && is_array($properties['attributes'])) {
            $rows = [];
            foreach ($properties['attributes'] as $field => $value) {
                $rows[] = [
                    'field' => $this->formatLabel($field),
                    'value' => $this->stringifyValue($value),
                ];
            }
            return $rows;
        }

        $rows = [];
        foreach ($properties as $field => $value) {
            if (in_array($field, ['changes', 'attributes'], true)) {
                continue;
            }

            $rows[] = [
                'field' => $this->formatLabel($field),
                'value' => $this->stringifyValue($value),
            ];
        }

        return $rows;
    }

    public function hasChangeTable(): bool
    {
        return $this->action === 'updated' && !empty($this->properties['changes']) && is_array($this->properties['changes']);
    }

    public function hasAttributeTable(): bool
    {
        return $this->action === 'created' && !empty($this->properties['attributes']) && is_array($this->properties['attributes']);
    }

    protected function stringifyValue($value)
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_array($value)) {
            if ($this->isAssoc($value)) {
                $parts = [];
                foreach ($value as $key => $item) {
                    $parts[] = $this->formatLabel($key) . ': ' . $this->stringifyValue($item);
                }
                return implode(', ', $parts);
            }

            $parts = [];
            foreach ($value as $item) {
                $parts[] = $this->stringifyValue($item);
            }
            return implode(', ', $parts);
        }

        if ($value === null || $value === '') {
            return '-';
        }

        return (string) $value;
    }

    protected function formatLabel(string $value): string
    {
        return ucwords(str_replace(['_', '-'], ' ', $value));
    }

    protected function isAssoc(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }
}

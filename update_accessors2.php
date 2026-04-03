<?php
$files = glob(__DIR__ . '/app/Models/*.php');

$replacements = [
    "get: fn (\$value) => json_decode(\$value, true)," => "get: fn (?string \$value): mixed => json_decode((string) \$value, true),",
    "set: fn (\$value) => json_encode(\$value)," => "set: fn (mixed \$value): string|false => json_encode(\$value),",
    "get: fn (\$value) => Carbon::parse(\$value)->format('d M, Y')," => "get: fn (?string \$value): string => \Carbon\Carbon::parse(\$value)->format('d M, Y'),",
    "get: fn () => \$this->items->sum('sub_total')," => "get: fn (): float|int => \$this->items->sum('sub_total'),",
    "get: fn () => \$this->items->count()," => "get: fn (): int => \$this->items->count(),",
    "get: fn () => \$this->items->sum('quantity')," => "get: fn (): float|int => \$this->items->sum('quantity'),",
    "get: fn () => '$' . number_format(\$this->sub_total_with_conditions, 2)," => "get: fn (): string => '$' . number_format((float) \$this->sub_total_with_conditions, 2),",
    "get: fn () => '$' . number_format(\$this->tax, 2)," => "get: fn (): string => '$' . number_format((float) \$this->tax, 2),",
    "get: fn () => '$' . number_format(\$this->total, 2)," => "get: fn (): string => '$' . number_format((float) \$this->total, 2),",
    "get: fn () => '$' . number_format(\$this->discount, 2)," => "get: fn (): string => '$' . number_format((float) \$this->discount, 2),",
    "get: fn () => \$this->price * \$this->quantity," => "get: fn (): float|int => \$this->price * \$this->quantity,",
    "get: fn () => \$this->price_with_conditions * \$this->quantity," => "get: fn (): float|int => \$this->price_with_conditions * \$this->quantity,",
    "get: fn () => '$' . number_format((float) \$this->price, 2)," => "get: fn (): string => '$' . number_format((float) \$this->price, 2),",
    "get: fn () => '$' . number_format((float) \$this->sub_total, 2)," => "get: fn (): string => '$' . number_format((float) \$this->sub_total, 2),",
    "get: fn () => '$' . number_format(\$this->price_with_conditions, 2)," => "get: fn (): string => '$' . number_format((float) \$this->price_with_conditions, 2),",
    "get: fn () => (\$this->first_name ?? '') . ' ' . (\$this->last_name ?? '')," => "get: fn (): string => (\$this->first_name ?? '') . ' ' . (\$this->last_name ?? ''),",
    "get: fn (\$value) => \$value," => "get: fn (mixed \$value): mixed => \$value,",
    "set: fn (\$value) => \$value," => "set: fn (mixed \$value): mixed => \$value,",
    "set: fn (\$value) => preg_replace('/[^0-9]/', '', \$value)," => "set: fn (?string \$value): ?string => preg_replace('/[^0-9]/', '', (string) \$value),",
    "get: fn () => \$this->productWarehouse()->sum('qty')," => "get: fn (): float|int => \$this->productWarehouse()->sum('qty'),",
    "get: fn () => \$this->productWarehouse()->sum(DB::raw('qty * cost')) / 100," => "get: fn (): float|int => \$this->productWarehouse()->sum(\Illuminate\Support\Facades\DB::raw('qty * cost')) / 100,",
];

foreach ($files as $file) {
    $content = file_get_contents($file);
    foreach ($replacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    file_put_contents($file, $content);
}
echo "Done\n";

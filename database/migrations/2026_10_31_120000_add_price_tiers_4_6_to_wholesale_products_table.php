<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wholesale_products', function (Blueprint $table) {
            $table->decimal('price_tier_4', 8, 2)->default(0)->after('price_tier_3'); // 30 020–50 000 шт
            $table->decimal('price_tier_5', 8, 2)->default(0)->after('price_tier_4'); // 50 020–100 000 шт
            $table->decimal('price_tier_6', 8, 2)->default(0)->after('price_tier_5'); // от 100 020 шт
        });

        // Прайс 2027: общая шкала для всех сортов (заглушки без цены не трогаем)
        DB::table('wholesale_products')->where('price_tier_1', '>', 0)->update([
            'price_tier_1' => 59,
            'price_tier_2' => 58,
            'price_tier_3' => 57,
            'price_tier_4' => 56,
            'price_tier_5' => 55,
            'price_tier_6' => 54,
        ]);
    }

    public function down(): void
    {
        Schema::table('wholesale_products', function (Blueprint $table) {
            $table->dropColumn(['price_tier_4', 'price_tier_5', 'price_tier_6']);
        });
    }
};

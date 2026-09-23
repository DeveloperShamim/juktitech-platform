<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('fraud_checks')) {
            return;
        }

        Schema::table('fraud_checks', function (Blueprint $table) {
            if (!$this->indexExists('fraud_checks', 'fraud_checks_updated_at_index')) {
                $table->index('updated_at', 'fraud_checks_updated_at_index');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('fraud_checks')) {
            return;
        }

        Schema::table('fraud_checks', function (Blueprint $table) {
            if ($this->indexExists('fraud_checks', 'fraud_checks_updated_at_index')) {
                $table->dropIndex('fraud_checks_updated_at_index');
            }
        });
    }

    private function indexExists(string $table, string $index): bool
    {
        $connection = Schema::getConnection();
        $database = $connection->getDatabaseName();

        return (bool) $connection->selectOne(
            'SELECT 1 FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ? LIMIT 1',
            [$database, $table, $index]
        );
    }
};

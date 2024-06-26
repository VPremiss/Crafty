<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Support\Concerns;

use Closure;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use VPremiss\Crafty\Facades\CraftyPackage;
use VPremiss\Crafty\Support\Exceptions\CraftyDatabaseException;

trait CraftilyHandlesDatabase
{
    public function chunkedDatabaseInsertion(string $tableName, array $dataArrays, ?Closure $callback = null): void
    {
        $callback ??= fn ($dataArray) => $dataArray;
        $chunksCount = CraftyPackage::getConfiguration('crafty.databasing_chunks_count');
        $defaultProperties = CraftyPackage::getConfiguration('crafty.insertion_default_properties');
        $columnNames = Schema::getColumnListing($tableName);

        DB::beginTransaction();
        try {
            $chunks = array_chunk($dataArrays, $chunksCount);

            foreach ($chunks as $chunk) {
                $data = array_map(function ($dataArray) use ($tableName, $callback, $columnNames, $defaultProperties) {
                    $callbackData = $callback($dataArray);

                    if (!is_array($callbackData)) {
                        throw new CraftyDatabaseException(
                            'The callback must return an array for each data arrays item.'
                        );
                    }

                    $validatedData = array_filter($callbackData, function ($key) use ($columnNames) {
                        return in_array($key, $columnNames);
                    }, ARRAY_FILTER_USE_KEY);

                    if (empty($validatedData)) {
                        throw new CraftyDatabaseException(
                            "The callback array item does not return valid keys for the '$tableName' table."
                        );
                    }

                    return array_merge(
                        $validatedData,
                        $defaultProperties,
                    );
                }, $chunk);

                DB::table($tableName)->insert($data);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }
}

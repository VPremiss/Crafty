<?php

namespace VPremiss\Crafty\Concerns;

use Closure;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use VPremiss\Crafty\Exceptions\CraftyChunkedDatabaseInsertCallbackException;
use VPremiss\Crafty\Exceptions\CraftyConfigurationException;

trait CraftilyHandlesDatabase
{
    public function chunkedDatabaseInsertion(string $tableName, array $dataArrays, Closure $callback): void
    {
        $chunksCount = config('crafty.databasing_chunks', 500);
        $defaultProperties = config('crafty.insertion_default_properties');
        $columnNames = Schema::getColumnListing($tableName);

        if (intval($chunksCount) < 2) {
            throw new CraftyConfigurationException(
                "Database chunking count should be more than one. What's the point otherwise?!"
            );
        }

        // TODO create a helper that validates arrays for being associative and then choosing key and value types
        if (! is_array($defaultProperties) || count($defaultProperties) === 0) {
            throw new CraftyConfigurationException(
                'Database insertion default properties must be a filled array containing the essentials such as created_at, updated_at, etc.'
            );
        }

        DB::beginTransaction();
        try {
            $chunks = array_chunk($dataArrays, $chunksCount);

            foreach ($chunks as $chunk) {
                $data = array_map(function ($dataArray) use ($tableName, $callback, $columnNames, $defaultProperties) {
                    $callbackData = $callback($dataArray);

                    if (! is_array($callbackData)) {
                        throw new CraftyChunkedDatabaseInsertCallbackException('The callback must return an array for each data arrays item.');
                    }

                    $validatedData = array_filter($callbackData, function ($key) use ($columnNames) {
                        return in_array($key, $columnNames);
                    }, ARRAY_FILTER_USE_KEY);

                    if (empty($validatedData)) {
                        throw new CraftyChunkedDatabaseInsertCallbackException("The callback array item does not return valid keys for the '$tableName' table.");
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

<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\FlowwowProductImporter;
use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;
use Throwable;

class ImportFlowwowProduct extends Command
{
    protected $signature = 'products:import-flowwow
        {bundle : Папка с manifest.json и фотографиями после flowwow:fetch}
        {--content= : JSON с собственными name, description, price и categories}
        {--publish : Сразу опубликовать проверенный товар; по умолчанию создаётся черновик}';

    protected $description = 'Импортирует фотографии Flowwow с собственным названием и описанием';

    public function handle(FlowwowProductImporter $importer): int
    {
        try {
            $contentPath = $this->option('content');
            if (! is_string($contentPath) || $contentPath === '') {
                $this->error('Укажите --content=путь/к/content.json с собственными текстами, ценой и категориями.');

                return self::FAILURE;
            }
            $product = $importer->import(
                (string) $this->argument('bundle'),
                $importer->readJson($contentPath),
                (bool) $this->option('publish'),
            );
            $this->info('Создан '.($product->is_available ? 'товар' : 'черновик')." #{$product->id}: {$product->name}");
            $this->line('Фото: '.$product->media()->count());
            $this->line('Редактирование: '.url('/admin/products/'.$product->id.'/edit'));

            return self::SUCCESS;
        } catch (ValidationException $exception) {
            foreach ($exception->errors() as $messages) {
                foreach ($messages as $message) {
                    $this->error($message);
                }
            }
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());
        }

        return self::FAILURE;
    }
}
